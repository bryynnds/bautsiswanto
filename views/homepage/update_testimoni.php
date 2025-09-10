<?php
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var app\models\HomepageTestimoni $model */
?>

<h2>Edit Testimoni</h2>

<?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'content')->textarea() ?>
    <?= $form->field($model, 'author')->textInput() ?>
    <?= Html::submitButton('Simpan Perubahan', ['class' => 'btn btn-primary']) ?>
    <?= Html::a('Kembali', ['homepage/edit'], ['class' => 'btn btn-secondary']) ?>
<?php ActiveForm::end(); ?>
