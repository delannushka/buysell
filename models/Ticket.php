<?php

namespace app\models;

use Exception;
use Yii;
use yii\base\InvalidConfigException;
use yii\data\ActiveDataProvider;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\ServerErrorHttpException;

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
class Ticket extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'ticket';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
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
    public function attributeLabels(): array
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
     * @throws InvalidConfigException
     */
    public function getCategories(): ActiveQuery
    {
        return $this->hasMany(Category::class, ['id' => 'category_id'])->viaTable('ticket_category', ['ticket_id' => 'id']);
    }

    /**
     * Gets query for [[Comments]].
     *
     * @return ActiveQuery
     */
    public function getComments(): ActiveQuery
    {
        return $this->hasMany(Comment::class, ['ticket_id' => 'id']);
    }

    /**
     * Gets query for [[TicketCategories]].
     *
     * @return ActiveQuery
     */
    public function getTicketCategories(): ActiveQuery
    {
        return $this->hasMany(TicketCategory::class, ['ticket_id' => 'id']);
    }

    /**
     * Gets query for [[User]].
     *
     * @return ActiveQuery
     */
    public function getUser(): ActiveQuery
    {
        return $this->hasOne(User::class, ['id' => 'user_id']);
    }

    public static function getAllByCategory(Category $category): ActiveDataProvider
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

    /**
     * @throws ServerErrorHttpException
     */
    public function deleteTicket(): bool
    {
        $db = Yii::$app->db;
        $transaction = $db->beginTransaction();
        try {
            $this->status = 0;
            $comments = $this->comments;
            foreach ($comments as $comment) {
                $comment->deleteComment();
            }
            $this->save();
            $transaction->commit();
        } catch (Exception $e){
            $transaction->rollback();
            throw new ServerErrorHttpException('Проблема на сервере. Объявление удалить не удалилось.');
        }
        return true;
    }

}
