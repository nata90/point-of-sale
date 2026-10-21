<?php
use app\components\Utility;

/* @var $header app\models\HdTransaksi */
/* @var $detail app\models\DtTransaksi[] */
/* @var $setting app\models\SettingApp */

$storeName = ($setting && !empty($setting->app_name)) ? $setting->app_name : 'TOKO TAMAN HATI';
$storeAddress = ($setting && !empty($setting->alamat)) ? $setting->alamat : 'KAUMAN PEDAN KLATEN';
$storeTelp = ($setting && !empty($setting->no_telp)) ? $setting->no_telp : '';
$storeEmail = ($setting && !empty($setting->email)) ? $setting->email : '';
?>

<div class="nota-wrapper">
    <!-- Header Toko -->
    <div class="text-center">
        <div class="header-title"><?= htmlspecialchars(strtoupper($storeName)) ?></div>
        <div class="header-sub">
            <?= nl2br(htmlspecialchars($storeAddress)) ?><br>
            <?php if (!empty($storeTelp)): ?>
                TELP : <?= htmlspecialchars($storeTelp) ?><br>
            <?php endif; ?>
            <?php if (!empty($storeEmail)): ?>
                Email: <?= htmlspecialchars($storeEmail) ?><br>
            <?php endif; ?>
        </div>
    </div>

    <div class="divider"></div>

    <!-- Metadata Transaksi -->
    <table class="meta-table">
        <tr>
            <td style="width: 58px;">No Faktur</td>
            <td style="width: 6px;">:</td>
            <td class="bold"><?= htmlspecialchars($header->no_transaksi) ?></td>
        </tr>
        <tr>
            <td>Tanggal</td>
            <td>:</td>
            <td><?= $header->tgl_bayar ? date('d/m/Y H:i', strtotime($header->tgl_bayar)) : date('d/m/Y H:i') ?></td>
        </tr>
        <tr>
            <td>Kasir</td>
            <td>:</td>
            <td><?= !Yii::$app->user->isGuest ? htmlspecialchars(Yii::$app->user->identity->username) : 'Kasir' ?></td>
        </tr>
    </table>

    <div class="divider"></div>

    <!-- Daftar Barang -->
    <table class="item-table">
        <?php 
        $all_total = 0;
        if (!empty($detail)): 
            foreach ($detail as $item):
                $subtotal = (float)$item->total_harga;
                $all_total += $subtotal;
                $namaBarang = ($item->barang && !empty($item->barang->nama_barang)) ? $item->barang->nama_barang : 'Barang';
        ?>
            <tr>
                <td colspan="2" class="bold" style="padding-top: 3px;">
                    <?= htmlspecialchars($namaBarang) ?>
                </td>
            </tr>
            <tr>
                <td style="color: #333; font-size: 9px; padding-bottom: 3px;">
                    <?= (int)$item->qty ?> x <?= Utility::rupiah($item->harga_satuan) ?>
                </td>
                <td class="text-right bold" style="padding-bottom: 3px;">
                    <?= Utility::rupiah($subtotal) ?>
                </td>
            </tr>
        <?php 
            endforeach; 
        else:
        ?>
            <tr>
                <td colspan="2" class="text-center" style="padding: 10px 0; color: #666;">
                    Tidak ada item transaksi
                </td>
            </tr>
        <?php endif; ?>
    </table>

    <div class="divider"></div>

    <!-- Total & Pembayaran -->
    <table class="summary-table">
        <?php if (!empty($header->diskon) && $header->diskon > 0): ?>
        <tr>
            <td>Subtotal</td>
            <td class="text-right"><?= Utility::rupiah($header->total + $header->diskon) ?></td>
        </tr>
        <tr>
            <td>Diskon</td>
            <td class="text-right" style="color: #c00;">- <?= Utility::rupiah($header->diskon) ?></td>
        </tr>
        <?php endif; ?>
        <tr>
            <td>Total</td>
            <td class="text-right bold"><?= Utility::rupiah($header->total) ?></td>
        </tr>
        <tr>
            <td>Bayar</td>
            <td class="text-right"><?= Utility::rupiah($header->jumlah_bayar) ?></td>
        </tr>
        <tr>
            <td class="bold">Kembali</td>
            <td class="text-right bold"><?= Utility::rupiah($header->jumlah_bayar - $header->total) ?></td>
        </tr>
    </table>

    <div class="divider"></div>

    <!-- Footer Ucapan -->
    <div class="footer-text">
        *** TERIMA KASIH ***<br>
        Barang yang sudah dibeli<br>
        tidak dapat ditukar / dikembalikan
    </div>
</div>
