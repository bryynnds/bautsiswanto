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
<section class="hero loading"
  style="background-image: url('<?= Yii::getAlias('@web') ?>/<?= $hero->background_image ?? 'images/background.jpg' ?>'); 
         background-size: cover;">
  <div class="hero-text">
    <h1><?= $hero->title ?? 'Kecantikan Natural Bersama Wardah' ?></h1>
    <p><?= $hero->subtitle ?? 'Kosmetik halal, natural, dan terpercaya untuk mendukung pesona cantikmu setiap hari.' ?></p>
    <a href="#produk" class="btn">Lihat Produk</a>
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
        <a href="#" class="btn-beli">Tambah ke keranjang</a>
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