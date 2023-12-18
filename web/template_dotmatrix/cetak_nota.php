<?php

use app\components\Utility;

	$tmpdir = sys_get_temp_dir();   # ambil direktori temporary untuk simpan file.
        $file =  tempnam($tmpdir, 'ctk');  # nama file temporary yang akan dicetak
        $handle = fopen($file, 'w');
        $condensed = Chr(27) . Chr(33) . Chr(48); //font besar
        $condensed4 = Chr(27) . Chr(33) . Chr(4);//font kecil
        $bold1 = Chr(27) . Chr(69).(1);
        $bold0 = Chr(27) . Chr(69).(0);
        // $bold0 = Chr(27) . Chr(70);
        $initialized = chr(27).chr(64);
        $condensed1 = chr(15);
        $condensed0 = chr(18);
        
        $alignCenter1 = Chr(27) . Chr(97). Chr(1);
        $alignCenter0 = Chr(27) . Chr(97). Chr(0);
        
        $jarakFont = Chr(27) . Chr(32). Chr(1); 
        $jarakFont0 = Chr(27) . Chr(32). Chr(0); 
        
        $marginRight = Chr(27) . Chr(81). Chr(120); 
        $marginRight0 = Chr(27) . Chr(81). Chr(0); 
        $tab =  Chr(27) . Chr(102). Chr(0). (33);  
        $right =  Chr(27) . Chr(97). Chr(2) ;  
        $right0 =  Chr(27) . Chr(97). Chr(0) ;  
   
        $coba =  Chr(27) . Chr(36)  . Chr(100). Chr(0); 
        $coba2 =  Chr(27) . Chr(36)  . Chr(230). Chr(0); 
        $coba3 =  Chr(27) . Chr(36)  . Chr(40). Chr(1); 
        $coba4 =  Chr(27) . Chr(36)  . Chr(60). Chr(1);  
        $a =  Chr(27) . Chr(87)  . Chr(1) ;  
        $a0 =  Chr(27) . Chr(87)  . Chr(0) ;  
        
        $width = Chr(29) . Chr(87). Chr(144). Chr(1);
        $tab = Chr(9);      
        // $coba0 =  Chr(27) . Chr(115). Chr(0);   
            
        $a1 = Chr(27) . Chr(71). Chr(1); 
        $a0 = Chr(27) . Chr(71). Chr(0); 
        
        $Data  = $initialized;
        $Data .= $condensed."\n";

        
        
        $Data .= $alignCenter1;
        $Data .= $jarakFont;

        $Data .= $condensed4."\n";
        $Data .= "------------------------------\n";
        $Data .= "TOKO TAMAN HATI\n";
        $Data .= "KAUMAN PEDAN KLATEN\n";
        $Data .= "TELP : (0272) 898246\n";
        $Data .= $condensed4."\n";
        $Data .= "------------------------------\n";
        $Data .= "Faktur Penjualan\n";
        $Data .= "No Faktur     : ".$no_transaksi."\n";
        $Data .= "Tanggal       : ".date('d/m/Y')."\n";
        $Data .= "------------------------------\n";

        $Data .= $jarakFont0;
        
        $Data .= $alignCenter0;
        $Data .= $jarakFont;

        $all_total = 0;
        if($detail != null){
                foreach($detail as $val){
                        $total = $val->qty * $val->harga_satuan;
                        $all_total = $all_total + $total;
                        $Data .= $val->barang->nama_barang." (".$val->qty.")\n";
                        $Data .= "                ".Utility::rupiah($total)."\n";
                }
                
        }else{
                $Data .= "Data Tidak ada\n";  
        }
        
        
        $Data .= "------------------------------\n";
        $Data .= "Total           ".Utility::rupiah($all_total)."\n";
        $Data .= "Disc             0\n";
        $Data .= "------------------------------\n";
        $Data .= "BARANG YANG SUDAH DIBELI\n";
        $Data .= "TIDAK DAPAT DITUKAR\n";
        $Data .= "TERIMA KASIH\n";
        $Data .= "ATAS KUNJUNGAN ANDA\n";
        $Data .= $condensed4."\n";
        $Data .= "\n";
        $Data .= "\n";
        $Data .= "\n";
        $Data .= "\n";
        $Data .= "\n";
        $Data .= "\n";
        $Data .= "\n";
        $Data .= "\n";


       
        $Data .= "\n";
        $Data .= chr(27).chr(105);
 
 