<?php

namespace app\controllers;

use app\models\Category;
use app\models\rbac\AuthorRule;
use app\models\Ticket;
use Yii;
use yii\data\ActiveDataProvider;
use yii\filters\AccessControl;
use yii\web\Controller;

class SiteController extends Controller
{
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow'   => true,
                        'actions' => ['index', 'error', 'init'],
                        'roles'   => ['?', '@'],
                    ],
                ],
            ],
        ];
    }

    const LIMIT_TICKETS = 8;

    public function actions(): array
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
        ];
    }

    /**
     * Главная страница
     *
     * @return string
     */
    public function actionIndex(): string
    {
        $freshTicketsProvider = new ActiveDataProvider([
            'query'      => Ticket::getFreshTickets(),
            'pagination' => ['pageSize' => self::LIMIT_TICKETS]
            // автоматически будет переписывать лимит на 8
        ]);

        $popularTicketsProvider = new ActiveDataProvider([
            'query'      => Ticket::getPopularTickets(),
            'pagination' => ['pageSize' => self::LIMIT_TICKETS]
            // автоматически будет переписывать лимит на 8
        ]);

        $mainCategoriesProvider = new ActiveDataProvider([
            'query' => Category::getActiveCategoryList(),
        ]);

        return $this->render('index',
            [
                'mainCategoriesProvider' => $mainCategoriesProvider,
                'freshTicketsProvider'   => $freshTicketsProvider,
                'popularTicketsProvider' => $popularTicketsProvider,
            ]
        );
    }

    /**
     * Метод создания ролей модератора и простого пользователя
     *
     */
    public function actionInit()
    {
        $auth = Yii::$app->authManager;
        $auth->removeAll();

        $user = $auth->createRole('user');
        $auth->add($user);

        $moderator = $auth->createRole('moderator');
        $auth->add($moderator);

        $editOwnTicket              = $auth->createPermission('editOwnTicket');
        $editOwnTicket->description = 'Edit own ticket';

        $editAllTickets              = $auth->createPermission('editAllTickets');
        $editAllTickets->description = 'Edit all tickets';

        $rule = new AuthorRule;
        $auth->add($rule);

        $editOwnTicket->ruleName = $rule->name;
        $auth->add($editOwnTicket);
        $auth->add($editAllTickets);

        $auth->addChild($user, $editOwnTicket);
        $auth->addChild($moderator, $editAllTickets);
        $auth->addChild($moderator, $user);

        $auth->addChild($editOwnTicket, $editAllTickets);

        //По умолчанию всем пользователям присваивается роль User
        //Если хотим присвоить роль модератора пользователя с id = 1:
        //$auth->assign($moderator, 1);

        return $this->redirect('/');
    }
}
