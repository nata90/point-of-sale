<?php 
use app\components\Utility;
use yii\helpers\Url;
use yii\helpers\Html;
?>
<table class="table table-striped">
    <tbody>
        <tr>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Jumlah</th>
            <th>Harga Beli</th>
            <th>Harga Jual</th>
            <th></th>
        </tr>
        <?php if($arr_data != null){
            foreach($arr_data as $key=>$val){
        ?>
            <tr>
                <td><?php echo $val['kodebarang'];?></td>
                <td><?php echo $val['namabarang'];?></td>
                <td><?php echo $val['jumlah'];?></td>
                <td style="text-align:right;"><?php echo Utility::rupiah((int)$val['hargabeli']);?></td>
                <td style="text-align:right;"><?php echo Utility::rupiah((int)$val['hargajual']);?></td>
                <td style="text-align:center;"><a rel="<?php echo $key; ?>" url="<?php echo Url::to(['pembelian/deleteitem']); ?>" class="delete-item-pem" href="#" title="Delete"><i class="fa fa-trash"></i></a></td>
            </tr>
        <?php }
        } ?>
    </tbody>
</table>
<?= Html::button(Yii::t('app', 'Simpan'), ['class' => 'btn btn-info pull-right', 'id' => 'simpan-beli', 'url'=>Url::to(['pembelian/simpanpembelian'])]) ?>