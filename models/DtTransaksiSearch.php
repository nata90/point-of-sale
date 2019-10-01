<?php

namespace app\models;

use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\DtTransaksi;

/**
 * DtTransaksiSearch represents the model behind the search form of `app\models\DtTransaksi`.
 */
class DtTransaksiSearch extends DtTransaksi
{
    public $nama_barang;
    public $start_date;
    public $end_date;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id', 'harga_satuan', 'qty', 'total_harga', 'status_hapus'], 'integer'],
            [['no_transaksi', 'kd_barang', 'tgl_hapus','nama_barang','start_date','end_date'], 'safe'],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'start_date' => 'Tanggal Mulai',
            'end_date' => 'Sampai',
            
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
        $query = DtTransaksi::find()->leftJoin('hd_transaksi','dt_transaksi.no_transaksi = hd_transaksi.no_transaksi');

        // add conditions that should always apply here

        $dataProvider = new ActiveDataProvider([
            'query' => $query,
            'sort' =>false,
            'pagination' => [
                'pageSize' => 100,
            ]
        ]);

        $this->load($params);

        if (!$this->validate()) {
            // uncomment the following line if you do not want to return any records when validation fails
            // $query->where('0=1');
            return $dataProvider;
        }

        // grid filtering conditions
        //$query->select(['kd_barang','qty','SUM(total_harga) AS total_harga']);
        $query->andFilterWhere([
            'id' => $this->id,
            'harga_satuan' => $this->harga_satuan,
            'qty' => $this->qty,
            'total_harga' => $this->total_harga,
            'status_hapus' => $this->status_hapus,
            'tgl_hapus' => $this->tgl_hapus,
        ]);

        $query->andFilterWhere(['like', 'no_transaksi', $this->no_transaksi])->andFilterWhere(['like', 'kd_barang', $this->kd_barang]);

        //$query->andWhere(['hd_transaksi.tgl_bayar']);
        $query->andFilterWhere(['between', 'hd_transaksi.tgl_bayar', date('Y-m-d', strtotime($this->start_date))." 00:00:00", date('Y-m-d', strtotime($this->end_date))." 23:59:59"]);
        //$query->groupBy(['kd_barang']);

        return $dataProvider;
    }
}
