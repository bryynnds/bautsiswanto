<?php

use yii\helpers\Html;

$this->title = 'Permintaan Produk';

?>

<div class="container mt-5">

    <div class="form-card">

        <h2 class="section-title mb-4">
            Permintaan Produk
        </h2>

        <?php if (Yii::$app->session->hasFlash('success')): ?>
            <div class="alert alert-success">
                <?= Yii::$app->session->getFlash('success') ?>
            </div>
        <?php endif; ?>

        <div class="request-info">
            Total Request:
            <strong><?= count($requests) ?></strong>
        </div>

        <?php if (!empty($requests)): ?>

            <div class="table-responsive">

                <table class="request-table">

                    <thead>
                        <tr>
                            <th>User</th>
                            <th>Produk</th>
                            <th>Jenis</th>
                            <th>Status</th>
                            <th>Tanggal</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>

                    <?php foreach ($requests as $request): ?>

                        <tr>

                            <td>
                                <?= Html::encode(
                                    $request->user->username ?? '-'
                                ) ?>
                            </td>

                            <td>
                                <strong>
                                    <?= Html::encode(
                                        $request->nama_produk
                                    ) ?>
                                </strong>
                            </td>

                            <td>
                                <?= $request->jenisProduk
                                    ? Html::encode(
                                        $request->jenisProduk->nama_jenis
                                    )
                                    : '-' ?>
                            </td>

                            <td>

                                <?php
                                $status = strtolower($request->status);

                                if ($status === 'tersedia') {
                                    $class = 'status-success';
                                } elseif ($status === 'pending') {
                                    $class = 'status-warning';
                                } elseif ($status === 'ditolak') {
                                    $class = 'status-danger';
                                } else {
                                    $class = 'status-primary';
                                }
                                ?>

                                <span class="status-badge <?= $class ?>">
                                    <?= ucfirst($request->status) ?>
                                </span>

                            </td>

                            <td>
                                <?= date(
                                    'd-m-Y H:i',
                                    strtotime($request->created_at)
                                ) ?>
                            </td>

                            <td>

                                <?= Html::a(
                                    'Proses',
                                    ['update', 'id' => $request->id],
                                    [
                                        'class' => 'btn btn-primary btn-sm'
                                    ]
                                ) ?>

                            </td>

                        </tr>

                    <?php endforeach; ?>

                    </tbody>

                </table>

            </div>

        <?php else: ?>

            <div class="empty-request">

                <div class="empty-icon">
                    📦
                </div>

                <h4>Belum Ada Request Produk</h4>

                <p>
                    Request dari pengguna akan muncul di halaman ini.
                </p>

            </div>

        <?php endif; ?>

    </div>

</div>

<style>

.request-info{
    background:#e0f7f6;
    color:#006666;
    padding:12px 16px;
    border-radius:10px;
    margin-bottom:20px;
    font-weight:500;
}

.request-table{
    width:100%;
    border-collapse:collapse;
}

.request-table th,
.request-table td{
    padding:14px;
    border-bottom:1px solid #e5e5e5;
}

.request-table th{
    color:#006666;
    font-weight:600;
    border-bottom:2px solid #006666;
    background:#fff;
}

.request-table tbody tr{
    transition:all .2s ease;
}

.request-table tbody tr:hover{
    background:#f7fdfd;
}

.status-badge{
    padding:6px 12px;
    border-radius:20px;
    font-size:13px;
    font-weight:600;
}

.status-success{
    background:#d1fae5;
    color:#065f46;
}

.status-warning{
    background:#fef3c7;
    color:#92400e;
}

.status-danger{
    background:#fee2e2;
    color:#991b1b;
}

.status-primary{
    background:#dbeafe;
    color:#1e40af;
}

.empty-request{
    text-align:center;
    padding:50px 20px;
}

.empty-icon{
    font-size:60px;
    margin-bottom:15px;
}

.empty-request h4{
    color:#006666;
    margin-bottom:10px;
}

.empty-request p{
    color:#666;
}

</style>