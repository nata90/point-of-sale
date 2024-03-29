<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\AppUser;

/**
 * AppUserSearch represents the model behind the search form of `app\models\AppUser`.
 */
class AppUserSearch extends AppUser
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_group', 'aktif'], 'integer'],
            [['username', 'password', 'authkey', 'accesstoken', 'name', 'created_at', 'updated_at'], 'safe'],
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
        $query = AppUser::find();

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
        $query->andFilterWhere([
            'id' => $this->id,
            'id_group' => $this->id_group,
            'aktif' => $this->aktif,
            //'created_at' => $this->created_at,
            //'updated_at' => $this->updated_at,
        ]);

        $query->andFilterWhere(['like', 'username', $this->username])
            //->andFilterWhere(['like', 'password', $this->password])
            //->andFilterWhere(['like', 'authkey', $this->authkey])
            //->andFilterWhere(['like', 'accesstoken', $this->accesstoken])
            ->andFilterWhere(['like', 'name', $this->name]);

        return $dataProvider;
    }
}
