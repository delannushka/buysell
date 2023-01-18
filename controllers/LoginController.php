<?php

namespace app\controllers;

use app\models\forms\LoginForm;
use delta\AuthHandler;
use Yii;
use yii\db\Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\HttpException;
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
                    throw new ForbiddenHttpException('Данная страница вам не доступна', 403);
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

    /**
     * Страница входа.
     *
     * @return Response|array|string код страницы
     */
    public function actionIndex(): Response|array|string
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

    /**
     * Метод для переадресации на URL авторизации VK
     */
    public function actionAuth()
    {
        $url = Yii::$app->authClientCollection->getClient("vkontakte")->buildAuthUrl(); // Build authorization URL
        Yii::$app->getResponse()->redirect($url); // Redirect to authorization URL.
    }

    /**
     * Вход на сайт через процедуру аутентификации через VK
     *
     * @throws HttpException
     * @throws \Exception
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
        $this->redirect('/');
    }

    /**
     * Выход с сайта.
     *
     * @return Response
     */
    public function actionLogout(): Response
    {
        Yii::$app->user->logout();
        return $this->goHome();
    }
}