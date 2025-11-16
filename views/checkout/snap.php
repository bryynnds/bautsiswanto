<script src="https://app.sandbox.midtrans.com/snap/snap.js"
        data-client-key="<?= $clientKey ?>"></script>

<script>
snap.pay('<?= $snapToken ?>', {
    onSuccess: function(result){
        window.location.href = "/site/index";
    },
    onPending: function(result){
        alert("Menunggu pembayaran...");
    },
    onError: function(result){
        alert("Pembayaran gagal!");
    }
});
</script>
