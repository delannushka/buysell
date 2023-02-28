<?php

namespace app\controllers;

use Yii;
use yii\base\Exception;
use yii\filters\AccessControl;
use yii\web\Controller;
use app\models\forms\RegisterForm;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;

class RegisterController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class'        => AccessControl::class,
                'denyCallback' => function () {
                    throw new ForbiddenHttpException('Вы уже на сайте. Регистрироваться заново не нужно.',
                        403);
                },
                'rules'        => [
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
     * Страница с формой регистрации нового пользователя
     *
     * @return Response|string код страницы
     * @throws Exception
     * @throws ServerErrorHttpException
     */
    public function actionIndex(): Response|string
    {
        $registerForm = new RegisterForm();
        if (Yii::$app->request->getIsPost()) {
            $registerForm->load(Yii::$app->request->post());
            $registerForm->avatar = UploadedFile::getInstance($registerForm,
                'avatar');
            if ($registerForm->validate()) {
                $registerForm->createNewUser();

                return Yii::$app->response->redirect('/login');
            }
        }

        return $this->render('index', ['model' => $registerForm]);
    }
}