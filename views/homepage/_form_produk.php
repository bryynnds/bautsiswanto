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

<?= $form->field($model, 'brand_name')->textInput([
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

<?= $form->field($model, 'link')->textInput([
    'placeholder' => 'Ketik disini...',
]) ?>

<?= $form->field($model, 'image')->fileInput() ?>
<?= Html::submitButton('Tambah Produk', ['class' => 'btn btn-success']) ?>
<?php ActiveForm::end(); ?>

<hr>
<div class="promo-card">
    <h3>Daftar Produk</h3>
    <table class="cart-table">
        <thead>
            <tr>
                <th>Gambar</th>
                <th>Nama Produk</th>
                <th>Deskripsi</th>
                <th>Harga</th>
                <th>Stok</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($produks as $p): ?>
                <tr>
                    <td>
                        <?php if ($p->image): ?>
                            <img src="<?= Yii::getAlias('@web') ?>/<?= $p->image ?>"
                                alt="<?= Html::encode($p->title) ?>"
                                class="cart-img">
                        <?php endif; ?>
                    </td>
                    <td><?= Html::encode($p->title) ?></td>
                    <td><?= Html::encode($p->description) ?></td>
                    <td>Rp <?= number_format($p->harga, 0, ',', '.') ?></td>
                    <td><?= $p->stok ?> pcs</td>
                    <td>
                        <div class="btn-wrapper">
                            <?= Html::a('Edit', ['homepage/update-produk', 'id' => $p->id], ['class' => 'btn-edit']) ?>
                            <?= Html::a('Hapus', ['homepage/delete-produk', 'id' => $p->id], [
                                'class' => 'btn-hapus',
                                'data' => ['confirm' => 'Yakin hapus produk ini?']
                            ]) ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>