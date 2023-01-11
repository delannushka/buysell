<?php

namespace app\controllers;

use app\models\User;
use delta\UploadFile;
use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\forms\RegisterForm;
use yii\web\ForbiddenHttpException;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;

class RegisterController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'denyCallback' => function () {
                    throw new ForbiddenHttpException('Вы уже на сайте. Регистрироваться заново не нужно.', 403);
                },
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['index'],
                        'roles'   => ['?'],
                    ],
                ],
            ],
        ];
    }


    /**
     * @throws Exception
     * @throws \Exception
     */
    public function actionIndex()
    {
        $registerForm = new RegisterForm();
        if (Yii::$app->request->getIsPost()) {
            $registerForm->load(Yii::$app->request->post());
            $registerForm->avatar = UploadedFile::getInstance($registerForm, 'avatar');
            if ($registerForm->validate()) {
                $user = new User();
                $user->name = $registerForm->name;
                $user->email = $registerForm->email;
                $user->password = Yii::$app->getSecurity()->generatePasswordHash($registerForm->password);
                $user->avatar = UploadFile::upload($registerForm->avatar, 'avatar');
                if ($user->validate()){
                    $db = Yii::$app->db;
                    $transaction = $db->beginTransaction();
                    try {
                        $user->save();
                        //по умолчанию пользователь становится обладателем роли user
                        $auth = Yii::$app->authManager;
                        $authorRole = $auth->getRole('user');
                        $auth->assign($authorRole, $user->id);
                        $transaction->commit();
                    } catch (Exception $e){
                        $transaction->rollback();
                        throw new ServerErrorHttpException('Проблема на сервере. Зарегистрироваться не удалось.');
                    }
                }
                return Yii::$app->response->redirect('/login');
            }
        }
        return $this->render('index', ['model'=> $registerForm]);
    }
}