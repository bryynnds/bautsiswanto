<?php
/** @var yii\web\View $this */
$this->title = 'Wardah Cosmetics';
?>

<!-- Hero Section -->
<section class="hero loading">
  <div class="hero-text">
    <h1>Kecantikan Natural Bersama Wardah</h1>
    <p>Kosmetik halal, natural, dan terpercaya untuk mendukung pesona cantikmu setiap hari.</p>
    <a href="#produk" class="btn">Lihat Produk</a>
  </div>
</section>

<!-- Produk Unggulan -->
<section class="produk loading" id="produk">
  <h2>Produk Unggulan</h2>
  <div class="produk-grid">
    <div class="card">
      <img src="<?= Yii::getAlias('@web') ?>/images/lipstik.png" alt="Kosmetik Wardah">
      <h3>Lipstick</h3>
      <p>Warna tahan lama dengan kandungan natural.</p>
      <a href="#" class="btn-beli">Beli Sekarang</a>
    </div>
    <div class="card">
      <img src="<?= Yii::getAlias('@web') ?>/images/foundation.png" alt="Kosmetik Wardah">
      <h3>Foundation</h3>
      <p>Cocok untuk semua jenis kulit dengan hasil flawless.</p>
      <a href="#" class="btn-beli">Beli Sekarang</a>
    </div>
    <div class="card">
      <img src="<?= Yii::getAlias('@web') ?>/images/mascara.png" alt="Kosmetik Wardah">
      <h3>Mascara</h3>
      <p>Tampilan bulu mata tebal alami sepanjang hari.</p>
      <a href="#" class="btn-beli">Beli Sekarang</a>
    </div>
  </div>
</section>

<!-- Keunggulan -->
<section class="keunggulan loading" id="keunggulan">
  <h2>Mengapa Memilih Wardah?</h2>
  <div class="keunggulan-grid">
    <div class="point">
      <h3>Halal</h3>
      <p>Diformulasikan sesuai standar halal internasional.</p>
    </div>
    <div class="point">
      <h3>Alami</h3>
      <p>Bahan alami yang aman digunakan setiap hari.</p>
    </div>
    <div class="point">
      <h3>Aman</h3>
      <p>Teruji aman oleh para ahli dermatologi.</p>
    </div>
  </div>
</section>

<!-- Testimoni -->
<section class="testimoni loading" id="testimoni">
  <h2>Apa Kata Mereka?</h2>
  <div class="testimoni-grid">
    <div class="testi">
      <p>"Produk Wardah bikin wajahku lebih fresh, dan aku suka karena halal."</p>
      <span>- Rani, 25 th</span>
    </div>
    <div class="testi">
      <p>"Foundation-nya ringan banget, cocok dipakai sehari-hari."</p>
      <span>- Sinta, 22 th</span>
    </div>
    <div class="testi">
      <p>"Skincare Wardah bikin kulitku lembab dan glowing natural."</p>
      <span>- Dwi, 27 th</span>
    </div>
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
