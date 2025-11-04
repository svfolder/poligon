<?php

namespace core\entities\Menu;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class MenuItemSearch extends MenuItem
{
    public function rules(): array
    {
        return [
            [['id', 'parent_id', 'menu_id', 'sort', 'status'], 'integer'],
            [['name', 'link'], 'safe'],
        ];
    }

    public function search(array $params): ActiveDataProvider
    {
        $query = MenuItem::find();

        $dataProvider = new ActiveDataProvider(['query' => $query, 'sort' => ['defaultOrder' => ['sort' => SORT_ASC]]]);

        $this->load($params);
        if (!$this->validate()) {return $dataProvider;}

        $query->andFilterWhere([
                    'id' => $this->id,
                    'parent_id' => $this->parent_id,
                    'menu_id' => $this->menu_id,
                    'sort' => $this->sort,
                    'status' => $this->status,
                ]);

        $query->andFilterWhere(['like', 'name', $this->name])
                    ->andFilterWhere(['like', 'link', $this->link]);

        return $dataProvider;
    }

    public function menuItemList(): array
    {
        return \yii\helpers\ArrayHelper::map(\core\entities\Menu\MenuItem::find()->all(), 'id', 'name');
    }

    public function menuList(): array
    {
        return \yii\helpers\ArrayHelper::map(\core\entities\Menu\Menu::find()->all(), 'id', 'name');
    }
}
