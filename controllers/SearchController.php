<?php

namespace app\controllers;

use app\models\Ticket;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;

class SearchController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['index'],
                        'roles'   => ['?', '@'],
                    ],
                ],
            ],
        ];
    }

    function actionIndex()
    {
        $query = Yii::$app->request->get('query');

        $searchTicketsProvider = new ActiveDataProvider([
            'query' => Ticket::find()->where("status = 1 and MATCH(header) AGAINST('{$query}')")
        ]);

        $freshTicketsProvider = new ActiveDataProvider([
            'query' => Ticket::find()->where(['status' => 1])->orderBy('date_add DESC')->limit(8),
        ]);

        return $this->render('index', [
            'searchTicketsProvider' => $searchTicketsProvider,
            'freshTicketsProvider' => $freshTicketsProvider
        ]);
    }
}