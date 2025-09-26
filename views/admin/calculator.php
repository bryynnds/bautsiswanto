<?php
use yii\helpers\Html;

$this->title = 'Kalkulator Kasir';
?>

<div class="container mt-5">
    <section class="dashboard">
        <!-- <h2><?= Html::encode($this->title) ?></h2> -->

        <div class="row">
            <!-- Kiri: Kasir -->
            <div class="col-md-6">
                <div class="dashboard-card p-3">
                    <h3>Tambah Produk</h3>
                    <div class="row g-2">
                        <div class="col-md-7">
                            <select id="productSelect" class="form-select">
                                <option value="">-- Pilih Produk --</option>
                                <?php foreach ($produks as $produk): ?>
                                    <option value="<?= $produk->id ?>"
                                            data-harga="<?= $produk->harga ?>"
                                            data-nama="<?= Html::encode($produk->title) ?>">
                                        <?= Html::encode($produk->title) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="number" id="productPrice" class="form-control" placeholder="Harga" readonly>
                        </div>
                        <div class="col-md-3">
                            <input type="number" id="productQty" class="form-control" placeholder="Jumlah" min="1">
                        </div>
                    </div>
                    <div class="mt-3">
                        <button id="addItemBtn" type="button" class="btn btn-success">Tambah</button>
                    </div>
                </div>
            </div>

            <!-- Kanan: Kalkulator Sederhana -->
            <div class="col-md-6">
                <div class="dashboard-card p-3">
                    <h3>Kalkulator</h3>
                    <input type="text" id="calcInput" class="form-control mb-2" readonly>
                    <div class="row g-2">
                        <?php
                        $buttons = [
                            ['1','2','3','/'],
                            ['4','5','6','*'],
                            ['7','8','9','-'],
                            ['0','.','=','+'],
                        ];
                        foreach ($buttons as $row): ?>
                            <div class="col-12 d-flex gap-2 mb-2">
                                <?php foreach ($row as $btn): ?>
                                    <button type="button" class="btn btn-secondary flex-fill calc-btn"><?= $btn ?></button>
                                <?php endforeach; ?>
                            </div>
                        <?php endforeach; ?>
                        <div class="col-12">
                            <button type="button" class="btn btn-danger w-100" id="clearCalc">C</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Order -->
        <h3 class="mt-4">Daftar Belanja</h3>
        <div class="dashboard-card">
            <table class="cart-table" id="orderTable">
                <thead>
                    <tr>
                        <th>Nama Produk</th>
                        <th>Harga</th>
                        <th>Qty</th>
                        <th>Sub Total</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                </tbody>
                <tfoot>
                    <tr>
                        <th colspan="3" class="text-end">Grand Total</th>
                        <th id="grandTotal">Rp 0</th>
                        <th></th>
                    </tr>
                </tfoot>
            </table>
        </div>
    </section>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const productSelect = document.getElementById('productSelect');
    const productPrice = document.getElementById('productPrice');
    const productQty = document.getElementById('productQty');
    const addBtn = document.getElementById('addItemBtn');
    const tbody = document.querySelector('#orderTable tbody');
    const grandTotalEl = document.getElementById('grandTotal');
    let grandTotal = 0;

    productSelect.addEventListener('change', function () {
        const opt = this.options[this.selectedIndex];
        productPrice.value = opt ? opt.getAttribute('data-harga') : '';
    });

    addBtn.addEventListener('click', function () {
        const opt = productSelect.options[productSelect.selectedIndex];
        if (!opt.value) return alert('Pilih produk dulu');

        const name = opt.getAttribute('data-nama');
        const price = parseFloat(productPrice.value);
        const qty = parseInt(productQty.value, 10);
        if (!price || !qty) return alert('Isi qty dengan benar');

        const subtotal = price * qty;
        grandTotal += subtotal;

        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${name}</td>
            <td>Rp ${price.toLocaleString('id-ID')}</td>
            <td>${qty}</td>
            <td>Rp ${subtotal.toLocaleString('id-ID')}</td>
            <td><button type="button" class="btn btn-danger btn-sm btn-remove">Hapus</button></td>
        `;
        tbody.appendChild(tr);
        updateGrandTotal();

        productSelect.selectedIndex = 0;
        productPrice.value = '';
        productQty.value = '';
    });

    tbody.addEventListener('click', e => {
        if (e.target.classList.contains('btn-remove')) {
            const row = e.target.closest('tr');
            const subtotalText = row.cells[3].innerText;
            const subtotalNumber = parseInt(subtotalText.replace(/[^\d]/g,''), 10) || 0;
            grandTotal -= subtotalNumber;
            row.remove();
            updateGrandTotal();
        }
    });

    function updateGrandTotal() {
        grandTotalEl.innerText = 'Rp ' + grandTotal.toLocaleString('id-ID');
    }

    // Kalkulator
    const calcInput = document.getElementById('calcInput');
    document.querySelectorAll('.calc-btn').forEach(btn => {
        btn.addEventListener('click', () => {
            const val = btn.innerText;
            if (val === '=') {
                try { calcInput.value = eval(calcInput.value); }
                catch { calcInput.value = 'Error'; }
            } else {
                calcInput.value += val;
            }
        });
    });
    document.getElementById('clearCalc').addEventListener('click', () => {
        calcInput.value = '';
    });
});
</script>
