<?php
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var app\models\HomepageProduk $model */
?>

<h2>Edit Produk</h2>

<?php $form = ActiveForm::begin(['options' => ['enctype' => 'multipart/form-data']]); ?>
    <?= $form->field($model, 'title')->textInput() ?>
    <?= $form->field($model, 'description')->textarea() ?>
    <?= $form->field($model, 'image')->fileInput() ?>
    <?php if ($model->image): ?>
        <img src="/<?= $model->image ?>" style="max-width:200px;">
    <?php endif; ?>
    <br>
    <?= Html::submitButton('Simpan Perubahan', ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Kembali', ['homepage/edit'], ['class' => 'btn btn-secondary']) ?>
<?php ActiveForm::end(); ?>
