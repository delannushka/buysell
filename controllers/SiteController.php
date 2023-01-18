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

    /**
     * Главная страница
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $freshTicketsProvider = new ActiveDataProvider([
            'query' => Ticket::getFreshTickets(),
            'pagination' => ['pageSize' => self::LIMIT_TICKETS] // автоматически будет переписывать лимит на 8
        ]);

        $popularTicketsProvider = new ActiveDataProvider([
            'query' => Ticket::getPopularTickets(),
            'pagination' => ['pageSize' => self::LIMIT_TICKETS] // автоматически будет переписывать лимит на 8
        ]);

        $mainCategoriesProvider = new ActiveDataProvider([
            'query' => Category::getActiveCategoryList(),
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
