<?php

use yii\helpers\Html;

?>

<div style="text-align:center;">

    <h1 style="margin-bottom:5px;">
        BAUT SISWANTO
    </h1>

    <h3 style="margin-top:0;">
        Laporan Penjualan Produk Baut dan Mur
    </h3>

</div>

<table width="100%" style="margin-bottom:20px;">

    <tr>
        <td width="25%">
            Periode
        </td>

        <td>
            :
            <?= $startDate ?: '-' ?>
            s/d
            <?= $endDate ?: '-' ?>
        </td>
    </tr>

    <tr>
        <td>
            Tanggal Cetak
        </td>

        <td>
            :
            <?= date('d-m-Y H:i') ?>
        </td>
    </tr>

</table>

<hr>

<h3 style="
background:#eeeeee;
padding:8px;
border:1px solid #cccccc;
">
    Ringkasan
</h3>

<table width="100%" border="1" cellspacing="0" cellpadding="6">

    <tr>
        <th>Total Pendapatan</th>
        <th>Total Pesanan</th>
        <th>Total Pelanggan</th>
    </tr>

    <tr>
        <td>
            Rp <?= number_format($totalPendapatan ?? 0, 0, ',', '.') ?>
        </td>

        <td>
            <?= $totalPesanan ?>
        </td>

        <td>
            <?= $totalPelanggan ?>
        </td>
    </tr>

</table>

<br>

<h3 style="
background:#eeeeee;
padding:8px;
border:1px solid #cccccc;
">
    Daftar Transaksi
</h3>

<table width="100%" border="1" cellspacing="0" cellpadding="6">

    <tr>
        <th>No</th>
        <th>Tanggal</th>
        <th>Nama</th>
        <th>Status</th>
        <th>Total</th>
    </tr>

    <?php foreach ($orders as $i => $order): ?>

        <tr>

            <td>
                <?= $i + 1 ?>
            </td>

            <td>
                <?= $order->created_at ?>
            </td>

            <td>
                <?= Html::encode($order->nama) ?>
            </td>

            <td>
                <?= $order->getStatusLabel() ?>
            </td>

            <td>
                Rp
                <?= number_format($order->total, 0, ',', '.') ?>
            </td>

        </tr>

    <?php endforeach; ?>

</table>

<br><br><br>

<table width="100%">

    <tr>

        <td width="60%">
        </td>

        <td align="center">

            Mengetahui,

            <br><br><br><br>

            <b>
                Pemilik
            </b>

        </td>

    </tr>

</table>