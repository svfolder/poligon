<?php

namespace core\entities\Auth;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use core\entities\Auth\AuthItemChild;

/**
 * AuthItemChildSearch represents the model behind the search form of `core\entities\Auth\AuthItemChild`.
 */
class AuthItemChildSearch extends AuthItemChild
{
    /**
     * {@inheritdoc}
     */
    public function rules(): array
    {
        return [
            [['parent', 'child'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function scenarios()
    {
        // bypass scenarios() implementation in the parent class
        return Model::scenarios();
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function search($params)
    {
        $query = AuthItemChild::find();

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere(['like', 'parent', $this->parent])
            ->andFilterWhere(['like', 'child', $this->child]);

        return $dataProvider;
    }

    function authItemList(): array
    {
    	return \yii\helpers\ArrayHelper::map(\core\entities\Auth\AuthItem::find()->all(), 'id', 'name');
    }
    
}
