<?php

namespace app\controllers;

use app\models\Category;
use app\models\Ticket;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;

class SiteController extends Controller
{
    const LIMIT_TICKETS = 8;

    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['index', 'error'],
                        'roles'   => ['?', '@'],
                    ],
                ],
            ],
        ];
    }

    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    public function actionIndex()
    {
        $freshTicketsProvider = new ActiveDataProvider([
            'query' => Ticket::queryFreshTickets(),
            'pagination' => ['pageSize' => 8] // автоматически будет переписывать лимит на 8
        ]);

        $popularTicketsProvider = new ActiveDataProvider([
            'query' => Ticket::queryPopularTickets(),
            'pagination' => ['pageSize' => 8] // автоматически будет переписывать лимит на 8
        ]);

        $mainCategoriesProvider = new ActiveDataProvider([
            'query' => Category::queryCategoryList(),
        ]);

        return $this->render('index',
            [
                'mainCategoriesProvider' => $mainCategoriesProvider,
                'freshTicketsProvider' => $freshTicketsProvider,
                'popularTicketsProvider' => $popularTicketsProvider,
            ]
        );
    }
}
