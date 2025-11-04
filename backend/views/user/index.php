<?php
use yii\helpers\Html;
use common\components\GridView;

/* @var $this yii\web\View */
/* @var $searchModel core\entities\User\UserSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */
$this->title = Yii::t('app', 'Users');
$this->params['breadcrumbs'][] = $this->title;
?>
<div class="user-index">

    <span class="button-panel">
        <?= Html::a('<i class="fa fa-plus-square"></i> ' . Yii::t('app', 'Create User'), ['create-minimal'], ['class' => 'btn btn-success']) ?>
        <?= Html::a('<i class="fa fa-plus-square"></i> ' . Yii::t('app', 'Create User'), ['create'], ['class' => 'btn btn-warning pull-right']) ?>
    </span>

    <div class="box">
        <div class="box-body">

            <?php // echo $this->render('_search', ['model' => $searchModel]); ?>

            <?= GridView::widget([
                'dataProvider' => $dataProvider,
                'filterModel' => $searchModel,
                'columns' => [
                    ['class' => 'common\components\SIDColumn'],
                    ['class' => 'common\components\UserCreatedFilterColumn', 'searchModel' => $searchModel],
                    ['class' => 'common\components\UserUsernameColumn'],
                    'first_name',
                    'last_name',
                    [
                        'attribute' => 'image',
                        'value' => function ($model) {
                        	return $model->image ? Html::img($model->getThumbFileUrl('image', 'default')) : null;
                        },
                        'format' => 'raw',
                        'contentOptions' => ['style' => 'width:180px'],
                    ],
                    'email:email',
                    ['class' => 'common\components\BannedColumn'],
                    'created_at:datetime',
                    ['class' => 'common\components\UserStatusColumn'],
                    ['class' => 'backend\widgets\grid\RoleColumn', 'filter' => $searchModel->rolesList()],
                    ['class' => 'common\components\UserActionColumn'],
                ],
            ]); ?>
        </div>
    </div>
</div>