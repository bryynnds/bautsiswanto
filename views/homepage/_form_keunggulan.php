<?php

use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;

/** @var $model app\models\HomepageKeunggulan */
/** @var $keunggulans app\models\HomepageKeunggulan[] */

if (!is_object($model)) {
    $model = new \app\models\HomepageKeunggulan();
}
?>

<div class="form-card">
    <h2 class="section-title mb-4">Tambah Keunggulan</h2>

    <?php $form = ActiveForm::begin([
        'id' => 'form-keunggulan', // ✅ WAJIB
        'action' => ['homepage/create-keunggulan'],
        'enableClientValidation' => true,
        'enableAjaxValidation' => false,
    ]); ?>

    <?= $form->field($model, 'title')->textInput([
        'placeholder' => 'Ketik disini...',
    ]) ?>

    <?= $form->field($model, 'subtitle')->textInput([
        'placeholder' => 'Ketik disini...',
    ]) ?>

    <?= Html::submitButton('Tambah Keunggulan', ['class' => 'btn btn-success']) ?>

    <?php ActiveForm::end(); ?>
</div>

<hr>

<div class="promo-card">
    <h3>Daftar Keunggulan</h3>
    <table class="cart-table">
        <thead>
            <tr>
                <th>Judul</th>
                <th>Subjudul</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($keunggulans as $k): ?>
                <tr>
                    <td><?= Html::encode($k->title) ?></td>
                    <td><?= Html::encode($k->subtitle) ?></td>
                    <td>
                        <div class="btn-wrapper">
                            <?= Html::a('Edit', ['homepage/update-keunggulan', 'id' => $k->id], ['class' => 'btn-edit']) ?>
                            <?= Html::a('Hapus', ['homepage/delete-keunggulan', 'id' => $k->id], [
                                'class' => 'btn-hapus',
                                'data' => ['confirm' => 'Yakin hapus keunggulan ini?']
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
    $('#form-keunggulan').on('afterValidate', function (e, messages, errorAttributes) {
        if (errorAttributes.length > 0) {

            $('.custom-alert').remove();

            var alertBox = $('<div class=\"alert alert-danger custom-alert\">Mohon lengkapi data keunggulan dengan benar.</div>');

            $('.form-card .section-title').after(alertBox);

            $('html, body').animate({
                scrollTop: $('.form-card').offset().top - 20
            }, 500);

            $('.has-error input').first().focus();
        }
    });
");
?>