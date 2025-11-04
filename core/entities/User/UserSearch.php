<?php

namespace core\entities\User;

use yii\base\Model;
use yii\data\ActiveDataProvider;

class UserSearch extends User
{
    use UserSearchTrait;

    public function rules(): array
    {
        return [
            [['id', 'status', 'is_banned'], 'integer'],
            [['username', 'first_name', 'last_name', 'email'], 'safe'],
            [['role'], 'safe'],
            [['date_from', 'date_to'], 'date', 'format' => 'php:Y-m-d'],
        ];
    }

    public function search(array $params): ActiveDataProvider
    {
        $query = User::find();
        $query->alias('u');

        $dataProvider = new ActiveDataProvider(['query' => $query]);

        $this->load($params);
        if (!$this->validate()) {return $dataProvider;}

        $query->andFilterWhere(['u.id' => $this->id, 'u.status' => $this->status, 'u.is_banned' => $this->is_banned]);

        if (!empty($this->role)) {
            $query->innerJoin('{{%auth_assignment}} a', 'a.user_id = u.id');
            $query->andWhere(['a.item_name' => $this->role]);
        }

        $query
            ->andFilterWhere(['like', 'u.username', $this->username])
            ->andFilterWhere(['like', 'u.email', $this->email])
            ->andFilterWhere(['>=', 'u.created_at', $this->date_from ? strtotime($this->date_from . ' 00:00:00') : null])
            ->andFilterWhere(['<=', 'u.created_at', $this->date_to ? strtotime($this->date_to . ' 23:59:59') : null]);

        return $dataProvider;
    }
}
