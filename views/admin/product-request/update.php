<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

$this->title = 'Proses Permintaan Produk';

?>

<div class="container mt-5">

    <div class="form-card">

        <h2 class="section-title mb-4">
            Proses Permintaan Produk
        </h2>

        <div class="request-detail">

            <div class="detail-item">
                <span>Nama Produk</span>
                <strong><?= Html::encode($model->nama_produk) ?></strong>
            </div>

            <div class="detail-item">
                <span>User</span>
                <strong>
                    <?= Html::encode($model->user->username ?? '-') ?>
                </strong>
            </div>

            <div class="detail-item">
                <span>Jenis Produk</span>
                <strong>
                    <?= $model->jenisProduk
                        ? Html::encode($model->jenisProduk->nama_jenis)
                        : '-' ?>
                </strong>
            </div>

            <div class="detail-item">
                <span>Material</span>
                <strong>
                    <?= Html::encode($model->material ?: '-') ?>
                </strong>
            </div>

            <div class="detail-item full-width">
                <span>Keterangan</span>

                <div class="description-box">
                    <?= nl2br(Html::encode($model->keterangan ?: '-')) ?>
                </div>
            </div>

            <?php if ($model->foto): ?>

                <div class="detail-item full-width">

                    <span>Foto Referensi</span>

                    <div class="photo-wrapper">

                        <img src="<?= Yii::getAlias('@web') . '/uploads/request-products/' . $model->foto ?>"
                            class="request-photo" alt="Foto Request Produk">

                    </div>

                </div>

            <?php endif; ?>

        </div>

        <hr class="my-4">

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'status')->dropDownList([
            'pending' => 'Pending',
            'diproses' => 'Diproses',
            'tersedia' => 'Tersedia',
            'tidak_ditemukan' => 'Tidak Ditemukan',
        ]) ?>

        <?= $form->field($model, 'admin_note')->textarea([
            'rows' => 5,
            'placeholder' => 'Tambahkan catatan untuk pengguna...'
        ]) ?>

        <div class="btn-group-custom">

            <?= Html::submitButton(
                'Simpan Perubahan',
                ['class' => 'btn btn-primary']
            ) ?>

            <?= Html::a(
                'Kembali',
                ['index'],
                ['class' => 'btn btn-secondary']
            ) ?>

        </div>

        <?php ActiveForm::end(); ?>

    </div>

</div>

<style>
    .request-detail {
        display: flex;
        flex-wrap: wrap;
        gap: 20px;
    }

    .detail-item {
        flex: 1 1 calc(50% - 20px);
        background: #f8fdfd;
        border-radius: 10px;
        padding: 15px;
        border: 1px solid #e0f2f1;
    }

    .detail-item span {
        display: block;
        color: #666;
        font-size: 14px;
        margin-bottom: 6px;
    }

    .detail-item strong {
        color: #006666;
        font-size: 16px;
    }

    .full-width {
        flex: 1 1 100%;
    }

    .description-box {
        background: #fff;
        border-radius: 8px;
        padding: 12px;
        margin-top: 5px;
        border: 1px solid #eee;
        line-height: 1.7;
    }

    .photo-wrapper {
        margin-top: 10px;
        display: flex;
        justify-content: center;
    }

    .request-photo {
        width: 250px;
        height: 250px;
        object-fit: cover;
        border-radius: 12px;
        border: 4px solid #fff;
        box-shadow: 0 4px 12px rgba(0, 0, 0, .1);
    }

    .form-control,
    .form-select {
        border-radius: 8px;
    }

    .btn-group-custom {
        margin-top: 20px;
        display: flex;
        gap: 10px;
    }

    hr {
        opacity: .15;
    }

    @media (max-width:768px) {

        .detail-item {
            flex: 1 1 100%;
        }

        .request-photo {
            width: 100%;
        }

        .btn-group-custom {
            flex-direction: column;
        }

    }
</style>