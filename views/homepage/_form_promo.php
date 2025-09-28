<?php
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var $model app\models\HomepagePromo */
/** @var $promos app\models\HomepagePromo[] */

if (!is_object($model)) {
    $model = new \app\models\HomepagePromo();
}
?>

<?php $form = ActiveForm::begin([
    'action' => ['homepage/create-promo'],
    'options' => ['enctype' => 'multipart/form-data']
]); ?>

<?= $form->field($model, 'title')->textInput(['placeholder' => 'Judul promo...']) ?>
<?= $form->field($model, 'start_date')->input('date') ?>
<?= $form->field($model, 'end_date')->input('date') ?>
<?= $form->field($model, 'imageFile')->fileInput() ?>

<?= Html::submitButton('Tambah Promo', ['class' => 'btn btn-success']) ?>
<?php ActiveForm::end(); ?>

<hr>
<h3>Daftar Promo</h3>
<div class="produk-grid produk-admin">
    <?php foreach ($promos as $p): ?>
        <div class="card">
            <h3><?= Html::encode($p->title) ?></h3>
            <p><?= Yii::$app->formatter->asDate($p->start_date, 'php:d M Y') ?>
                - <?= Yii::$app->formatter->asDate($p->end_date, 'php:d M Y') ?></p>

            <?php if ($p->image): ?>
                <img src="<?= Yii::getAlias('@web') ?>/<?= $p->image ?>" alt="<?= $p->title ?>" class="produk-img">
            <?php endif; ?>

            <div class="btn-wrapper">
                <?= Html::a('Edit', ['homepage/update-promo', 'id' => $p->id], ['class' => 'btn-edit']) ?>
                <?= Html::a('Hapus', ['homepage/delete-promo', 'id' => $p->id], [
                    'class' => 'btn-hapus',
                    'data' => ['confirm' => 'Yakin hapus promo ini?']
                ]) ?>
            </div>
        </div>
    <?php endforeach; ?>
</div>
