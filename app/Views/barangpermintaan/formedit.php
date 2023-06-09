<?= $this->extend('main/layout') ?>

<?= $this->section('judul') ?>
Edit Barang Permintaan
<?= $this->endSection() ?>

<?= $this->section('subjudul') ?>

<button type="button" class="btn btn-sm btn-warning" onclick="window.location='<?= site_url('barangpermintaan/data') ?>'">
    <i class="fa fa-backward"></i> Kembali
</button>

<?= $this->endSection() ?>

<?= $this->section('isi') ?>
<table class="table table-sm table-striped table-hover" style="width: 100%;">
    <tr>
        <td style="width: 20%;">No Surat Jalan</td>
        <td style="width: 2%;">:</td>
        <td style="width: 28%;"><?= $nonosuratjalan ?></td>
        </td>
        <input type="hidden" id="nosuratjalan" value="<?= $nonosuratjalan; ?>">
    </tr>
    <tr>
        <td style="width: 20%;">Tanggal Permintaan</td>
        <td style="width: 2%;">:</td>
        <td style="width: 28%;"><?= date('d-m-Y', strtotime($tanggal)) ; ?></td>
    </tr>
</table>
<div class="card">
  <div class="card-header bg-primary">
    Input Barang
  </div>
  <div class="card-body">
    <div class="form-row">
        <div class="form-group col-md-3">
            <label for="">Kode Barang</label>
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Kode Barang" name="kdbarang" id="kdbarang">
                <div class="input-group-append">
                    <button class="btn btn-outline-primary" type="button" id="tombolCariBarang"><i class="fa fa-search"></i></button>
                </div>
            </div>
            <input type="hidden" id="iddetail" name="iddetail">
        </div>
        <div class="form-group col-md-3">
            <label for="">Nama Barang</label>
            <input type="text" class="form-control" name="namabarang" id="namabarang" readonly>
        </div>
        <div class="form-group col-md-2">
            <label for="">Harga Jual</label>
            <input type="text" class="form-control" name="hargajual" id="hargajual" readonly>
        </div>
        <div class="form-group col-md-1">
            <label for="">Jumlah</label>
            <input type="number" class="form-control" name="jumlah" id="jumlah">
        </div>
        <div class="form-group col-md-1">
            <label for="">Aksi</label>
            <div class="input-group">
            <button type="button" class="btn btn-sm btn-info" title="Tambah Item" id="tombolTambahItem">
                <i class="fa fa-plus-square"></i>
            </button>
            <button type="button" style="display: none;" class="btn btn-sm btn-primary" title="Edit Item" id="tombolEditItem">
                <i class="fa fa-edit"></i>
            </button>
            &nbsp;
            <button type="button" style="display: none;" class="btn btn-sm btn-secondary" title="Reload" id="tombolReload">
                <i class="fa fa-sync-alt"></i>
            </button>
            </div> 
        </div>
    </div>

    <div class="row" id="tampilDataDetail"></div>
  </div>
</div>
<div class="modalcaribarang" style="display: none;"></div>

<?= $this->endSection() ?>
<?= $this->section('javascript') ?>
<script type="text/javascript">
    function dataDetail(){
    let nosuratjalan = $('#nosuratjalan').val();

    $.ajax({
        type: "post",
        url: "/barangpermintaan/dataDetail",
        data: {
            nosuratjalan : nosuratjalan
        },
        dataType: "json",
        success: function (response) {
            if (response.data){
                $('#tampilDataDetail').html(response.data);
            }
        },
        error: function(xhr, ajaxOptions, thrownError){
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
    });
}

function kosong(){
    $('#kdbarang').val('');
    $('#namabarang').val('');
    $('#hargajual').val('');
    $('#jumlah').val('');
    $('#kdbarang').focus();
    $('#tombolEditItem').hide();
    $('#tombolReload').hide();
    $('#tombolTambahItem').fadeIn();
}

function ambilDataBarang(){
    let kodebarang = $('#kdbarang').val();

        $.ajax({
            type: "post",
            url: "/barangpermintaan/ambilDataBarang",
            data: {
                kodebarang : kodebarang
            },
            dataType: "json",
            success: function (response) {
                if (response.sukses){
                    let data = response.sukses;
                    $('#namabarang').val(data.namabarang);
                    $('#hargajual').val(data.hargajual);
                    $('#jumlah').focus();
                }
                if (response.error){
                    alert(response.error);
                    kosong();
                }
            },
            error: function(xhr, ajaxOptions, thrownError){
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
        });
}

$(document).ready(function () {
    dataDetail();

    $('#tombolReload').click(function (e) { 
        e.preventDefault();
        $('#iddetail').val('');
        $(this).hide();
        $('#tombolEditItem').hide();
        $('#tombolTambahItem').fadeIn();

        kosong();
    });

    $('#tombolTambahItem').click(function (e) { 
        e.preventDefault();
        let nosuratjalan = $('#nosuratjalan').val();
        let kodebarang = $('#kdbarang').val();
        let jumlah = $('#jumlah').val();
        let hargajual = $('#hargajual').val();

        if (nosuratjalan.length == 0) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Maaf, Faktur tidak boleh kosong'
            })
        }else if(kodebarang.length == 0){
            alert('Maaf, kodebarang wajib diisi');
        }else if(jumlah.length == 0){
            alert('Maaf, jumlah wajib diisi');
        }else{
            $.ajax({
                type: "post",
                url: "/barangpermintaan/simpanDetail",
                data: {
                    nosuratjalan : nosuratjalan,
                    kodebarang : kodebarang,
                    hargajual : hargajual,
                    jumlah : jumlah
                },
                dataType: "json",
                success: function (response) {
                    if (response.sukses){
                        alert (response.sukses);
                        kosong();
                        dataDetail();
                    }
                },
                error: function(xhr, ajaxOptions, thrownError){
                        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                    }
            });
        }
    });

    $('#tombolEditItem').click(function (e) { 
        e.preventDefault();
        let nosuratjalan = $('#nosuratjalan').val();
        let kodebarang = $('#kdbarang').val();
        let jumlah = $('#jumlah').val();
        let hargajual = $('#hargajual').val();

        $.ajax({
            type: "post",
            url: "/barangpermintaan/updateItem",
            data: {
                iddetail : $('#iddetail').val(),
                nosuratjalan : nosuratjalan,
                kodebarang : kodebarang,
                hargajual : hargajual,
                jumlah : jumlah
            },
            dataType: "json",
            success: function (response) {
                if (response.sukses){
                    alert (response.sukses);
                    kosong();
                    dataDetail();
                }
            },
            error: function(xhr, ajaxOptions, thrownError){
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    });

    $('#tombolCariBarang').click(function (e) { 
        e.preventDefault();
        $.ajax({
            url: "/barangpermintaan/cariDataBarang",
            dataType: "json",
            success: function (response) {
                if (response.data){
                    $('.modalcaribarang').html(response.data).show();
                    $('#modalcaribarang').modal('show');
                }
            },
            error: function(xhr, ajaxOptions, thrownError){
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
        });
    });
});
</script>
<?= $this->endSection() ?>
