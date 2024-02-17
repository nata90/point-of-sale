<?php
    use app\components\Utility;
    use yii\helpers\Url;
?>
<table class="table table-striped">
    <thead>
        <tr class="table-title">
            <th>NO</th>
            <th>NAMA BARANG</th>
            <th>HARGA</th>
            <th>QTY</th>
            <th>SUBTOTAL</th>
            <th></th>
        </tr>
    </thead>
    <tbody>
        <?php 
            if(isset($datatransaksi) && !empty($datatransaksi)){
                $no = 1;
                foreach($datatransaksi as $key=>$value){
                    $subtotal = $subtotal + $value['total'];
                    $total = $subtotal - $diskon; ?>

                    <tr>
                        <td><b><?php echo $no ?></b></td>
                        <td><b><?php echo strtoupper($value['namabarang']);?></b></td>
                        <td><b><?php echo Utility::rupiah($value['harga']);?>&nbsp;&nbsp;<a class="modalBtn" href="#" url="<?php echo Url::to(['filebarang/updateharga', 'id'=>$key]);?>" headername="<?php echo strtoupper($value['namabarang']);?>"><i class="fa fa-fw fa-edit"></i>EDIT</a></b></td>
                        <td><b><?php echo $value['qty'];?></b></td>
                        <td><b><?php echo Utility::rupiah($value['total']);?></b></td>
                        <td style="text-align:center;"><button rel="<?php echo $key;?>" url="<?php echo Url::to(['site/deleteitem']);?>" class="btn bg-olive margin delete-item" title="Delete" style="margin:0 0 0 0px;">HAPUS</button></td>
                    </tr>
            <?php 
                    $no++;
                }
            }
        ?>
    </tbody>
</table>