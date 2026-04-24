<?php
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var $model app\models\HomepagePromo */
/** @var $promos app\models\HomepagePromo[] */

if (!is_object($model)) {
    $model = new \app\models\HomepagePromo();
}
?>

<div class="form-card">
    <h2 class="section-title mb-4">Tambah Promo</h2>

    <?php $form = ActiveForm::begin([
        'id' => 'form-promo', // ✅ WAJIB
        'action' => ['homepage/create-promo'],
        'options' => ['enctype' => 'multipart/form-data'],
        'enableClientValidation' => true,
        'enableAjaxValidation' => false,
    ]); ?>

    <?= $form->field($model, 'title')->textInput(['placeholder' => 'Judul promo...']) ?>
    <?= $form->field($model, 'start_date')->input('date') ?>
    <?= $form->field($model, 'end_date')->input('date') ?>
    <?= $form->field($model, 'imageFile')->fileInput() ?>

    <?= Html::submitButton('Tambah Promo', ['class' => 'btn btn-success']) ?>

    <?php ActiveForm::end(); ?>
</div>

<hr>

<div class="promo-card">
    <h3>Daftar Promo</h3>
    <table class="cart-table">
        <thead>
            <tr>
                <th>Gambar</th>
                <th>Judul Promo</th>
                <th>Periode</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($promos as $p): ?>
                <tr>
                    <td>
                        <?php if ($p->image): ?>
                            <img src="<?= Yii::getAlias('@web') ?>/<?= $p->image ?>" 
                                 alt="<?= Html::encode($p->title) ?>" 
                                 class="cart-img">
                        <?php endif; ?>
                    </td>
                    <td><?= Html::encode($p->title) ?></td>
                    <td>
                        <?= Yii::$app->formatter->asDate($p->start_date, 'php:d M Y') ?>
                        - <?= Yii::$app->formatter->asDate($p->end_date, 'php:d M Y') ?>
                    </td>
                    <td>
                        <div class="btn-wrapper">
                            <?= Html::a('Edit', ['homepage/update-promo', 'id' => $p->id], ['class' => 'btn-edit']) ?>
                            <?= Html::a('Hapus', ['homepage/delete-promo', 'id' => $p->id], [
                                'class' => 'btn-hapus',
                                'data' => ['confirm' => 'Yakin hapus promo ini?']
                            ]) ?>
                        </div>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>

<?php
$this->registerJs("
    $('#form-promo').on('afterValidate', function (e, messages, errorAttributes) {
        if (errorAttributes.length > 0) {

            $('.custom-alert').remove();

            var alertBox = $('<div class=\"alert alert-danger custom-alert\">Mohon lengkapi data promo dengan benar.</div>');

            $('.form-card .section-title').after(alertBox);

            $('html, body').animate({
                scrollTop: $('.form-card').offset().top - 20
            }, 500);

            $('.has-error input, .has-error textarea').first().focus();
        }
    });
");
?>