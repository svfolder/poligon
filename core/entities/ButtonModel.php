<?php

namespace core\entities;


use yii\base\Model;

class ButtonModel extends Model
{
    public $data;

    public function formName(): string
    {
        return '';
    }

    public function rules(): array
    {
        return [
            [['data', ], 'string'],
            [['data'], 'safe'],
        ];
    }

}