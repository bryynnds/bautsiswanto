<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var $model app\models\HomepageProduk */
/** @var $produks app\models\HomepageProduk[] */

// Ensure $model is an object
if (!is_object($model)) {
    $model = new \app\models\HomepageProduk();
}
?>


<!-- <h3>Tambah Produk</h3> -->
<?php $form = ActiveForm::begin([
    'action' => ['homepage/create-produk'],
    'options' => ['enctype' => 'multipart/form-data']
]); ?>
<?= $form->field($model, 'title')->textInput([
    'placeholder' => 'Ketik disini...',
]) ?>

<?= $form->field($model, 'description')->textarea([
    'placeholder' => 'Ketik disini...',
]) ?>

<?= $form->field($model, 'harga')->textInput([
    'placeholder' => 'Masukkan harga produk...',
    'type' => 'number',
    'min' => 0
]) ?>

<?= $form->field($model, 'stok')->textInput([
    'placeholder' => 'Masukkan jumlah stok...',
    'type' => 'number',
    'min' => 0
]) ?>

<?= $form->field($model, 'image')->fileInput() ?>
<?= Html::submitButton('Tambah Produk', ['class' => 'btn btn-success']) ?>
<?php ActiveForm::end(); ?>

<hr>
<h3>Daftar Produk</h3>
<div class="produk-grid produk-admin">
    <?php foreach ($produks as $p): ?>
        <div class="card">


            <h3><?= Html::encode($p->title) ?></h3>
            <?php if ($p->image): ?>
                <img src="<?= Yii::getAlias('@web') ?>/<?= $p->image ?>" alt="<?= $p->title ?>" class="produk-img">
            <?php endif; ?>
            <p><?= Html::encode($p->description) ?></p>
            <p>Rp <?= number_format($p->harga, 0, ',', '.') ?></p>
            <p><strong>Stok:</strong> <?= $p->stok ?> pcs</p>

            <div class="btn-wrapper">
                <?= Html::a('Edit', ['homepage/update-produk', 'id' => $p->id], ['class' => 'btn-edit']) ?>
                <?= Html::a('Hapus', ['homepage/delete-produk', 'id' => $p->id], [
                    'class' => 'btn-hapus',
                    'data' => ['confirm' => 'Yakin hapus produk ini?']
                ]) ?>
            </div>

        </div>
    <?php endforeach; ?>
</div>