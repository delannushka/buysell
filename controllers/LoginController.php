<?php

namespace app\controllers;

use app\models\forms\LoginForm;
use Yii;
use yii\web\Response;
use yii\widgets\ActiveForm;

class LoginController extends \yii\web\Controller
{
    public function actionIndex()
    {
        $loginForm = new LoginForm();

        if (Yii::$app->request->getIsPost()) {
            $loginForm->load(Yii::$app->request->post());
            if (Yii::$app->request->isAjax) {
                Yii::$app->response->format = Response::FORMAT_JSON;
                return ActiveForm::validate($loginForm);
            }
            if ($loginForm->validate()) {
                $user = $loginForm->getUser();
                Yii::$app->user->login($user);
                return $this->goHome();
            }
        }
        return $this->render('index', ['model' => $loginForm]);
    }

}