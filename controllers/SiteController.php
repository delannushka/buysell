<?php

namespace app\controllers;

use app\models\Category;
use app\models\Ticket;
use app\models\TicketCategory;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\Response;
use yii\filters\VerbFilter;
use app\models\LoginForm;
use app\models\ContactForm;

class SiteController extends Controller
{
    const LIMIT_TICKETS = 8;
    /**
     * {@inheritdoc}
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'only' => ['logout'],
                'rules' => [
                    [
                        'actions' => ['logout'],
                        'allow' => true,
                        'roles' => ['@'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::class,
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return string
     */
    public function actionIndex()
    {
        $freshTicketsProvider = new ActiveDataProvider([
            'query' => Ticket::find()->orderBy('date_add DESC')->limit(self::LIMIT_TICKETS),
        ]);

        $popularTicketsProvider = new ActiveDataProvider([
            'query' => Ticket::find()
                ->join('LEFT JOIN', 'comment', 'comment.ticket_id = ticket.id')
                ->groupBy('ticket.id')
                ->having('COUNT(comment.id) > 0')
                ->orderBy('COUNT(comment.id) DESC')
                ->limit(self::LIMIT_TICKETS),
        ]);

        $mainCategoriesProvider = new ActiveDataProvider([
            'query' => Category::find()
                ->select('id, name, COUNT(ticket_category.category_id) as count')
                ->join('LEFT JOIN', 'ticket_category', 'ticket_category.category_id = category.id')
                ->groupBy('category.id')
                ->having('COUNT(ticket_category.category_id) > 0')
            ]
        );

        return $this->render('index',
            [
                'mainCategoriesProvider' => $mainCategoriesProvider,
                'freshTicketsProvider' => $freshTicketsProvider,
                'popularTicketsProvider' => $popularTicketsProvider,
            ]
        );
    }

    /**
     * Login action.
     *
     * @return Response|string
     */
    public function actionLogin()
    {
        if (!Yii::$app->user->isGuest) {
            return $this->goHome();
        }

        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()) {
            return $this->goBack();
        }

        $model->password = '';
        return $this->render('login', [
            'model' => $model,
        ]);
    }

    /**
     * Logout action.
     *
     * @return Response
     */
    public function actionLogout()
    {
        Yii::$app->user->logout();

        return $this->goHome();
    }

    /**
     * Displays contact page.
     *
     * @return Response|string
     */
    public function actionContact()
    {
        $model = new ContactForm();
        if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
            Yii::$app->session->setFlash('contactFormSubmitted');

            return $this->refresh();
        }
        return $this->render('contact', [
            'model' => $model,
        ]);
    }

    /**
     * Displays about page.
     *
     * @return string
     */
    public function actionAbout()
    {
        return $this->render('about');
    }
}
