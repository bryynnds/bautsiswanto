<?php

use yii\helpers\Html;
use yii\widgets\ActiveForm;

/** @var yii\web\View $this */
/** @var app\models\Keranjang[] $items */

$this->title = 'Checkout';
?>

<div class="container mt-5">
    <section class="checkout-section loading">
        <h2><?= Html::encode($this->title) ?></h2>

        <?php if (!empty($items)): ?>

            <?php
            // Hitung grand total dulu supaya bisa digunakan di form
            $grandTotal = 0;
            foreach ($items as $item) {
                $harga = (int)$item->produk->harga;
                $subtotal = $harga * $item->jumlah;
                $grandTotal += $subtotal;
            }
            ?>

            <div class="row mt-4">
                <!-- Form Data -->
                <div class="col-md-6">
                    <?php $form = ActiveForm::begin([
                        'action' => ['/checkout/process'], // sesuaikan route jika beda
                        'method' => 'post',
                    ]); ?>

                    <div class="mb-3">
                        <label class="form-label">Nama Lengkap</label>
                        <input type="text" name="nama" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Alamat</label>
                        <textarea name="alamat" class="form-control" rows="3" required></textarea>
                    </div>

                    <div class="mb-3">
                        <label for="no_hp" class="form-label">No HP</label>
                        <input type="text" class="form-control" id="no_hp" name="no_hp" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Metode Pembayaran</label>
                        <select name="metode_pembayaran" class="form-select" required>
                            <option value="COD">COD</option>
                            <option value="Transfer Bank">Transfer Bank</option>
                        </select>
                    </div>

                    <!-- kirim total sebagai hidden -->
                    <input type="hidden" name="total" value="<?= htmlspecialchars($grandTotal, ENT_QUOTES) ?>">

                    <button type="submit" class="btn-checkout btn btn-primary">Bayar</button>

                    <?php ActiveForm::end(); ?>
                </div>

                <!-- Ringkasan Order -->
                <div class="col-md-6">
                    <h4>Ringkasan Pesanan</h4>
                    <div class="cart-table-wrapper">
                        <table class="cart-table table">
                            <thead>
                                <tr>
                                    <th>Produk</th>
                                    <th>Harga</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($items as $item): ?>
                                    <?php
                                    $harga = (int)$item->produk->harga;
                                    $subtotal = $harga * $item->jumlah;
                                    ?>
                                    <tr>
                                        <td><?= Html::encode($item->produk->title) ?></td>
                                        <td>Rp <?= number_format($harga, 0, ',', '.') ?></td>
                                        <td><?= $item->jumlah ?></td>
                                        <td>Rp <?= number_format($subtotal, 0, ',', '.') ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                            <tfoot>
                                <tr>
                                    <th colspan="3" class="text-end">Total</th>
                                    <th>Rp <?= number_format($grandTotal, 0, ',', '.') ?></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                </div>
            </div>
        <?php else: ?>
            <div class="cart-empty text-center mt-4">
                <p>Keranjang Anda kosong.</p>
                <?= Html::a('Belanja Sekarang', ['site/index'], ['class' => 'btn-checkout btn btn-primary']) ?>
            </div>
        <?php endif; ?>
    </section>
</div>
