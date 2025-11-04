<?php


namespace core\traits;


use yii\base\Exception;
use yii\helpers\FileHelper;

trait FileTrait
{

    public function cleanDirectory($dir, $options = [])
    {
        foreach (FileHelper::findFiles($dir, $options) as $file){
            unlink($file);
        }
    }

    public function moveFile(string $fromPath, string $toPath): bool
    {
        try {
            if (copy($fromPath, $toPath)) {
                unlink($fromPath);
                return true;
            }
        } catch (Exception $e) {
            return false;
        }
        return false;
    }
}