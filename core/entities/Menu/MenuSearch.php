<?php

namespace core\entities\Menu;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class MenuSearch extends Menu
{
    public function rules(): array
    {
        return [
            [['id', 'status'], 'integer'],
            [['name', 'alias'], 'safe'],
        ];
    }

    public function search(array $params): ActiveDataProvider
    {
        $query = Menu::find();

        $dataProvider = new ActiveDataProvider(['query' => $query]);

        $this->load($params);
        if (!$this->validate()) {return $dataProvider;}

        $query->andFilterWhere([
                    'id' => $this->id,
                    'status' => $this->status,
                ]);

        $query->andFilterWhere(['like', 'name', $this->name])
                    ->andFilterWhere(['like', 'alias', $this->alias]);

        return $dataProvider;
    }
}
