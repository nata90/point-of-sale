<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\HdTransaksi;

/**
 * HdTransaksiSearch represents the model behind the search form of `app\models\HdTransaksi`.
 */
class HdTransaksiSearch extends HdTransaksi
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'status_bayar', 'total', 'jumlah_bayar', 'status_hapus'], 'integer'],
            [['no_transaksi', 'tgl_bayar', 'tgl_hapus'], 'safe'],
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
        $query = HdTransaksi::find();

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
            'DATE(tgl_bayar)' => $this->tgl_bayar,
            'status_bayar' => $this->status_bayar,
            'total' => $this->total,
            'jumlah_bayar' => $this->jumlah_bayar,
            'status_hapus' => $this->status_hapus,
            'tgl_hapus' => $this->tgl_hapus,
        ]);

        $query->andFilterWhere(['like', 'no_transaksi', $this->no_transaksi]);

        return $dataProvider;
    }
}
