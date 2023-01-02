<?php

namespace app\models;

use Yii;
use yii\data\ActiveDataProvider;
use yii\data\Pagination;
use yii\db\ActiveQuery;

/**
 * This is the model class for table "ticket".
 *
 * @property int $id
 * @property int $status
 * @property int $user_id
 * @property string $header
 * @property string $photo
 * @property int $price
 * @property string $type
 * @property string $text
 * @property int|null $date_add
 *
 * @property Category[] $categories
 * @property Comment[] $comments
 * @property TicketCategory[] $ticketCategories
 * @property User $user
 */
class Ticket extends \yii\db\ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'ticket';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['status', 'user_id', 'price'], 'integer'],
            [['user_id', 'header', 'price', 'type', 'text', 'photo'], 'required'],
            [['type'], 'string'],
            [['date_add'], 'safe'],
            [['header'], 'string', 'max' => 100],
            [['photo'], 'string', 'max' => 255],
            [['text'], 'string', 'max' => 1000],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'status' => 'Status',
            'user_id' => 'User ID',
            'header' => 'Header',
            'photo' => 'Photo',
            'price' => 'Price',
            'type' => 'Type',
            'text' => 'Text',
            'date_add' => 'Date Add',
        ];
    }

    /**
     * Gets query for [[Categories]].
     *
     * @return ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::class, ['id' => 'category_id'])->viaTable('ticket_category', ['ticket_id' => 'id']);
    }

    /**
     * Gets query for [[Comments]].
     *
     * @return ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::class, ['ticket_id' => 'id']);
    }

    /**
     * Gets query for [[TicketCategories]].
     *
     * @return ActiveQuery
     */
    public function getTicketCategories()
    {
        return $this->hasMany(TicketCategory::class, ['ticket_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public static function getAllByCategory(Category $category)
    {
        $query = Ticket::find()
            ->select('id, status, header, photo, price, type, text, ticket_category.category_id as category_id')
            ->leftJoin('ticket_category', 'ticket_category.ticket_id = ticket_id')
            ->having('ticket.status = 1 and ticket_category.category_id = ' . $category->id);

        return new ActiveDataProvider([
            'query' => $query,
            'totalCount' => $query->count(),
            'pagination' => [
                'pageSize' => 8,
                'forcePageParam' => false,
                'pageSizeParam' => false
            ],
            'sort' => [
                'defaultOrder' => [
                    'id' => SORT_DESC
                ]
            ],
        ]);
    }

}
