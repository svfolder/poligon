<?php

use backend\widgets\grid\FilterContentActionColumn;
use core\entities\Auth\AuthItem;
use core\helpers\RoleHelper;
use yii\helpers\Html;
use common\components\GridView;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $searchModel core\entities\Auth\AuthItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = Yii::t('app', 'Auth Items');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="auth-item-index">

    <p>
        <?= Html::a(Yii::t('app', 'Create Auth Item'), ['create'], ['class' => 'btn btn-success']) ?>
    </p>

    <div class="box">
        <div class="box-body">

            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    ['class' => 'yii\grid\CheckboxColumn', 'checkboxOptions' => ['onclick' => '$(this).parent().parent().toggleClass("danger")']],

                    'name',
                    [
                        'attribute' => 'type',
                        'filter' => RoleHelper::statusList(),
                        'value' => function (AuthItem $model) {
                            return RoleHelper::statusLabel($model->type);
                        },
                        'format' => 'raw',
                    ],
                    'description:ntext',
                    'rule_name',
                    'data',
                    'created_at:datetime',
                    [
                        'class' => \yii\grid\ActionColumn::class,
                        'urlCreator' => function ($action, $model, $key, $index) { return Url::toRoute([$action, 'name' => $model->name]); }
                    ],
                ],
            ]); ?>

        </div>
    </div>
</div>