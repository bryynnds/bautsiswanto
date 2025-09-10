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


<h3>Tambah Produk</h3>
<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <?= $form->field($model, 'title')->textInput() ?>
    <?= $form->field($model, 'description')->textarea() ?>
    <?= $form->field($model, 'image')->fileInput() ?>
    <?= Html::submitButton('Tambah Produk', ['class' => 'btn btn-success']) ?>
<?php ActiveForm::end(); ?>

<hr>
<h3>Daftar Produk</h3>
<ul>
<?php foreach ($produks as $p): ?>
    <li>
        <b><?= $p->title ?></b> - <?= $p->description ?>
        <?php if ($p->image): ?>
            <br><img src="/<?= $p->image ?>" style="max-width:100px;">
        <?php endif; ?>
    </li>
<?php endforeach; ?>
</ul>
