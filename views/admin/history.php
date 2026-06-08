<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\User[] $users */

$this->title = 'Riwayat Belanja';
?>

<div class="container mt-5">
    <!-- <h3>Riwayat Belanja</h3> -->

    <div class="dashboard-card">
        <h3>Riwayat Transaksi</h3>

        <select id="userSelect" class="form-control mb-4">
            <option value="">-- Pilih Customer --</option>
            <?php foreach ($users as $user): ?>
                <option value="<?= $user->id ?>"><?= Html::encode($user->username) ?></option>
            <?php endforeach; ?>
        </select>

        <div id="historyFilter" style="display:none;">

            <div class="filter-toolbar">

                <input type="text" id="searchHistory" class="filter-input" placeholder="Cari produk atau nama...">

                <select id="bulanFilter" class="filter-select">

                    <option value="">
                        Semua Bulan
                    </option>

                    <?php for ($i = 1; $i <= 12; $i++): ?>

                        <option value="<?= sprintf('%02d', $i) ?>">
                            <?= date('F', mktime(0, 0, 0, $i, 1)) ?>
                        </option>

                    <?php endfor; ?>

                </select>

                <select id="tahunFilter" class="filter-select">

                    <option value="">
                        Semua Tahun
                    </option>

                </select>

                <select id="sortFilter" class="filter-select">

                    <option value="desc">
                        Terbaru
                    </option>

                    <option value="asc">
                        Terlama
                    </option>

                </select>

            </div>

        </div>
    </div>

    <div class="dashboard-card">
        <div class="table-responsive">
            <table class="cart-table" id="historyTable">
                <thead>
                    <tr>
                        <th>Nama</th>
                        <th>Produk</th>
                        <th>Harga</th>
                        <th>Satuan</th>
                        <th>Jumlah</th>
                        <th>Subtotal</th>
                        <th>Tanggal</th>
                    </tr>
                </thead>
                <tbody></tbody>
            </table>
        </div>
    </div>
</div>

<?php

use yii\helpers\Url;

$url = Url::to(['admin/get-history']); // atau Url::to(['get-history']) jika relative controller
$js = <<<JS
let historyData = [];
$('#userSelect').on('change', function(){
    var userId = $(this).val();
    var wrapper = $('#historyWrapper');
    var tbody = $('#historyTable tbody');
    tbody.empty();

    if(!userId){
        wrapper.hide();
        return;
    }

    $.ajax({
        url: '{$url}',
        method: 'GET',
        data: { user_id: userId },
        dataType: 'json',
        success: function(res){
            if(res.success){
                historyData = res.data;

                $("#historyFilter").show();

                renderHistory();

                let tahunSet = new Set();

historyData.forEach(function(row){

    tahunSet.add(
        row.tanggal.split('-')[2]
    );

});

$('#tahunFilter').html(
    '<option value="">Semua Tahun</option>'
);

[...tahunSet]
.sort()
.reverse()
.forEach(function(th){

    $('#tahunFilter').append(
        '<option value="'+th+'">'+th+'</option>'
    );

});
                if(res.data.length){
                    wrapper.show();
                    res.data.forEach(function(row){
                        tbody.append(
                            '<tr>'+
                                '<td>'+ (row.nama || '-') +'</td>'+
                                '<td>'+ (row.produk || '-') +'</td>'+
                                '<td>Rp '+ (row.harga || '-') +'</td>'+
                                '<td>'+ (row.satuan || '-') +'</td>'+
                                '<td>'+ (row.qty || '-') +'</td>'+
                                '<td>Rp '+ (row.subtotal || '-') +'</td>'+
                                '<td>'+ (row.tanggal || '-') +'</td>'+
                            '</tr>'
                        );
                    });
                } else {
                    wrapper.show();
                    tbody.append('<tr><td colspan="7" class="text-center">Belum ada riwayat belanja.</td></tr>');
                }
            } else {
                wrapper.show();
                tbody.append('<tr><td colspan="7" class="text-center">Error: '+ (res.message || 'unknown') +'</td></tr>');
                console.error('Response error:', res);
            }
        },
        error: function(xhr, status, err){
            wrapper.show();
            tbody.append('<tr><td colspan="6" class="text-center">Server error. Cek console/network atau logs.</td></tr>');
            console.error('AJAX error', status, err, xhr.responseText);
            // juga tampilkan isi response jika ada (berguna saat YII_DEBUG = true)
            try { console.log('responseText: ', xhr.responseText); } catch(e){}
        }
    });
});

function renderHistory(){

    let tbody = $('#historyTable tbody');

    tbody.empty();

    let keyword =
        $('#searchHistory').val().toLowerCase();

    let bulan =
        $('#bulanFilter').val();

    let tahun =
        $('#tahunFilter').val();

    let sort =
        $('#sortFilter').val();

    let filtered = historyData.filter(function(row){

        let cocokSearch =
            !keyword ||
            row.nama.toLowerCase().includes(keyword) ||
            row.produk.toLowerCase().includes(keyword);

        let tanggalParts =
            row.tanggal.split('-');

        let rowBulan =
            tanggalParts[1];

        let rowTahun =
            tanggalParts[2];

        let cocokBulan =
            !bulan || rowBulan === bulan;

        let cocokTahun =
            !tahun || rowTahun === tahun;

        return cocokSearch &&
               cocokBulan &&
               cocokTahun;
    });

    filtered.sort(function(a,b){

        let dateA =
            a.tanggal.split('-').reverse().join('');

        let dateB =
            b.tanggal.split('-').reverse().join('');

        return sort === 'asc'
            ? dateA.localeCompare(dateB)
            : dateB.localeCompare(dateA);
    });

    if(!filtered.length){

        tbody.append(
            '<tr><td colspan="7" class="text-center">Data tidak ditemukan.</td></tr>'
        );

        return;
    }

    filtered.forEach(function(row){

        tbody.append(

            '<tr>'+
                '<td>'+row.nama+'</td>'+
                '<td>'+row.produk+'</td>'+
                '<td>Rp '+row.harga+'</td>'+
                '<td>'+row.satuan+'</td>'+
                '<td>'+row.qty+'</td>'+
                '<td>Rp '+row.subtotal+'</td>'+
                '<td>'+row.tanggal+'</td>'+
            '</tr>'
        );

    });
}

$(document).on(
    'keyup',
    '#searchHistory',
    renderHistory
);

$(document).on(
    'change',
    '#bulanFilter',
    renderHistory
);

$(document).on(
    'change',
    '#tahunFilter',
    renderHistory
);

$(document).on(
    'change',
    '#sortFilter',
    renderHistory
);

JS;
$this->registerJs($js);
?>