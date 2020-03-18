<?php

namespace app\models;

use Yii;
use yii\base\Model;
use yii\data\ActiveDataProvider;
use app\models\HeaderPembelian;

/**
 * HeaderPembelianSearch represents the model behind the search form of `app\models\HeaderPembelian`.
 */
class HeaderPembelianSearch extends HeaderPembelian
{
    public $start_date;
    public $end_date;

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['id_pembelian', 'status_delete'], 'integer'],
            [['tgl_pembelian', 'keterangan', 'tgl_delete','start_date','end_date'], 'safe'],
            [['total_pembelian'], 'number'],
        ];
    }

     /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id_pembelian' => Yii::t('app', 'Id Pembelian'),
            'tgl_pembelian' => Yii::t('app', 'Tgl Pembelian'),
            'keterangan' => Yii::t('app', 'Keterangan'),
            'total_pembelian' => Yii::t('app', 'Total Pembelian'),
            'status_delete' => Yii::t('app', 'Status Delete'),
            'tgl_delete' => Yii::t('app', 'Tgl Delete'),
            'id_supplier' => Yii::t('app', 'Supplier'),
            'start_date' => Yii::t('app', 'Tgl Mulai Pembelian'),
            'end_date' => Yii::t('app', 'Tgl Akhir Pembelian'),
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
            //'DATE(tgl_pembelian)' => date('Y-m-d', strtotime($this->tgl_pembelian)),
            'total_pembelian' => $this->total_pembelian,
            'status_delete' => 0,
            'tgl_delete' => $this->tgl_delete,
        ]);

        $query->andFilterWhere(['like', 'keterangan', $this->keterangan]);
        $query->andFilterWhere(['between', 'tgl_pembelian', date('Y-m-d', strtotime($this->start_date)).' 00:00:00', date('Y-m-d', strtotime($this->end_date)).' 00:00:00']);
        $query->orderBy(['id_pembelian'=>SORT_DESC]);

        return $dataProvider;
    }
}
