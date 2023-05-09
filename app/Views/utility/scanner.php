<?= $this->extend('main/layout') ?>

<?= $this->section('judul') ?>
Scanner BarCode ( Informasi Barang )
<?= $this->endSection() ?>

<?= $this->section('subjudul') ?>

<?= $this->endSection() ?>

<?= $this->section('isi') ?>
<!-- QR SCANNER CODE BELOW  -->
<div class="row">
  <div class="col">
    <div id="reader" class="my-auto mx-auto"></div>
  </div>
  <div class="col" style="padding: 30px">
    <div class="form-row">
      <div class="col">
        <h4>Scan Result </h4>
        <div id="result">
          Result goes here
        </div>
      </div>
    </div>
    <div class="form-group ">
        <label for="">Kode Barang</label>
        <div class="input-group mb-3">
          <input type="text" class="form-control" placeholder="Kode Barang" name="kdbarang" id="kdbarang" readonly>
        </div>
      </div>
      <div class="form-group ">
        <label for="">Nama Barang</label>
        <input type="text" class="form-control" name="namabarang" id="namabarang" readonly>
      </div>
      <div class="form-group ">
        <label for="">Harga Jual</label>
        <input type="text" class="form-control" name="hargajual" id="hargajual" readonly>
      </div>
      <div class="form-group ">
        <label for="">Stok</label>
        <input type="number" class="form-control" name="brgstok" id="brgstok" readonly>
      </div>
      <div class="form-group ">
        <label for="">Lokasi</label>
        <input type="text" class="form-control" name="brglokasi" id="brglokasi" readonly>
      </div>
      <div class="form-group ">
        <label for="">Gambar</label>
        <div id="brggambar"></div>
      </div>
  </div>

</div>

<?= $this->endSection() ?>
<?= $this->section('javascript') ?>
<script type="text/javascript">
  function ambilDataBarang() {
        let kodebarang = $('#kdbarang').val();

        $.ajax({
            type: "post",
            url: "/utility/ambilDataBarang",
            data: {
                kodebarang: kodebarang
            },
            dataType: "json",
            success: function(response) {
                if (response.sukses) {
                    let data = response.sukses;
                    $('#namabarang').val(data.namabarang);
                    $('#hargajual').val(data.hargajual);
                    $('#brgstok').val(data.brgstok);
                    $('#brglokasi').val(data.brglokasi)
                    if(data.brggambar!='' ||data.brggambar!=null){
                      $('#brggambar').html(`<img src="../uploads/${data.brggambar}" width="auto" height="80"/>`);
                    }
                    
                }
                if (response.error) {
                    alert(response.error);
                    kosong();
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    }
  $(document).ready(function() {
    // When scan is successful fucntion will produce data
    function onScanSuccess(qrCodeMessage) {
      $('#kdbarang').val('qrCodeMessage');
      ambilDataBarang();
      document.getElementById("result").innerHTML =
        '<span class="result">' + qrCodeMessage + "</span>";
    }

    // When scan is unsuccessful fucntion will produce error message
    function onScanError(errorMessage) {
      // Handle Scan Error
    }

    // Setting up Qr Scanner properties
    var html5QrCodeScanner = new Html5QrcodeScanner("reader", {
      fps: 10,
      qrbox: 250
    });

    // in
    html5QrCodeScanner.render(onScanSuccess, onScanError);
  });
</script>
<?= $this->endSection() ?>