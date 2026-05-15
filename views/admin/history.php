<?php

use yii\helpers\Html;

/** @var yii\web\View $this */
/** @var app\models\User[] $users */

$this->title = 'Riwayat Belanja';
?>

<div class="container mt-5">
    <!-- <h3>Riwayat Belanja</h3> -->

    <div class="dashboard-card">
        <h3>Cek Riwayat Belanja</h3>
        <select id="userSelect" class="form-control">
            <option value="">-- Pilih Customer --</option>
            <?php foreach ($users as $user): ?>
                <option value="<?= $user->id ?>"><?= Html::encode($user->username) ?></option>
            <?php endforeach; ?>
        </select>
    </div>

    <div class="dashboard-card">
        <table class="cart-table" id="historyTable">
            <thead>
                <tr>
                    <th>Username</th>
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

<?php

use yii\helpers\Url;

$url = Url::to(['admin/get-history']); // atau Url::to(['get-history']) jika relative controller
$js = <<<JS
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
                if(res.data.length){
                    wrapper.show();
                    res.data.forEach(function(row){
                        tbody.append(
                            '<tr>'+
                                '<td>'+ (row.username || '-') +'</td>'+
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
JS;
$this->registerJs($js);
?>