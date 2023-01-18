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

    /**
     * Метод нахождения рандомной фотографии
     * @return string
     */
    public function getRandomTitleImage(): string
    {
       return TicketCategory::find()->where(['category_id' => $this->id])->orderBy('rand()')->one()->ticket->photo;
    }

    /**
     * Метод подсчета действующих объявлений в категории
     *
     * @return int
     * @throws InvalidConfigException
     */
    public function getCountTicketsInCategory(): int
    {
        return $this->getTickets()->where(['ticket.status' => 1])->count();
    }

    /**
     * Метод вывода списка всех категорий
     *
     * @return array
     */
    public static function getCategoriesList(): array
    {
        return Category::find()->all();
    }

    /**
     * Метод вывода списка категорий, у которых есть хотя бы одно объявление
     *
     * @return ActiveQuery
     */
    public static function getActiveCategoryList(): ActiveQuery
    {
        return
            Category::find()
                ->select('id, name, COUNT(ticket_category.category_id) as count')
                ->join('LEFT JOIN', 'ticket_category', 'ticket_category.category_id = category.id')
                ->groupBy('category.id')
                ->having('COUNT(ticket_category.category_id) > 0');
    }
}

