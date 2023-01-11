<?php

namespace delta;

use yii\web\ServerErrorHttpException;

class UploadFile
{

    /**
     * @throws ServerErrorHttpException
     */
    public static function upload($fileForSave, $direction): string
    {
        $name = uniqid() . '.' . $fileForSave->getExtension();
        if ($fileForSave->saveAs("@webroot/uploads/{$direction}/" . $name)) {
            return $name;
        }
        throw new ServerErrorHttpException("Не удалось сохранить файл. Попробуйте еще раз");
    }
}