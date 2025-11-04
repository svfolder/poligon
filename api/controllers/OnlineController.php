<?php


namespace api\controllers;

use common\components\Dumper;
use core\entities\User\User;
use yii\rest\Controller;


class OnlineController extends Controller
{

    public function behaviors()
    {
        return [
            'corsFilter' => [
                'class' => \yii\filters\Cors::className(),
                'cors' => [
                    'Origin' => ['http://sv5kit.open', 'https://sv5kit.open'],
                    'Access-Control-Request-Method' => ['POST', 'PUT'],
                ]
            ],
        ];
    }

    public function actionIndex()
    {

        $user_id = \Yii::$app->request->post('user_id');
        $user = User::find()->andWhere(['id' => $user_id, 'is_banned' => 0])->one();

        if ($user){

            $user->activity_at = time();
            $user->save();

            return 'Ok!';
        }else{
            return false;
        }

    }

}