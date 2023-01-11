<?php

namespace app\models;

use yii\base\InvalidConfigException;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;

/**
 * This is the model class for table "category".
 *
 * @property int $id
 * @property string $name
 *
 * @property TicketCategory[] $ticketCategories
 * @property Ticket[] $tickets
 */
class Category extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'category';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name'], 'required'],
            [['name'], 'string', 'max' => 12],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
        ];
    }

    /**
     * Gets query for [[TicketCategories]].
     *
     * @return ActiveQuery
     */
    public function getTicketCategories(): ActiveQuery
    {
        return $this->hasMany(TicketCategory::class, ['category_id' => 'id']);
    }

    /**
     * Gets query for [[Tickets]].
     *
     * @return ActiveQuery
     * @throws InvalidConfigException
     */
    public function getTickets(): ActiveQuery
    {
        return $this->hasMany(Ticket::class, ['id' => 'ticket_id'])->viaTable('ticket_category', ['category_id' => 'id']);
    }

    public function getRandomTitleImage()
    {
       $randomTicket = TicketCategory::find()->where(['category_id' => $this->id])->orderBy('rand()')->one();
        return $randomTicket->ticket->photo;
    }

    public static function getCategoriesList(): array
    {
        return Category::find()->all();
    }

    /**
     * @throws InvalidConfigException
     */
    public function getCountTicketsInCategory(){
        return $this->getTickets()->where(['ticket.status' => 1])->count();
    }

}

