<?php

namespace app\widgets;

use app\models\Ticket;
use yii\base\Widget;

class TicketViewWidget extends Widget
{
    public Ticket $ticket;

    public function run()
    {
        return $this->render('ticket', ['ticket' => $this->ticket]);
    }
}