<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DetailPembelian;

/**
 * DetailPembelianSearch represents the model behind the search form of `app\models\DetailPembelian`.
 */
class DetailPembelianSearch extends DetailPembelian
{
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'id_pembelian', 'harga_beli', 'harga_jual', 'status_delete'], 'integer'],
            [['kd_barang', 'satuan', 'tgl_delete'], 'safe'],
            [['jumlah'], 'number'],
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
        $query = DetailPembelian::find();

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
            'id_pembelian' => $this->id_pembelian,
            'jumlah' => $this->jumlah,
            'harga_beli' => $this->harga_beli,
            'harga_jual' => $this->harga_jual,
            'status_delete' => $this->status_delete,
            'tgl_delete' => $this->tgl_delete,
        ]);

        $query->andFilterWhere(['like', 'kd_barang', $this->kd_barang])
            ->andFilterWhere(['like', 'satuan', $this->satuan]);

        return $dataProvider;
    }
}
