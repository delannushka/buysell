<?php

namespace app\models\forms;

use app\models\User;
use Yii;
use yii\base\Model;

class LoginForm extends Model
{
    public ?string $email = null;
    public ?string $password = null;

    private $_user;

    public function attributeLabels(): array
    {
        return [
            'email' => 'Эл.почта',
            'password' => 'Пароль',
        ];
    }

    public function rules(): array
    {
        return [
            [['email', 'password'], 'required'],
            ['password', 'validatePassword'],
        ];
    }

    /**
     * Метод получения данных пользователя по email
     *
     * @return User|null $user - объект класса User
     */
    public function getUser(): ?User
    {
        if ($this->_user === null) {
            $this->_user = User::findOne(['email' => $this->email]);
        }

        return $this->_user;
    }

    /**
     * Метод валидации пароля при входе пользователя
     *
     * @param  string  $attribute  - строка из поля 'password' формы входа
     */
    public function validatePassword(string $attribute)
    {
        if ( ! $this->hasErrors()) {
            $user = $this->getUser();

            if ( ! $user
                || ! Yii::$app->security->validatePassword($this->password,
                    $user->password)
            ) {
                $this->addError($attribute, 'Неправильный email или пароль');
            }
        }
    }
}