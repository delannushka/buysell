<?php

namespace app\models;

use Yii;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\ServerErrorHttpException;

/**
 * This is the model class for table "comment".
 *
 * @property int $id
 * @property int $user_id
 * @property int $ticket_id
 * @property string $text
 * @property string|null $date
 * @property int $status
 *
 * @property Ticket $ticket
 * @property User $user
 */
class Comment extends ActiveRecord
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'comment';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['user_id', 'ticket_id', 'text'], 'required'],
            [['user_id', 'ticket_id', 'status'], 'integer'],
            [['date'], 'safe'],
            [['text'], 'string', 'max' => 255],
            [['user_id'], 'exist', 'skipOnError' => true, 'targetClass' => User::class, 'targetAttribute' => ['user_id' => 'id']],
            [['ticket_id'], 'exist', 'skipOnError' => true, 'targetClass' => Ticket::class, 'targetAttribute' => ['ticket_id' => 'id']],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id' => 'ID',
            'user_id' => 'User ID',
            'ticket_id' => 'Ticket ID',
            'text' => 'Text',
            'date' => 'Date',
            'status' => 'Status'
        ];
    }

    /**
     * Gets query for [[Ticket]].
     *
     * @return ActiveQuery
     */
    public function getTicket(): ActiveQuery
    {
        return $this->hasOne(Ticket::class, ['id' => 'ticket_id']);
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

    /**
     * Метод удаления комментария со страницы объявления
     *
     * @return bool
     * @throws ServerErrorHttpException
     */
    public function deleteComment(): bool
    {
        $this->status = 0;
        if ($this->save()){
            return true;
        }
        else {
            throw new ServerErrorHttpException('Проблема на сервере. Комментарий удалить не удалилось.');
        }
    }

    /**
     * Метод сохранения комментария к объявлению
     *
     * @param Ticket $ticket - объявление
     * @param string $commentText - текст комментария
     * @return bool
     * @throws ServerErrorHttpException
     */
    public function saveComment(Ticket $ticket, string $commentText): bool
    {
        $this->user_id = Yii::$app->user->id;
        $this->ticket_id = $ticket->id;
        $this->text = $commentText;
        if($this->save()) {
            return true;
        } else {
            throw new ServerErrorHttpException('Проблема на сервере. Комментарий сохранить не удалилось.');
        }
    }
}
