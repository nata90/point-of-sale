<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\HeaderPembelian;

/**
 * HeaderPembelianSearch represents the model behind the search form of `app\models\HeaderPembelian`.
 */
class HeaderPembelianSearch extends HeaderPembelian
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pembelian', 'status_delete'], 'integer'],
            [['tgl_pembelian', 'keterangan', 'tgl_delete'], 'safe'],
            [['total_pembelian'], 'number'],
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
        $query = HeaderPembelian::find();

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
            'id_pembelian' => $this->id_pembelian,
            'DATE(tgl_pembelian)' => date('Y-m-d', strtotime($this->tgl_pembelian)),
            'total_pembelian' => $this->total_pembelian,
            'status_delete' => 0,
            'tgl_delete' => $this->tgl_delete,
        ]);

        $query->andFilterWhere(['like', 'keterangan', $this->keterangan]);

        return $dataProvider;
    }
}
