<?php

namespace app\models;

use Yii;
use yii\base\Exception;
use yii\db\ActiveQuery;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int         $id
 * @property string      $name
 * @property string      $email
 * @property string      $password
 * @property string|null $avatar
 * @property string|null $date_add
 * @property Comment[]   $comments
 * @property Ticket[]    $tickets
 * @property Auth[]      $auths
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName(): string
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['name', 'email', 'password'], 'required'],
            [['date_add'], 'safe'],
            [['name', 'email', 'avatar'], 'string', 'max' => 255],
            [['password'], 'string', 'max' => 64],
            [['email'], 'unique'],
        ];
    }

    /**
     * Gets query for [[Auths]].
     *
     * @return ActiveQuery
     */
    public function getAuths(): ActiveQuery
    {
        return $this->hasMany(Auth::class, ['user_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels(): array
    {
        return [
            'id'       => 'ID',
            'name'     => 'Name',
            'email'    => 'Email',
            'password' => 'Password',
            'avatar'   => 'Avatar',
            'date_add' => 'Date Add',
        ];
    }

    /**
     * Gets query for [[Comments]].
     *
     * @return ActiveQuery
     */
    public function getComments(): ActiveQuery
    {
        return $this->hasMany(Comment::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Tickets]].
     *
     * @return ActiveQuery
     */
    public function getTickets(): ActiveQuery
    {
        return $this->hasMany(Ticket::class, ['user_id' => 'id']);
    }

    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    public static function findIdentityByAccessToken($token, $type = null)
    {
        // TODO: Implement findIdentityByAccessToken() method.
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAuthKey()
    {
        return null;
    }

    public function validateAuthKey($authKey)
    {
        return null;
    }

    /**
     * Метод сохранения данных юзера, выполнившего вход на сайт через VK в таблицу User
     *
     * @throws Exception
     */
    public function loadAuthUser($userVk): void
    {
        $this->email    = $userVk['email'];
        $this->name     = $userVk['first_name'].' '.$userVk['last_name'];
        $this->avatar   = $userVk['photo'];
        $this->password = Yii::$app->security->generateRandomString(6);
    }
}
