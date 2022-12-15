<?php

namespace app\controllers;

use app\models\forms\LoginForm;
use yii\web\Response;
use yii\widgets\ActiveForm;

class OffersController extends \yii\web\Controller
{
    public function actionIndex()
    {
        return $this->render('view');
    }
}