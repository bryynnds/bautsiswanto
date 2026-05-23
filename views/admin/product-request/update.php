<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;
?>

<div class="container mt-4">

    <h2 class="mb-4">Proses Request Produk</h2>

    <div class="card p-4">

        <h4><?= Html::encode($model->nama_produk) ?></h4>

        <p>
            <strong>User:</strong>
            <?= Html::encode($model->user->username ?? '-') ?>
        </p>

        <p>
            <strong>Jenis Produk:</strong>
            <?= $model->jenisProduk
                ? Html::encode($model->jenisProduk->nama_jenis)
                : '-' ?>
        </p>

        <p>
            <strong>Material:</strong>
            <?= Html::encode($model->material) ?>
        </p>

        <p>
            <strong>Keterangan:</strong><br>
            <?= nl2br(Html::encode($model->keterangan)) ?>
        </p>

        <?php if ($model->foto): ?>

            <p>
                <strong>Foto:</strong><br>

                <img
                    src="<?= Yii::getAlias('@web') . '/uploads/request-products/' . $model->foto ?>"
                    width="300"
                    class="img-fluid border rounded"
                >
            </p>

        <?php endif; ?>

        <hr>

        <?php $form = ActiveForm::begin(); ?>

        <?= $form->field($model, 'status')->dropDownList([
            'pending' => 'Pending',
            'diproses' => 'Diproses',
            'tersedia' => 'Tersedia',
            'tidak_ditemukan' => 'Tidak Ditemukan',
        ]) ?>

        <?= $form->field($model, 'admin_note')->textarea([
            'rows' => 5
        ]) ?>

        <div class="mt-3">

            <?= Html::submitButton(
                'Simpan Perubahan',
                ['class' => 'btn btn-success']
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