<?php

use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use app\models\HomepageProduk;

/** @var yii\web\View $this */
/** @var app\models\HomepageHero $hero */
/** @var app\models\HomepageProduk[] $produks */
/** @var app\models\HomepageKeunggulan[] $keunggulans */
/** @var app\models\HomepageTestimoni[] $testimonis */
$this->title = 'Wardah Cosmetics';

$dataProvider = new ArrayDataProvider([
  'allModels' => $produks,
  'pagination' => false, // kalau mau disable pagination
]);
?>

<!-- Hero Section -->
<section class="hero">
  <div class="container-hero">
    <div class="hero-image">
      <img src="<?= Yii::getAlias('@web') ?>/<?= $hero->background_image ?? 'images/background.jpg' ?>" alt="Hero Image">
    </div>
    <div class="hero-text">
      <h1><?= $hero->title ?? 'Kecantikan Natural Bersama Wardah' ?></h1>
      <p><?= $hero->subtitle ?? 'Kosmetik halal, natural, dan terpercaya untuk mendukung pesona cantikmu setiap hari.' ?></p>
      <a href="#produk" class="btn">Lihat Produk</a>
    </div>
  </div>
</section>

<!-- Promo Section -->
<section class="promo loading" id="promo">
  <h2>Promo Spesial</h2>
  <div id="promoCarousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="4000" data-bs-pause="hover">

    <!-- Slides -->
    <div class="carousel-inner">
      <?php foreach ($promos as $index => $promo): ?>
        <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
          <div class="card mx-auto" style="max-width: 800px;">
            <img src="<?= Yii::getAlias('@web') ?>/<?= $promo->image ?>" class="card-img-top" alt="<?= $promo->title ?>">
            <div class="card-body text-center">
              <h3 class="card-title"><?= $promo->title ?></h3>
              <p class="card-text">
                <?= Yii::$app->formatter->asDate($promo->start_date, 'php:d F Y') ?>
                –
                <?= Yii::$app->formatter->asDate($promo->end_date, 'php:d F Y') ?>
              </p>
            </div>
          </div>
        </div>
      <?php endforeach; ?>
    </div>

    <!-- Indicators (Dots) di luar card -->
    <div class="carousel-indicators mt-1">
      <?php foreach ($promos as $index => $promo): ?>
        <button type="button" data-bs-target="#promoCarousel" data-bs-slide-to="<?= $index ?>"
          class="<?= $index === 0 ? 'active' : '' ?>" aria-current="<?= $index === 0 ? 'true' : 'false' ?>"
          aria-label="Slide <?= $index + 1 ?>"></button>
      <?php endforeach; ?>
    </div>
  </div>
</section>




<!-- Produk Unggulan -->
<section class="produk loading" id="produk">
  <h2>Produk Unggulan</h2>
  <div class="produk-grid">
    <?php foreach ($produks as $produk): ?>
      <div class="card">
        <h3><?= $produk->title ?></h3>
        <p>Rp <?= number_format($produk->harga, 0, ',', '.') ?></p>
        <img src="<?= Yii::getAlias('@web') ?>/<?= $produk->image ?>" alt="<?= $produk->title ?>" class="produk-img">
        <p><?= $produk->description ?></p>

        <!-- <p><strong>Stok:</strong> <?= $produk->stok ?> pcs</p> -->
        <button class="btn btn-add-cart" data-id="<?= $produk->id ?>">Tambah ke Keranjang</button>

      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- Keunggulan -->
<section class="keunggulan loading" id="keunggulan">
  <h2>Mengapa Memilih Wardah?</h2>
  <div class="keunggulan-grid">
    <?php foreach ($keunggulans as $k): ?>
      <div class="point">
        <h3><?= $k->title ?></h3>
        <p><?= $k->subtitle ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<!-- Testimoni -->
<section class="testimoni loading" id="testimoni">
  <h2>Apa Kata Mereka?</h2>
  <div class="testimoni-grid">
    <?php foreach ($testimonis as $t): ?>
      <div class="testi">
        <p>"<?= $t->content ?>"</p>
        <span>- <?= $t->author ?></span>
      </div>
    <?php endforeach; ?>
  </div>
</section>

<?php
$addUrl = \yii\helpers\Url::to(['cart/add']);
$cartUrl = \yii\helpers\Url::to(['cart/index']);
$csrf = Yii::$app->request->csrfToken;
$js = <<<JS
$(document).on('click', '.btn-add-cart', function() {
    let produkId = $(this).data('id');

    $.ajax({
        url: '/cart/add',
        type: 'POST',
        data: { id: produkId },
        success: function(response) {
            // tampilkan toast
            let toastEl = document.getElementById('cartToast');
            let toast = new bootstrap.Toast(toastEl, { delay: 2000 }); // hilang otomatis 2 detik
            toast.show();
        },
        error: function() {
            alert("Silahkan login terlebih dahulu untuk menambahkan ke keranjang.");
        }
    });
});

$(".btn-add-cart").click(function() {
    var produkId = $(this).data("id");
    $.post("$addUrl", {produk_id: produkId, _csrf: "$csrf"}, function(res) {
        if(res.success) {
            $("#cart-count").text(res.count);
        }
    });
});
JS;
$this->registerJs($js);
?>


<?php
$js = <<<JS
// Intersection Observer for animations
const elements = document.querySelectorAll('.card, .point, .testi, .loading');
const observer = new IntersectionObserver((entries) => {
  entries.forEach(entry => {
    if (entry.isIntersecting) {
      entry.target.classList.add('show');
      entry.target.classList.add('loaded');
    }
  });
}, { 
  threshold: 0.2,
  rootMargin: '0px 0px -50px 0px'
});

elements.forEach(el => observer.observe(el));

// Add smooth scroll for navigation
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
  anchor.addEventListener('click', function (e) {
    e.preventDefault();
    document.querySelector(this.getAttribute('href')).scrollIntoView({
      behavior: 'smooth'
    });
  });
});

// Add loading animation when page loads
window.addEventListener('load', () => {
  document.querySelector('.hero').classList.add('loaded');
});
JS;
$this->registerJs($js);
?>