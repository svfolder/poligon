<?php

use yii\web\View;

/* @var $this View */
/* @var $content string */

?>
<?php switch (Yii::$app->params['layout']):
    case 'main': ?>
<?= $this->render('/layouts/partials/_main', ['content' => $content]); ?>
    <?php break; ?>

    <?php case 'landing': ?>
<?= $this->render('/layouts/partials/_landing'); ?>
    <?php break; ?>

    <?php default: ?>
<?= $this->render('/layouts/_clean', ['content' => $content]); ?>
    <?php break; ?>

<?php endswitch; ?>
