<?php

use yii\helpers\FileHelper;

/**
 * @param string $key
 * @param mixed $default
 * @return mixed
 */
function env(string $key, $default = null)
{
    // getenv is disabled when using createImmutable with Dotenv class
    if (isset($_ENV[$key])) {
        return $_ENV[$key];
    } elseif (isset($_SERVER[$key])) {
        return $_SERVER[$key];
    }

    return $default;
}

function getDomain()
{
    return \nspl\a\last(explode( '/', rtrim(rtrim(FileHelper::normalizePath(Yii::$app->basePath, '/'),'console'), '/')));
}