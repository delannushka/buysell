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
            'query' => Ticket::find()->where(['status' => 1])->orderBy('date_add DESC')->limit(self::LIMIT_TICKETS),
        ]);

        $popularTicketsProvider = new ActiveDataProvider([
            'query' => Ticket::find()
                ->join('LEFT JOIN', 'comment', 'comment.ticket_id = ticket.id')
                ->groupBy('ticket.id')
                ->having('COUNT(comment.id) > 0 AND status = 1')
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
}
