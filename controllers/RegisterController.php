<?php

namespace app\controllers;

use app\models\User;
use Yii;
use yii\base\Exception;
use yii\web\Controller;
use app\models\forms\RegisterForm;
use yii\web\UploadedFile;

class RegisterController extends Controller
{
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
                $user->avatar = $user->uploadAvatar($registerForm->avatar);
                if ($user->validate()){
                    $user->save();
                }
                return Yii::$app->response->redirect('/login');
            }
        }
        return $this->render('index', ['model'=> $registerForm]);
    }
}