<?php

namespace app\controllers;

use app\models\forms\LoginForm;
use app\models\rbac\AuthorRule;
use delta\AuthHandler;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\widgets\ActiveForm;

class LoginController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'denyCallback' => function () {
                    echo('Если вы на сайте - входить заново не надо! 
                    Если вы не на сайте, не надо пытаться из него выходить!');
                },
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['index', 'auth', 'vk'],
                        'roles'   => ['?'],
                    ],
                    [
                        'allow'   => true,
                        'actions' => ['logout'],
                        'roles'   => ['@'],
                    ],
                ],

            ],
        ];
    }

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

    public function actionAuth()
    {
        $url = Yii::$app->authClientCollection->getClient("vkontakte")->buildAuthUrl(); // Build authorization URL
        Yii::$app->getResponse()->redirect($url); // Redirect to authorization URL.
    }

    /**
     * @throws \yii\db\Exception
     * @throws \yii\web\HttpException
     */
    public function actionVk()
    {
        // After user returns at our site:
        $code = Yii::$app->request->get('code');
        $authHandler = new AuthHandler($code);

        if (!$authHandler->isAuthExist()) {
            $authHandler->saveAuthUser();
        }
        Yii::$app->user->login($authHandler->getAuth()->user);
        $this->redirect('/offers');
    }

    public function actionLogout(): Response
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}