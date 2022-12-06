<?php

namespace app\models;

use Yii;

/**
 * This is the model class for table "ticket".
 *
 * @property int $id
 * @property int $status
 * @property int $user_id
 * @property string $header
 * @property string|null $photo
 * @property int $price
 * @property string $type
 * @property string $text
 * @property string|null $date_add
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
            [['user_id', 'header', 'price', 'type', 'text'], 'required'],
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
     * @return \yii\db\ActiveQuery
     */
    public function getCategories()
    {
        return $this->hasMany(Category::class, ['id' => 'category_id'])->viaTable('ticket_category', ['ticket_id' => 'id']);
    }

    /**
     * Gets query for [[Comments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::class, ['ticket_id' => 'id']);
    }

    /**
     * Gets query for [[TicketCategories]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTicketCategories()
    {
        return $this->hasMany(TicketCategory::class, ['ticket_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getUser()
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }
}
