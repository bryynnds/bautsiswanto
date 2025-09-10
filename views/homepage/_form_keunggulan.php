<?php
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var $model app\models\HomepageKeunggulan */
/** @var $keunggulans app\models\HomepageKeunggulan[] */

// Ensure $model is an object of HomepageKeunggulan
if (!is_object($model)) {
    $model = new \app\models\HomepageKeunggulan();
}
?>

<h3>Tambah Keunggulan</h3>
<?php $form = ActiveForm::begin(); ?>
    <?= $form->field($model, 'title')->textInput() ?>
    <?= $form->field($model, 'subtitle')->textInput() ?>
    <?= Html::submitButton('Tambah Keunggulan', ['class' => 'btn btn-success']) ?>
<?php ActiveForm::end(); ?>

<hr>
<h3>Daftar Keunggulan</h3>
<ul>
<?php foreach ($keunggulans as $k): ?>
    <li><b><?= $k->title ?></b> - <?= $k->subtitle ?></li>
<?php endforeach; ?>
</ul>
