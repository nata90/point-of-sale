<?php

namespace app\components;

use Yii;
use yii\base\Model;
use app\models\UrutanTransaksi;

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
    		$urutan = UrutanTransaksi::find()->where(['DATE(tgl_transaksi)'=>date('Y-m-d')])->one();

    		if($urutan == null){
    			$new_urut = new UrutanTransaksi;
    			$new_urut->nama_transaksi = 'transaksi penjualan';
    			$new_urut->urutan = 1;
    			$new_urut->tgl_transaksi = date('Y-m-d H:i:s');
    			if($new_urut->save()){
    				$return = date('Ymd').$new_urut->urutan;
    			} 
    		}else{
    			$return = date('Y').date('m').date('d').$urutan->urutan;

	    		$urutan->urutan = $urutan->urutan + 1;
	    		$urutan->save(false);
    		}
    		
    	}

    	return $return;
    }
}
