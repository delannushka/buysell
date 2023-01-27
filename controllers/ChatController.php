<?php
namespace app\controllers;

use app\models\forms\ChatForm;
use app\models\Ticket;
use delta\FirebaseHandler;
use Kreait\Firebase\Exception\DatabaseException;
use Kreait\Firebase\Factory;
use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\ServerErrorHttpException;

class ChatController extends Controller
{
    public function behaviors(): array
    {
        return [
            'access' => [
                'class' => AccessControl::class,
                'rules' => [
                    [
                        'allow' => true,
                        'actions' => ['index'],
                        'roles' => ['@'],
                    ]
                ],
            ],
        ];
    }

    /**
     * @throws DatabaseException
     * @throws NotFoundHttpException
     */
    public function actionIndex($id)
    {
        $ticket = Ticket::findOne($id);
        if (!$ticket || $ticket->status === 0) {
            throw new NotFoundHttpException('Объвление не найдено');
        }
        $chatForm = new ChatForm();
        $messages = [];
        $database = new FirebaseHandler($id, Yii::$app->user->id);

        $dataMessages = $database->getValue();
        if ($dataMessages !== null) {
            $messages = $database->extractData($dataMessages);
        }
        print_r($messages);

        if (Yii::$app->request->getIsPost()) {
            $chatForm->load(Yii::$app->request->post());
            if ($chatForm->validate()) {
                $chatForm->addMessage($database);
                return Yii::$app->response->redirect('/chat/' . $id);
            }
        }
        return $this->render('chat', [
            'model' => $chatForm,
            'messages' => $messages,
            'sellerId' => Yii::$app->user->id,
        ]);
    }
}


        /*
        $ticket = Ticket::findOne($id);
        if (!$ticket || $ticket->status === 0) {
            throw new NotFoundHttpException('Объвление не найдено');
        }
        $sellerId = $ticket->user_id;
        $buyerId = Yii::$app->user->id;
        if ($sellerId === $buyerId) {
            throw new ForbiddenHttpException('Вам нельзя выполнять данное дейтсвие');
        }

        $factory = (new Factory)
            ->withServiceAccount(__DIR__ . '/buysell-ca35f-firebase-adminsdk-v8bwf-608e868ed7.json')
            ->withDatabaseUri('https://buysell-ca35f-default-rtdb.firebaseio.com');

        $realtimeDatabase = $factory->createDatabase();

        $realtimeDatabase->getReference($sellerId . '/' . $buyerId)
            ->set([
                [
                    'user_id' => $buyerId,
                    'dt_add' => date('c'),
                    'message' => 'Один один один два',
                ],
                [
                    'user_id' => $sellerId,
                    'dt_add' => date('c'),
                    'message' => 'Два',
                ]
            ]);

        $snapshot = $realtimeDatabase->getReference('7/9')->getSnapshot()->numChildren();
        print_r($snapshot);

        return $this->render('chat');
    }
        */