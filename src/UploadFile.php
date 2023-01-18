<?php

namespace delta;

use yii\web\ServerErrorHttpException;

class UploadFile
{
    /**
     * Метод загрузки файла в заданную директорию
     *
     * @param $fileForSave - загружаемый файл
     * @param string $direction - директория
     * @return string
     * @throws ServerErrorHttpException
     */
    public static function upload($fileForSave, string $direction): string
    {
        $name = uniqid() . '.' . $fileForSave->getExtension();
        if ($fileForSave->saveAs("@webroot/uploads/{$direction}/" . $name)) {
            return $name;
        }
        throw new ServerErrorHttpException("Не удалось сохранить файл. Попробуйте еще раз");
    }
}