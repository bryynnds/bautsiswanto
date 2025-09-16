<?php
use yii\bootstrap5\Tabs;
use yii\bootstrap5\ActiveForm;
use yii\bootstrap5\Html;
?>

<section class="edit-section loading">
  <div class="container">
    <h2 class="section-title">Ubah Beranda</h2>

    <div class="edit-tabs">
      <?= Tabs::widget([
          'options' => ['class' => 'custom-tabs'], // custom class untuk styling
          'items' => [
              [
                  'label' => 'Slogan Utama',
                  'content' => "<div class='tab-card'>" . 
                      $this->render('_form_hero', ['model' => $hero]) . "</div>",
                  'active' => true
              ],
              [
                  'label' => 'Produk',
                  'content' => "<div class='tab-card'>" . 
                      $this->render('_form_produk', [
                          'model' => $newProduk,
                          'produks' => $produks
                      ]) . "</div>",
              ],
              [
                  'label' => 'Keunggulan',
                  'content' => "<div class='tab-card'>" . 
                      $this->render('_form_keunggulan', [
                          'model' => $newKeunggulan,
                          'keunggulans' => $keunggulans
                      ]) . "</div>",
              ],
              [
                  'label' => 'Testimoni',
                  'content' => "<div class='tab-card'>" . 
                      $this->render('_form_testimoni', [
                          'model' => $newTestimoni,
                          'testimonis' => $testimonis
                      ]) . "</div>",
              ],
          ]
      ]) ?>
    </div>
  </div>
</section>
