<?php

namespace app\models;

use Exception;
use Yii;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

/**
 * This is the model class for table "user".
 *
 * @property int $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property int $moderator
 * @property string|null $avatar
 * @property string|null $date_add
 * @property Comment[] $comments
 * @property Ticket[] $tickets
 * @property Auth[] $auths
 */
class User extends ActiveRecord implements IdentityInterface
{
    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['name', 'email', 'password'], 'required'],
            [['moderator'], 'integer'],
            [['date_add'], 'safe'],
            [['name', 'email', 'avatar'], 'string', 'max' => 255],
            [['password'], 'string', 'max' => 64],
            [['email'], 'unique'],
        ];
    }

    /**
     * @throws Exception
     */
    public function uploadAvatar($fileForSave): string
    {
        $fileName = uniqid('upload') . '.' . $fileForSave->getExtension();
        if ($fileForSave->saveAs('@webroot/uploads/' . $fileName)) {
            return $fileName;
        }
        throw new Exception('Ошибка сохранения');
    }

    /**
     * Gets query for [[Auths]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getAuths()
    {
        return $this->hasMany(Auth::class, ['user_id' => 'id']);
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => 'ID',
            'name' => 'Name',
            'email' => 'Email',
            'password' => 'Password',
            'moderator' => 'Moderator',
            'avatar' => 'Avatar',
            'date_add' => 'Date Add',
        ];
    }

    /**
     * Gets query for [[Comments]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getComments()
    {
        return $this->hasMany(Comment::class, ['user_id' => 'id']);
    }

    /**
     * Gets query for [[Tickets]].
     *
     * @return \yii\db\ActiveQuery
     */
    public function getTickets()
    {
        return $this->hasMany(Ticket::class, ['user_id' => 'id']);
    }

    /**
     * @throws \yii\base\Exception
     */
    public function loadAuthUser($userVk): void
    {
        $this->email = $userVk['email'];
        $this->name = $userVk['first_name'] . ' ' . $userVk['last_name'];
        $this->avatar = $userVk['photo'];
        $this->password = Yii::$app->security->generateRandomString(6);
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
}
