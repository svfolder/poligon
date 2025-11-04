<?php


namespace core\traits;


use Yii;
use yii\web\Session;
use function nspl\a\last;

trait RememberTrait
{

    /**
     * @todo Доработать сохранение пагинации
     * @param $params
     */
    public function remember(&$params)
    {
        $class = last(explode('\\', (get_class($this))));

        /** @var Session $session */
        $session = Yii::$app->session;

        if (!isset($params[$class])) {
            if (isset($session[$class])){
                $params[$class] = $session[$class];
            }
        }else{
            $session[$class] = $params[$class];
        }
    }

}