<?php

namespace core\helpers;

use Yii;
use yii\base\Exception;
use yii\helpers\FileHelper;


class AssetPublisher
{
    /**
     * @throws Exception
     */
    public static function publishImages()
    {
        self::publish('@frontend/assets/dist/images', '@webroot/images');
    }

    /**
     * @throws Exception
     */
    public static function publish(string $source, string $target)
    {
        $source = Yii::getAlias($source);
        $target = Yii::getAlias($target);
        $marker = $target . '/.copied';

        if (file_exists($marker)) {
            return;
        }

        if (!is_dir($source)) {
            return;
        }

        if (!is_dir($target)) {
            FileHelper::createDirectory($target, 0755, true);
        }

        FileHelper::copyDirectory($source, $target);

        file_put_contents($marker, 'auto-published: ' . date('Y-m-d H:i:s'));
    }
}