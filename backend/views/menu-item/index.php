<?php
use yii\helpers\Html;
use himiklab\sortablegrid\SortableGridView as GridView;

/* @var $this yii\web\View */
/* @var $searchModel core\entities\Menu\MenuItemSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = Yii::t('app', 'Menu Items');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="menu-item-index">

    <span class="button-panel">
        <?= Html::a('<i class="fa fa-plus-square"></i> ' . Yii::t('app', 'Create Menu Item'), ['create'], ['class' => 'btn btn-success']) ?>
    </span>

    <div class="box">
        <div class="box-body">

            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'yii\grid\SerialColumn'],
                    ['class' => 'yii\grid\CheckboxColumn', 'checkboxOptions' => ['onclick' => '$(this).parent().parent().toggleClass("danger")']],

                    ['class' => 'common\components\IDColumn'],
                    [
                        'attribute' => 'parent_id',
                        'filter' => $searchModel->menuItemList(),
                        'value' => function ($model) {
                        	return $model->getParent()->one()->name;
                        },
                    ],
                    [
                        'attribute' => 'menu_id',
                        'filter' => $searchModel->menuList(),
                        'value' => function ($model) {
                        	return $model->getMenu()->one()->name;
                        },
                    ],
                    'name',
                    'link',
                    'sort',
                    [
                        'attribute' => 'status',
                        'class' => \common\components\StatusColumn::class,
                        'filter' => \common\components\StatusColumn::statusList(),
                        'format' => 'raw',
                    ],
                    [
                        'content' => function(){return "<span class='glyphicon glyphicon-resize-vertical'></span>";},
                        'contentOptions' => ['style'=>'width: 34px; cursor:move;'],
                    ],

                    ['class' => 'yii\grid\ActionColumn'],
                ],
            ]); ?>
        </div>
    </div>
</div>