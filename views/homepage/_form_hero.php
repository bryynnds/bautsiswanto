<?php
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var $model app\models\HomepageHero */
if (!is_object($model)) {
    $model = new \app\models\HomepageHero();
}
?>

<?php $form = ActiveForm::begin([
    'action' => ['homepage/edit-hero'],
    'options' => ['enctype' => 'multipart/form-data']
]); ?>

    <?= $form->field($model, 'title')->textInput() ?>
    <?= $form->field($model, 'subtitle')->textInput() ?>
    <?= $form->field($model, 'background_image')->fileInput() ?>
    <?php if ($model->background_image): ?>
        <img src="/<?= $model->background_image ?>" style="max-width:200px;">
    <?php endif; ?>
    <br><br>
    <?= Html::submitButton('Simpan', ['class' => 'btn btn-success']) ?>

<?php ActiveForm::end(); ?>
