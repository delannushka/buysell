<?php

namespace app\models\forms;

use app\models\User;
use app\handlers\UploadFile;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;

class RegisterForm extends Model
{
    public ?string $name = null;
    public ?string $email = null;
    public ?string $password = null;
    public ?string $repeatPassword = null;

    public string|UploadedFile $avatar = '';

    public function attributeLabels(): array
    {
        return [
            'name'           => 'Имя и Фамилия',
            'email'          => 'Эл.почта',
            'password'       => 'Пароль',
            'repeatPassword' => 'Пароль еще раз',
            'avatar'         => 'Аватар',
        ];
    }

    public function rules(): array
    {
        return [
            [
                ['name', 'email', 'password', 'repeatPassword', 'avatar'],
                'required',
            ],
            [['name'], 'string'],
            ['email', 'email'],
            [
                'email',
                'unique',
                'targetClass' => User::class,
                'message'     => 'Пользователь с данным Email уже существует',
            ],
            ['password', 'string', 'min' => 6, 'max' => 64],
            ['repeatPassword', 'compare', 'compareAttribute' => 'password'],
            [['avatar'], 'file', 'extensions' => 'png, jpg'],
        ];
    }


    /**
     * Метод создания нового пользователя
     *
     * @throws Exception
     * @throws ServerErrorHttpException
     */
    public function createNewUser(): void
    {
        $user           = new User();
        $user->name     = $this->name;
        $user->email    = $this->email;
        $user->password = Yii::$app->getSecurity()
            ->generatePasswordHash($this->password);
        $user->avatar   = UploadFile::upload($this->avatar, 'avatar');
        if ($user->validate()) {
            $db          = Yii::$app->db;
            $transaction = $db->beginTransaction();
            try {
                $user->save();
                //по умолчанию пользователь становится обладателем роли user
                $auth       = Yii::$app->authManager;
                $authorRole = $auth->getRole('user');
                $auth->assign($authorRole, $user->id);
                $transaction->commit();
            } catch (Exception $e) {
                Yii::$app->errorHandler->logException($e);
                $transaction->rollback();
                throw new ServerErrorHttpException('Проблема на сервере. Зарегистрироваться не удалось.');
            }
        }
    }

}