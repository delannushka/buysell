<?php

namespace app\controllers;

use app\models\Ticket;
use http\Url;
use Yii;
use yii\data\ActiveDataProvider;
use yii\web\ServerErrorHttpException;

class MyController extends \yii\web\Controller
{
    public function actionIndex(){

        $id = \Yii::$app->user->id;

        $myTicketsProvider = new ActiveDataProvider([
            'query' => Ticket::find()
            ->where([
                'user_id' => $id,
                'status' => 1
            ])
        ]);


        return $this->render('index', [
            'myTicketsProvider' => $myTicketsProvider
        ]);
    }

    public function actionComments()
    {
        return $this->render('comments');
    }

    /**
     * @throws \yii\db\StaleObjectException
     * @throws \Throwable
     */
    public function actionDelete($id){
        $ticket = Ticket::findOne($id);
        if ($ticket->deleteTicket()){
            return $this->redirect('/my');
        } else {
            throw new \Exception('Не удалось удалить объявдение');
        }
    }
}