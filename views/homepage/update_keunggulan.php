<?php
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var app\models\HomepageKeunggulan $model */
?>

<h2>Edit Keunggulan</h2>

<?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'title')->textInput() ?>
    <?= $form->field($model, 'subtitle')->textInput() ?>
    <?= Html::submitButton('Simpan Perubahan', ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Kembali', ['homepage/edit'], ['class' => 'btn btn-secondary']) ?>
<?php ActiveForm::end(); ?>
