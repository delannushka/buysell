<?php
namespace app\models\rbac;

use yii\rbac\Rule;

class AuthorRule extends Rule
{
    /**
     * Проверяем authorID на соответствие с пользователем, переданным через параметры
     */

    public $name = 'isAuthor';

        /**
         * @param string|int $user the user ID.
         * @param Item $item the role or permission that this rule is associated width.
         * @param array $params parameters passed to ManagerInterface::checkAccess().
         * @return bool a value indicating whether the rule permits the role or permission it is associated with.
         */
        public function execute($user, $item, $params)
    {
        return isset($params['autor_id']) ? $params['autor_id']->createdBy == $user : false;
    }
}