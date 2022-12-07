<?php

namespace app\models\forms;

use app\models\User;
use Yii;
use yii\base\Exception;
use yii\base\Model;
use yii\web\UploadedFile;

class RegisterForm extends Model
{
    public ?string $name = null;
    public ?string $email = null;
    public ?string $password = null;
    public ?string $repeatPassword = null;

    /** @var UploadedFile  */
    public $avatar;

    public function attributeLabels(): array
    {
        return [
            'name' => 'Имя и Фамилия',
            'email' => 'Эл.почта',
            'password' => 'Пароль',
            'repeatPassword' => 'Пароль еще раз',
            'avatar' => '',
        ];
    }

    public function rules(): array
    {
        return [
            [['name', 'email', 'password', 'repeatPassword'], 'required'],
            [['name'], 'string'],
            ['email', 'email'],
            ['email', 'unique', 'targetClass' => User::class, 'message' => 'Пользователь с данным Email уже существует'],
            ['password', 'string', 'min' => 6, 'max' => 64],
            ['repeatPassword', 'compare', 'compareAttribute' => 'password'],
            [['avatar'], 'file', 'extensions' => 'png, jpg'],
        ];
    }
}