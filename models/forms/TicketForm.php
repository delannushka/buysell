<?php

namespace app\models\forms;

use app\models\TicketCategory;
use delta\TicketHandler;
use app\models\Ticket;
use delta\UploadFile;
use Yii;
use yii\base\Model;
use yii\db\Exception;
use yii\web\ServerErrorHttpException;
use yii\web\UploadedFile;

class TicketForm extends Model
{
    public $avatar;
    public $header;
    public $text;
    public $categories;
    public $price;
    public $type;

    const MAX_HEADER = 100;
    const MIN_HEADER = 10;
    const MAX_TEXT = 1000;
    const MIN_TEXT = 50;
    const MIN_PRICE = 100;

    public function attributeLabels(): array
    {
        return [
            'header' => 'Название',
            'text' => 'Описание',
            'categories' => 'Выберите категорию публикации',
            'price' => 'Цена',
        ];
    }

    public function rules(): array
    {
        return [
            [['header', 'text', 'categories', 'price', 'type'], 'required'],
            ['header', 'string', 'min' => self::MIN_HEADER, 'max' => self::MAX_HEADER],
            ['text', 'string', 'min' => self::MIN_TEXT, 'max' => self::MAX_TEXT],
            ['price', 'integer', 'min' => self::MIN_PRICE],
            ['avatar', 'file', 'extensions' => 'png, jpg'],
            [['avatar'], 'validateImage', 'skipOnEmpty' => false],
            ['type', 'in', 'range' => [TicketHandler::TICKET_SELL, TicketHandler::TICKET_BUY]],
        ];
    }

    public function validateImage()
    {
        $ticket = Ticket::findOne(Yii::$app->request->get('id'));
        if(!$ticket && !$this->avatar )
        {
            $this->addError('avatar', 'Загрузите фотографию');
        }
    }

    /**
     * @throws ServerErrorHttpException
     */
    public function createNewTicket()
    {
        $this->avatar = UploadedFile::getInstance($this, 'avatar');

        if ($this->validate()) {
            $ticket = new Ticket();
            $ticket->user_id = Yii::$app->user->getId();
            $ticket->header = $this->header;
            $ticket->text = $this->text;
            $ticket->price = $this->price;
            $ticket->type = $this->type;
            $ticket->photo = UploadFile::upload($this->avatar, 'tickets');
            $db = Yii::$app->db;
            $transaction = $db->beginTransaction();
            try {
                $ticket->save();
                foreach ($this->categories as $category) {
                    $ticketCategory = new TicketCategory();
                    $ticketCategory->ticket_id = $ticket->id;
                    $ticketCategory->category_id = $category;
                    $ticketCategory->save();
                }
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollback();
                throw new ServerErrorHttpException('Проблема на сервере. Создать объявление не удалось.');
            }
            return $ticket->id;
        }
        return false;
    }

    public function autocompleteEditForm(Ticket $ticket): void
    {
        $this->header = $ticket->header;
        $this->text = $ticket->text;
        $this->price = $ticket->price;
        $this->type = $ticket->type;
        $this->categories = TicketCategory::find()->select('category_id')->where(['ticket_id'=> $ticket->id])->column();
        $this->avatar = $ticket->photo;
    }

    /**
     * @throws ServerErrorHttpException
     */

    public function editTicket(Ticket $ticket)
    {
        $this->avatar = UploadedFile::getInstance($this, 'avatar');
        if ($this->validate()) {
            $ticket->header = $this->header;
            $ticket->text = $this->text;
            $ticket->price = $this->price;
            $ticket->type = $this->type;
            //если поменяли фото
            if ($this->avatar) {
                $ticket->photo = UploadFile::upload($this->avatar, 'tickets');
            }
            $db = Yii::$app->db;
            $transaction = $db->beginTransaction();
            try {
                $ticket->save();
                TicketCategory::deleteAll(['ticket_id' => $ticket->id]);
                foreach ($this->categories as $category) {
                    $ticketCategory = new TicketCategory();
                    $ticketCategory->ticket_id = $ticket->id;
                    $ticketCategory->category_id = $category;
                    $ticketCategory->save();
                }
                $transaction->commit();
            } catch (Exception $e) {
                $transaction->rollback();
                throw new ServerErrorHttpException('Проблема на сервере. Отредактировать объявление не удалось.');
            }
        }
    }

}

