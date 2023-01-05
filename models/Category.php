<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string $name
 *
 * @property TicketCategory[] $ticketCategories
 * @property Ticket[] $tickets
 */
class Category extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 12],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[TicketCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTicketCategories()
    {
        return $this->hasMany(TicketCategory::class, ['category_id' => 'id']);
    }

    /**
     * Gets query for [[Tickets]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTickets()
    {
        return $this->hasMany(Ticket::class, ['id' => 'ticket_id'])->viaTable('ticket_category', ['category_id' => 'id']);
    }

    public function getRandomTitleImage()
    {
       $randomTicket = TicketCategory::find()->where(['category_id' => $this->id])->orderBy('rand()')->one();
        return $randomTicket->ticket->photo;
    }

    public static function getCategoriesList()
    {
        return Category::find()->all();
    }

    public function getCountTicketsInCategory(){
        return $this->getTickets()->where(['ticket.status' => 1])->count();
    }

}

