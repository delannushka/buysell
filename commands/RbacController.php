<?php

namespace app\commands;

use app\models\rbac\AuthorRule;
use Yii;
use yii\console\Controller;

class RbacController extends \yii\web\Controller
{
    //через консоль выполнить это действие не удалось, ошибка:
    // 'Calling unknown method: yii\console\Request::validateCsrfToken()'

    /**
     * Метод создания ролей модератора и простого пользователя
     *
     */
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll(); //Очищаю все таблицы

        //добавляем роли
        $user = $auth->createRole('user');
        $auth->add($user);

        $moderator = $auth->createRole('moderator');
        $auth->add($moderator);

        // разрешение на редактирование только свего контента
        $editOwnTicket = $auth->createPermission('editOwnTicket');
        $editOwnTicket->description = 'Edit own ticket';

        // разрешение на редактирование всего и вся
        $editAllTickets = $auth->createPermission('editAllTickets');
        $editAllTickets->description = 'Edit all tickets';

        //ограничивующее правило - проверка на авторство объявления
        $rule = new AuthorRule;
        $auth->add($rule);

        //привязываем правило к разрешению editOwnTicket и сохраняем все в БД
        $editOwnTicket->ruleName = $rule->name;
        $auth->add($editOwnTicket);
        $auth->add($editAllTickets);

        // даём роли user разрешение "editOwnTicket"
        $auth->addChild($user, $editOwnTicket);
        // даём роли moderator разрешение "editAllTickets"
        $auth->addChild($moderator, $editAllTickets);
        // а также все разрешения роли "user"
        $auth->addChild($moderator, $user);
    /*
    Для того чтобы проверить, может ли пользователь отредактировать пост, нам надо передать
    дополнительный параметр, необходимый для правила AuthorRule, описанного ранее:
        if (\Yii::$app->user->can('editAllTickets', ['autor_id' => $model->id]))
        {
            // edit ticket
        }
    Мы начинаем с editAllTickets и переходим к editOwnTicket. Для того чтобы это произошло,
    правило AuthorRule должно вернуть true при вызове метода execute.
    Метод получает $params, переданный при вызове метода can, значение которого равно ['autor_id' => $model->id]
    */
        $auth->addChild($editOwnTicket, $editAllTickets);

        /*
        Назначение ролей пользователям. 1 это ID возвращаемое IdentityInterface::getId()
        $auth->assign($moderator, 1);
        */
    }
}