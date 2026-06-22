<?php

use yii\grid\GridView;
use yii\data\ArrayDataProvider;
use app\models\HomepageProduk;
use yii\helpers\Url;
use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\HomepageHero $hero */
/** @var app\models\HomepageProduk[] $produks */
/** @var app\models\HomepageKeunggulan[] $keunggulans */
/** @var app\models\HomepageTestimoni[] $testimonis */
$this->title = 'Baut Siswanto';

$dataProvider = new ArrayDataProvider([
  'allModels' => $produks,
  'pagination' => false, // kalau mau disable pagination
]);
?>

<!-- Hero Section -->
<section class="hero">
  <div class="container-hero">
    <div class="hero-image">
      <img src="<?= Yii::getAlias('@web') ?>/<?= $hero->background_image ?? 'images/background.jpg' ?>"
        alt="Hero Image">
    </div>
    <div class="hero-text">
      <h1><?= $hero->title ?? 'Pusat Baut dan Mur Berkualitas' ?></h1>

      <p>
        <?= $hero->subtitle ?? 'Menyediakan berbagai jenis baut, mur, ring, dan perlengkapan teknik dengan harga kompetitif dan kualitas terpercaya.' ?>
      </p>
      <a href="/produk/index" class="btn">Lihat Produk</a>
    </div>
  </div>
</section>

<!-- Produk Unggulan -->
<section class="produk loading" id="produk">
  <h2>
    <?= $configTerlaris->title ?? 'Produk Terlaris' ?>
  </h2>
  <div class="produk-grid">
    <?php if (!empty($produkTerlaris)): ?>
      <?php foreach ($produkTerlaris as $p): ?>
        <div class="card">

          <img src="<?= Yii::getAlias('@web') ?>/<?= $p['image'] ?>" alt="<?= $p['title'] ?>" class="produk-img">

          <h3><?= $p['title'] ?></h3>

          <div class="harga-wrapper">

            <?php if ($p['harga_bijian']): ?>
              <div class="harga-item">
                Bijian:
                <strong>
                  Rp <?= number_format($p['harga_bijian'], 0, ',', '.') ?>
                </strong>
              </div>
            <?php endif; ?>

            <?php if ($p['harga_kg']): ?>
              <div class="harga-item">
                Kiloan:
                <strong>
                  Rp <?= number_format($p['harga_kg'], 0, ',', '.') ?>
                </strong>
              </div>
            <?php endif; ?>

          </div>

          <p class="produk-desc">
            <?= $p['description'] ?>
          </p>

          <button class="btn btn-add-cart" data-id="<?= $p['id'] ?>">
            Tambah ke Keranjang
          </button>

        </div>
      <?php endforeach; ?>
    <?php else: ?>
      <p>Belum ada produk terlaris.</p>
    <?php endif; ?>
  </div>
</section>

<!-- Keunggulan -->
<section class="keunggulan loading mb-4" id="keunggulan">
  <h2>Mengapa Harus Memilih Baut Siswanto?</h2>
  <div class="keunggulan-grid">
    <?php foreach ($keunggulans as $k): ?>
      <div class="point">
        <div class="point-icon">
          <i class="<?= Html::encode($k->icon) ?>"></i>
        </div>
        <h3><?= $k->title ?></h3>
        <p><?= $k->subtitle ?></p>
      </div>
    <?php endforeach; ?>
  </div>
</section>


<?php foreach (Yii::$app->session->getAllFlashes() as $type => $message): ?>
  <div class="alert alert-<?= $type ?>">
    <?= $message ?>
  </div>
<?php endforeach; ?>


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