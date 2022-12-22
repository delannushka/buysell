<?php

namespace delta;

class UploadFile
{
    /**
     * @throws \Exception
     */
    public static function upload($fileForSave, $direction): string
    {
        $name = uniqid() . '.' . $fileForSave->getExtension();
        if ($fileForSave->saveAs("@webroot/uploads/{$direction}/" . $name)) {
            return $name;
        }
        throw new \Exception("Не удалось сохранить файл");
    }
}