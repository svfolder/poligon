<?php

namespace core\entities\Language;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class LanguageSearch extends Language
{
    public function rules(): array
    {
        return [
            [['id', 'status'], 'integer'],
            [['name', 'code'], 'safe'],
        ];
    }

    public function search(array $params): ActiveDataProvider
    {
        $query = Language::find();

        $dataProvider = new ActiveDataProvider(['query' => $query]);

        $this->load($params);
        if (!$this->validate()) {return $dataProvider;}

        $query->andFilterWhere([
                    'id' => $this->id,
                    'status' => $this->status,
                ]);

        $query->andFilterWhere(['like', 'name', $this->name])
                    ->andFilterWhere(['like', 'code', $this->code]);

        return $dataProvider;
    }
}
