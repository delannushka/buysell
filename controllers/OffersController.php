<?php

namespace app\controllers;

use app\models\forms\LoginForm;
use app\models\forms\TicketForm;
use app\models\Ticket;
use app\models\TicketCategory;
use Exception;
use Yii;
use yii\web\Response;
use yii\web\UploadedFile;
use yii\widgets\ActiveForm;
use delta\UploadFile;

class OffersController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('view');
    }

    /**
     * @throws Exception
     */
    public function actionAdd()
    {
        $newTicket = new TicketForm();

        if (Yii::$app->request->getIsPost()) {
            $newTicket->load(Yii::$app->request->post());
            $newTicket->avatar = UploadedFile::getInstance($newTicket, 'avatar');

            if ($newTicket->validate()) {
                $ticket = new Ticket();
                $ticket->user_id = Yii::$app->user->getId();
                $ticket->header = $newTicket->header;
                $ticket->text = $newTicket->text;
                $ticket->price = $newTicket->price;
                $ticket->type = $newTicket->type;
                $ticket->photo = UploadFile::upload($newTicket->avatar, 'tickets');

                if ($ticket->save()) {
                    foreach ($newTicket->categories as $category) {
                        $ticketCategory = new TicketCategory();
                        $ticketCategory->ticket_id = $ticket->id;
                        $ticketCategory->category_id = $category;
                        $ticketCategory->save();
                    }
                    return Yii::$app->response->redirect("/offers/{$ticket->id}");
                }
            }
        }
        return $this->render('add.php', ['model' => $newTicket]);
    }
}