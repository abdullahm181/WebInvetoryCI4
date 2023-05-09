<?= $this->extend('main/layout') ?>

<?= $this->section('judul') ?>
Laporan Barang Keluar
<?= $this->endSection() ?>

<?= $this->section('subjudul') ?>
<button type="button" class="btn btn-warning" onclick="window.location=('/laporan/index')">Kembali</button>
<?= $this->endSection() ?>

<?= $this->section('isi') ?>
<div class="row">
    <div class="col-lg-4">
        <div class="card text-white bg-primary mb-3">
            <div class="card-header">Pilih Periode</div>
            <div class="card-body bg-white">
                <p class="card-text">
                <form action="<?= site_url('laporan/cetak_barang_keluar_periode') ?>" method="POST">
                    <div class="form-group">
                        <label for="">Tanggal Awal</label>
                        <input type="date" name="tglawal" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <label for="">Tanggal Akhir</label>
                        <input type="date" name="tglakhir" class="form-control" required>
                    </div>
                    <div class="form-group">
                        <button type="submit" name="btnCetak" class="btn btn-block btn-success">
                            <i class="fa fa-print"></i> Cetak Laporan
                        </button>
                    </div>
                </form>
                </p>
            </div>
        </div>
    </div>
    <div class="col-lg-8">
        <div class="card text-white bg-primary mb-3">
            <div class="card-header">Laporan Grafik</div>
            <div class="card-body bg-white">
                <div class="form-group">
                    <label for="">Pilih Bulan</label>
                    <input type="month" class="form-control" id="bulan" value="<?= date('Y-m') ?>"><br>
                    <button type="button" class="btn btn-sm btn-primary" id="tombolTampil">
                        Tampil
                    </button>
                </div>
                <div class="viewTampilGrafik">

                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card text-white bg-primary mb-3">
            <div class="card-header">Laporan Detail Barang Keluar</div>
            <div class="card-body bg-white">

            <div class="row">
    <div class="col">
        <label for="">Filter Data</label>
    </div>
    <div class="col">
        <input type="date" name="tglawaldetail" id="tglawaldetail" class="form-control">
    </div>
    <div class="col">
        <input type="date" name="tglakhirdetail" id="tglakhirdetail" class="form-control">
    </div>
    <div class="col">
        <button type="button" class="btn btn-block btn-primary" id="tombolTampilDetail"> Tampilkan</button>
    </div>
</div>
<br>
<table style="width: 100%;" id="datdetailbarangkeluar" class="table table-bordered table-hover dataTable dtr-inline collapsed">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Barang</th>
            <th>Kode Barang</th>
            <th>Tanggal Masuk</th>
            <th>Jumlah</th>
            <th>Input By</th>
        </tr>
    </thead>
    <tbody>

    </tbody>
</table>
            </div>
        </div>
    </div>
</div>


<?= $this->endSection() ?>
<?= $this->section('javascript') ?>
<script type="text/javascript">
    function tampilGrafik() {
        $.ajax({
            type: "post",
            url: "/laporan/tampilGrafikBarangKeluar",
            data: {
                bulan: $('#bulan').val()
            },
            dataType: "json",
            beforeSend: function() {
                $('.viewTampilGrafik').html('<i class="fa fa-spinner fa-spin"></i>');
            },
            success: function(response) {
                if (response.data) {
                    $('.viewTampilGrafik').html(response.data);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    }
    function listDataDetailBarangKeluar() {
        var table = $('#datdetailbarangkeluar').DataTable({
            destroy: true,
            "processing": true,
            "serverSide": false,
            "order": [],
            "ajax": {
                "url": "/laporan/listDataDetailBarangKeluar",
                "type": "POST",
                "data": {
                    tglawal: $('#tglawaldetail').val(),
                    tglakhir: $('#tglakhirdetail').val(),
                },
            },
            "columnDefs": [{
                "targets": [0, 5],
                "orderable": false,
            }, ],
        });
    }
    $(document).ready(function() {
        tampilGrafik();

        $('#tombolTampil').click(function(e) {
            e.preventDefault();
            tampilGrafik();
        });
        $('#tombolTampilDetail').click(function(e) {
            e.preventDefault();

            listDataDetailBarangKeluar();
        });
    });
</script>
<?= $this->endSection() ?>