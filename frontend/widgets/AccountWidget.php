<?php

namespace frontend\widgets;

use yii\base\Widget;

class AccountWidget extends Widget
{

    public function __construct($config = [])
    {
        parent::__construct($config);
    }

    public function run(): string
    {
        return $this->render('account', []);
    }

}