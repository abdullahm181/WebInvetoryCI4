<?= $this->extend('main/layout') ?>

<?= $this->section('judul') ?>
Laporan Stok Akhir
<?= $this->endSection() ?>

<?= $this->section('subjudul') ?>
<button type="button" class="btn btn-warning" onclick="window.location=('/laporan/index')">Kembali</button>
<?= $this->endSection() ?>

<?= $this->section('isi') ?>
<div class="row">
    <div class="col-lg-12">
        <div class="card text-white bg-primary mb-3">
            <div class="card-header">Laporan Grafik Stok</div>
            <div class="card-body bg-white">
                <div class="viewTampilGrafik">

                </div>
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card text-white bg-primary mb-3">
            <div class="card-header">Tabel Stok</div>
            <div class="card-body bg-white">

                <table style="width: 100%;" id="datdetailstok" class="table table-bordered table-hover dataTable dtr-inline collapsed">
                    <thead>
                        <tr>
                        <th>No</th>
                            <th>Nama Barang</th>
                            <th>Kode Barang</th>
                            <th>Stok</th>
                            <th>EOQ</th>
                            <th>Safety Stock</th>
                            <th>Reorder Point</th>
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
            url: "/laporan/tampilGrafikStokAkhir",
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

    function listdatastokakhir() {
        var table = $('#datdetailstok').DataTable({
            destroy: true,
            "processing": true,
            "serverSide": false,
            "order": [],
            "ajax": {
                "url": "/laporan/listdatastokakhir",
                "type": "POST"
            },
            "columnDefs": [{
                "targets": [0, 6],
                "orderable": false,
            }, ],
        });
    }
    $(document).ready(function() {
        tampilGrafik();
        listdatastokakhir();

    });
</script>
<?= $this->endSection() ?>