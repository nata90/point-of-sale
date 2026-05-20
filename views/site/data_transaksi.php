<?php
    use app\components\Utility;
    use yii\helpers\Url;
?>
<table class="pin-table">
    <thead>
        <tr>
            <th style="width:50px;">No</th>
            <th>Nama Barang</th>
            <th>Harga</th>
            <th style="width:70px;">Qty</th>
            <th>Subtotal</th>
            <th style="width:80px;"></th>
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
                        <td style="color:#62625b;font-weight:600;"><?php echo $no ?></td>
                        <td style="font-weight:600;"><?php echo strtoupper($value['namabarang']);?></td>
                        <td>
                            <?php echo Utility::rupiah($value['harga']);?>
                            <a class="modalBtn" href="#" url="<?php echo Url::to(['filebarang/updateharga', 'id'=>$key]);?>" headername="<?php echo strtoupper($value['namabarang']);?>" style="display:inline-flex;align-items:center;gap:4px;margin-left:6px;font-size:12px;font-weight:700;color:#e60023;text-decoration:none;background:#fff5f5;padding:2px 8px;border-radius:9999px;">
                                <i class="fa fa-pencil" style="font-size:10px;"></i>Edit
                            </a>
                        </td>
                        <td style="text-align:center;font-weight:700;"><?php echo $value['qty'];?></td>
                        <td style="font-weight:700;"><?php echo Utility::rupiah($value['total']);?></td>
                        <td style="text-align:center;">
                            <button rel="<?php echo $key;?>" url="<?php echo Url::to(['site/deleteitem']);?>" class="delete-item" title="Hapus" style="font-family:'Inter',sans-serif;font-size:12px;font-weight:700;color:#9e0a0a;background:#fff;border:1px solid #e5e5e0;border-radius:9999px;padding:6px 14px;cursor:pointer;transition:background .15s;">
                                <i class="fa fa-trash-o" style="margin-right:4px;"></i>Hapus
                            </button>
                        </td>
                    </tr>
            <?php 
                    $no++;
                }
            }
        ?>
    </tbody>
</table>
