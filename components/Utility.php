<?php

namespace app\components;

use Yii;
use yii\base\Model;
use app\models\UrutanTransaksi;
use app\models\KodeGenerate;

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

                $new_urutan = $urutan->urutan + 1;

                $return = date('Y').date('m').date('d').$new_urutan;   			

	    		$urutan->urutan = $new_urutan;
	    		$urutan->save(false);
    		}
    		
    	}

    	return $return;
    }

    public static function generateKodeBarang(){
        $model = KodeGenerate::find()->where(['nama_alias'=>'BRG'])->one();

        if($model){
            $char = $model->nama_alias;

            $kode_barang = $char . sprintf("%06s", $model->urutan);
        }else{
            $kode_barang = '-';
        }

        return $kode_barang;
    }

    function getUserIP(){
        $client  = @$_SERVER['HTTP_CLIENT_IP'];
        $forward = @$_SERVER['HTTP_X_FORWARDED_FOR'];
        $remote  = $_SERVER['REMOTE_ADDR'];

        if(filter_var($client, FILTER_VALIDATE_IP))
        {
            $ip = $client;
        }
        elseif(filter_var($forward, FILTER_VALIDATE_IP))
        {
            $ip = $forward;
        }
        else
        {
            $ip = $remote;
        }

        if($ip == '::1'){
            $ip = '127.0.0.1';
        }

        return $ip;
    }

    public static function getTotalTransaksiPenjualan($arrDataTransaksi,$diskon){
        $total = 0;
        $subtotal = 0;
        if($arrDataTransaksi){
            foreach($arrDataTransaksi as $key=>$value){
                $subtotal = $subtotal + $value['total'];
                $total = $subtotal - $diskon;
                
            }
        }

        return ['total'=>$total,'subtotal'=>$subtotal];
    }
}
