<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\FileBarang;

/**
 * FileBarangSearch represents the model behind the search form of `app\models\FileBarang`.
 */
class FileBarangSearch extends FileBarang
{
    public $stok_akhir;
    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'harga_beli', 'harga_jual', 'aktif'], 'integer'],
            [['kd_barang', 'nama_barang','lokasi','stok_akhir'], 'safe'],
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
        $query = FileBarang::find();

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
            'harga_beli' => $this->harga_beli,
            'harga_jual' => $this->harga_jual,
            'aktif' => $this->aktif,
        ]);

        $query->andFilterWhere(['like', 'kd_barang', $this->kd_barang])
            ->andFilterWhere(['like', 'nama_barang', $this->nama_barang])
            ->andFilterWhere(['like', 'lokasi', $this->lokasi]);

        return $dataProvider;
    }

    /**
     * Creates data provider instance with search query applied
     *
     * @param array $params
     *
     * @return ActiveDataProvider
     */
    public function searchStokKosong($params)
    {
        $query = FileBarang::find();

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
            'harga_beli' => $this->harga_beli,
            'harga_jual' => $this->harga_jual,
            'aktif' => $this->aktif,
        ]);

        $query->andFilterWhere(['like', 'kd_barang', $this->kd_barang])
            ->andFilterWhere(['like', 'nama_barang', $this->nama_barang])
            ->andFilterWhere(['<=', 'stok', 0])
            ->andFilterWhere(['like', 'lokasi', $this->lokasi]);

        return $dataProvider;
    }

    public function searchBarangED($params){
        $query = FileStokBarang::find()->leftJoin('file_barang','file_stok_barang.kd_barang = file_barang.kd_barang');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id
        ]);

        $query->andFilterWhere(['like', 'kd_barang', $this->kd_barang])
        ->andFilterWhere(['like', 'file_barang.nama_barang', $this->nama_barang])
        ->andFilterWhere(['<', 'tgl_ed', date('Y-m-d')])
        ->andFilterWhere(['>', 'stok_akhir', 0]);

        return $dataProvider;
    }

    public function searchBeforeED($params){
        $query = FileStokBarang::find()->leftJoin('file_barang','file_stok_barang.kd_barang = file_barang.kd_barang');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>false
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        $query->andFilterWhere([
            'id' => $this->id
        ]);

        $now = date('Y-m-d');
        $query->andFilterWhere(['like', 'kd_barang', $this->kd_barang])
        ->andFilterWhere(['like', 'file_barang.nama_barang', $this->nama_barang])
        ->andFilterWhere(['<=', 'tgl_ed', date('Y-m-d', strtotime('+1 month', strtotime($now)))])
        ->andFilterWhere(['>', 'tgl_ed', $now])
        ->andFilterWhere(['>', 'stok_akhir', 0]);

        return $dataProvider;
    }
}
