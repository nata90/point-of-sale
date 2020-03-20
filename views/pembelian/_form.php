<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
use yii\jui\AutoComplete;
use yii\web\JsExpression;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $model app\models\HeaderPembelian */
/* @var $form yii\widgets\ActiveForm */
$this->registerJs(<<<JS
    var harga_beli = document.getElementById('headerpembelian-harga_beli');
    harga_beli.addEventListener('keyup', function(e){
        harga_beli.value = formatRupiah(this.value, 'Rp. ');   
    });

    var harga_jual = document.getElementById('headerpembelian-harga_jual');
    harga_jual.addEventListener('keyup', function(e){
        harga_jual.value = formatRupiah(this.value, 'Rp. ');   
    });

    $(document).on("click", "#tambah-beli", function () {
        var tgl = $('#headerpembelian-tgl_pembelian').val();
        var kd_bar = $('#headerpembelian-kd_barang').val();
        var nm_barang = $('#headerpembelian-nama_barang').val();
        var jumlah = $('#headerpembelian-jumlah').val();
        var harga_beli = $('#headerpembelian-harga_beli').val();
        var harga_jual = $('#headerpembelian-harga_jual').val();
        var tgl_ed = $('#headerpembelian-tgl_ed').val();

        var rem_harga_beli = harga_beli.replace("Rp. ","");
        var rem_dot_harga_beli = rem_harga_beli.split(".").join("");

        var rem_harga_jual = harga_jual.replace("Rp. ","");
        var rem_dot_harga_jual = rem_harga_jual.split(".").join("");

        var url = $(this).attr("url");

        $.ajax({
            type: 'post',
            url: url,
            dataType: 'json',
            data: {
                'tgl':tgl, 
                'kd_bar':kd_bar,
                'nm_barang':nm_barang,
                'jumlah':jumlah,
                'harga_beli':rem_dot_harga_beli,
                'harga_jual':rem_dot_harga_jual,
                'tgl_ed':tgl_ed
            },
            success: function(v){
                $('#table-list').html(v.table);
                $('#headerpembelian-nama_barang').val('');
                $('#headerpembelian-kd_barang').val('');
                $('#headerpembelian-jumlah').val('');
                $('#headerpembelian-harga_beli').val('');
                $('#headerpembelian-harga_jual').val('');
                $('#headerpembelian-nama_barang').focus();

            }
        });

    });


    $(document).on("click", ".delete-item-pem", function () {
        var url = $(this).attr('url');
        var key = $(this).attr('rel');

        $.ajax({
            type: 'get',
            url: url,
            dataType: 'json',
            data: {
                'key':key, 
            },
            success: function(v){
                $('#table-list').html(v.table);
            }
        });
    });
    
    $(document).on("click", "#dialog-barang", function () {
        var url = $(this).attr('url');
        
        $.ajax({
            type: 'post',
            url: url,
            dataType: 'json',
            success: function(v){
                show_modal(v.title,v.msg);
            },

        });
    });

    $(document).on("click", "#add-barang", function () {
        var url = $(this).attr('url');
        var kd_barang = $('#filebarang-kd_barang').val();
        var nama_barang = $('#filebarang-nama_barang').val();
        var lokasi = $('#filebarang-lokasi').val();
        
        $.ajax({
            type: 'post',
            url: url,
            dataType: 'json',
            data: {
                'kd_barang':kd_barang,
                'nama_barang':nama_barang,
                'lokasi':lokasi 
            },
            success: function(v){
                if(v.return == 0){
                    alert(v.msg);
                }else{
                    $("#modal").modal("hide");
                    $('#headerpembelian-nama_barang').val(v.namabarang);
                    $('#headerpembelian-kd_barang').val(v.kdbarang);
                }
                
            },

        });
    });

    $(document).on("click", "#simpan-beli", function () {
        var tgl = $('#headerpembelian-tgl_pembelian').val();
        var supplier = $('#headerpembelian-id_supplier').val();
        var no_faktur = $('#headerpembelian-no_faktur').val();
        var url = $(this).attr('url');
        
        $.ajax({
            type: 'post',
            url: url,
            dataType: 'json',
            'beforeSend':function(json)
            { 
                SimpleLoading.start('gears'); 
            },
            data: {
                'tgl':tgl,
                'supplier':supplier,
                'no_faktur':no_faktur
            },
            success: function(v){
                if(v.error == 0){
                    location.replace(v.redirect);
                }else{
                    show_modal("<strong style='color:red;'>ERROR</strong>",v.msg);
                }
                
            },
            'complete':function(json)
            {
                SimpleLoading.stop();
            },
        });
    });
    
JS
);
?>

<div class="col-md-12">
    <div class="box box-danger">
        <div class="box-header with-border">
            <h3 class="box-title">
                PEMBELIAN
            </h3>
        </div>
        <?php $form = ActiveForm::begin(); ?>
        <div class="box-body">
            <div class="row">
                <div class="col-md-2">
                    <?= $form->field($model, 'tgl_pembelian')->widget(\yii\jui\DatePicker::class,[
                        'options'=>['class'=>'form-control'],
                    ]) ?>
                </div>
                <div class="col-md-4">
                    <?= $form->field($model, 'no_faktur')->textInput() ?>
                </div>
                <div class="col-md-6">
                    
                    <?= $form->field($model, 'nama_supplier')->widget(\yii\jui\AutoComplete::classname(), [
                        'options' => ['class' => 'form-control'],
                        'clientOptions' => [
                            //'source' => $data,
                            'source' =>Url::to(['pembelian/autocompletesupplier']),
                            'minLength'=>'2', 
                            'autoFill'=>true,
                            'select' => new JsExpression("function( event, ui ) {
                                $('#headerpembelian-id_supplier').val(ui.item.id);
                             }")
                        ],
                    ]) ?>
                    <?= $form->field($model, 'id_supplier')->hiddenInput()->label(false) ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-5">
                   
                    <?= $form->field($model, 'nama_barang')->widget(\yii\jui\AutoComplete::classname(), [
                        'options' => ['class' => 'form-control'],
                        'clientOptions' => [
                            //'source' => $data,
                            'source' =>Url::to(['pembelian/autocompletebarang']),
                            'minLength'=>'2', 
                            'autoFill'=>true,
                            'select' => new JsExpression("function( event, ui ) {
                                $('#headerpembelian-kd_barang').val(ui.item.id);
                             }")
                        ],
                    ]) ?>
                    
                    <?= $form->field($model, 'kd_barang')->hiddenInput()->label(false) ?>
                </div>
                <div class="col-md-1">
                    <button type="button" class="btn btn-success" style="margin-top:25px;" id="dialog-barang" url="<?php echo Url::to(['pembelian/loadformbarang'])?>">+ Barang</button>
                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'tgl_ed')->widget(\yii\jui\DatePicker::class,[
                        'options'=>['class'=>'form-control'],
                    ]) ?>
                </div>
                <div class="col-md-2">
                    <?= $form->field($model, 'jumlah')->textInput() ?>
                </div>
            </div>
            <div class="row">
                <div class="col-md-2">
                    <?= $form->field($model, 'harga_beli')->textInput() ?>
                </div>
                <div class="col-md-2">
                      <?= $form->field($model, 'harga_jual')->textInput() ?>
                </div>
            </div>

        </div>

        <div class="box-footer">
            <?= Html::button(Yii::t('app', 'Tambah'), ['class' => 'btn btn-info pull-right', 'id' => 'tambah-beli', 'url'=>Url::to(['pembelian/listpembelian'])]) ?>
        </div>

        <?php ActiveForm::end(); ?>
    </div>

</div>
<div class="col-md-12">
    <div class="box box-danger">
        <div class="box-header with-border">
            <h3 class="box-title">
                ITEM PEMBELIAN
            </h3>
        </div>
        <div class="box-body" id="table-list">
            <table class="table table-striped">
                <tbody>
                    <tr>
                        <th>Kode Barang</th>
                        <th>Nama Barang</th>
                        <th>Tgl ED</th>
                        <th>Jumlah</th>
                        <th>Harga Beli</th>
                        <th>Harga Jual</th>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                        <td></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
