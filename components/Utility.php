<?php

namespace app\components;

use Yii;
use yii\base\Model;

/**
 * ContactForm is the model behind the contact form.
 */
class Utility extends Model
{
    public static function rupiah($nominal) {
        $rupiah =  number_format($nominal,0, ",",".");
        $rupiah = "Rp "  . $rupiah . ",00";
        return $rupiah;
    }

    public static function getNoTransaksi($id_type){
    	if($id_type == 1){ //no transaksi penjualan
    		$urutan = UrutanTransaksi::find()->where(['id'=>1])->one();
    	}
    }
}
