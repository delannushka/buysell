<?php

namespace app\models\forms;

use delta\TicketHandler;
use app\models\Ticket;
use Yii;
use yii\base\Model;

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

}

