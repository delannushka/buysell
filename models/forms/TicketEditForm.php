<?php

namespace app\models\forms;

use app\models\Ticket;
use Yii;

class TicketEditForm extends \yii\base\Model
{
    public $header;
    public $text;
    public $price;
    public $type;
    public $categories;
    public $avatar;

    const MAX_HEADER = 100;
    const MIN_HEADER = 10;
    const MAX_TEXT = 1000;
    const MIN_TEXT = 50;

    public function attributeLabels(): array
    {
        return [
            'header' => 'Название',
            'text' => 'Описание',
            'price' => 'Цена',
            'type' => 'Куплю или Продам',
            'categories' => 'Выберите категорию',
            'avatar' => 'Фотография объявления'
        ];
    }

    public function rules(): array
    {
        return [
            [['header', 'text', 'price', 'type', 'categories'], 'required'],
            ['header', 'string', 'min' => self::MIN_HEADER, 'max' => self::MAX_HEADER],
            ['text', 'string', 'min' => self::MIN_TEXT, 'max' => self::MAX_TEXT],
        ];
    }


}