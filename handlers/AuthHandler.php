<?php

namespace app\handlers;

use app\models\Auth;
use app\models\User;
use Exception;
use Yii;
use yii\authclient\clients\VKontakte;
use yii\helpers\ArrayHelper;
use yii\web\HttpException;
use yii\web\ServerErrorHttpException;

/**
 * @property array $attributes Массив информации о пользователе
 * @property VKontakte $vk клиент VK
 * @property Auth $auth модель Auth для хранения пользователей авторизованных через VK
 * @param string $code Полученный от VK API код
 */

class AuthHandler
{
    public $attributes;
    public $vk;
    public $auth;

    /**
     * @throws HttpException|Exception
     */
    public function __construct(string $code)
    {
        $this->vk = Yii::$app->authClientCollection->getClient("vkontakte");
        $accessToken = $this->vk->fetchAccessToken($code);
        $this->attributes = $this->vk->getUserAttributes();
        $this->attributes['email'] = ArrayHelper::getValue($accessToken->params, 'email');
    }

    public function getUser(): User
    {
        $user = User::findOne(['email' => $this->attributes['email']]);
        if ($user) {
            return $user;
        }
        return new User();
    }

    public function getAuth(): Auth
    {
        return $this->auth;
    }

    /**
     * Метод нахождения юзера в таблице Auth
     *
     * @return bool
     */
    public function isAuthExist(): bool
    {
        $this->auth = Auth::find()->where([
            'source' => $this->vk->getId(),
            'source_id' => $this->attributes['id'],
        ])->one();

        if ($this->auth) {
            return true;
        }
        return false;
    }

    /**
     * Метод сохранения данных юзера в таблицу Auth
     *
     * @throws ServerErrorHttpException|\yii\base\Exception
     */
    public function saveAuthUser(): void
    {
        $user = $this->getUser();
        $user->loadAuthUser($this->attributes);
        $transaction = Yii::$app->db->beginTransaction();
        try {
            if ($user->save()) {
                $this->auth = new Auth([
                    'user_id' => $user->id,
                    'source' => $this->vk->getId(),
                    'source_id' => (string)$this->attributes['id'],
                ]);
                if ($this->auth->save()) {
                    $transaction->commit();
                }
            }
        } catch (Exception $e) {
            Yii::$app->errorHandler->logException($e);
            $transaction->rollback();
            throw new ServerErrorHttpException('Не удалось сохранить данные');
        }
    }
}