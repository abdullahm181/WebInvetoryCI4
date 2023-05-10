<?= $this->extend('main/layout') ?>


<?= $this->section('judul') ?>
Dashboard
<?= $this->endSection() ?>

<?= $this->section('subjudul') ?>

<?= $this->endSection() ?>

<?= $this->section('isi') ?>
<div class="row">
    <div class="col">
        <div class="form-group">
            <label for="">Pilih Bulan</label>
            <input type="month" class="form-control" id="bulan" value="<?= date('Y-m') ?>"><br>
            <button type="button" class="btn btn-sm btn-primary" id="tombolTampil">
                Tampil
            </button>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-6">
        <div class="card text-white bg-primary mb-3">
            <div class="card-header">Grafik Barang Masuk</div>
            <div class="card-body bg-white">
                <div id="container-masuk">
                    <canvas id="viewGrafikMasuk" style="height: 50vh; width: 100%;"></canvas>
                </div>

            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card text-white bg-primary mb-3">
            <div class="card-header">Grafik Barang Keluar</div>
            <div class="card-body bg-white">
                <div id="container-keluar">
                    <canvas id="viewGrafikKeluar" style="height: 50vh; width: 100%;"></canvas>
                </div>

            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12">
        <div class="card text-white bg-primary mb-3">
            <div class="card-header">List Barang Butuh di ReStock</div>
            <div class="card-body bg-white">
                <?php if (session()->userlevelid == 4 || session()->userlevelid == 1) : ?>
                    <button type="button" class="btn btn-block btn-primary" id="tombolBuatPermintaan">Buat Permintaan Barang</button>

                    <br>
                <?php endif; ?>
                <table style="width: 100%;" id="barangrestock" class="table table-bordered table-hover dataTable dtr-inline collapsed">
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
    var dynamicColors = function() {
        var r = Math.floor(Math.random() * 255);
        var g = Math.floor(Math.random() * 255);
        var b = Math.floor(Math.random() * 255);
        return "rgb(" + r + "," + g + "," + b + ")";
    };

    function getcolor(data) {

        coloR = [];
        for (var i in data) {
            coloR.push(dynamicColors());
        }
        return coloR;
    }

    function listdatabarangrestock() {
        var table = $('#barangrestock').DataTable({
            destroy: true,
            "processing": true,
            "serverSide": false,
            "order": [],
            "ajax": {
                "url": "/main/listdatabarangrestock",
                "type": "POST",
            },
            "columnDefs": [{
                "targets": [0, 6],
                "orderable": false,
            }, ],
        });
    }

    function tampilGrafik() {
        $('#viewGrafikMasuk').remove();
        $('#viewGrafikKeluar').remove();
        $('#container-masuk').append(`<canvas id="viewGrafikMasuk" style="height: 50vh; width: 100%;"></canvas>`);
        $('#container-keluar').append(`<canvas id="viewGrafikKeluar" style="height: 50vh; width: 100%;"></canvas>`);
        var ctxmasuk = document.getElementById('viewGrafikMasuk').getContext('2d');
        var ctxkeluar = document.getElementById('viewGrafikKeluar').getContext('2d');
        $.ajax({
            type: "post",
            url: "/main/tampilGrafikMasuk",
            data: {
                bulan: $('#bulan').val()
            },
            dataType: "json",
            beforeSend: function() {
                $('#viewGrafikMasuk').html('<i class="fa fa-spinner fa-spin"></i>');
            },
            success: function(response) {
                var chartmasuk = new Chart(ctxmasuk, {
                    type: 'bar',
                    reponsive: true,
                    data: {
                        labels: response.grafiklabel,
                        datasets: [{

                            backgroundColor: getcolor(response.grafiklabel),
                            borderColor: ['rgb(255,991,130)'],
                            data: response.grafikdata,
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        plugins: {
                            legend: false // Hide legend
                        },
                        scales: {
                            y: {
                                display: false // Hide Y axis labels
                            },
                            x: {
                                display: false // Hide X axis labels
                            }
                        }
                    },
                    duration: 1000
                });

            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
        $.ajax({
            type: "post",
            url: "/main/tampilGrafikKeluar",
            data: {
                bulan: $('#bulan').val()
            },
            dataType: "json",
            beforeSend: function() {
                $('#GrafikKeluar').html('<i class="fa fa-spinner fa-spin"></i>');
            },
            success: function(response) {
                var chartkeluar = new Chart(ctxkeluar, {
                    type: 'bar',
                    reponsive: true,
                    data: {
                        labels: response.grafiklabel,
                        datasets: [{

                            backgroundColor: getcolor(response.grafiklabel),
                            borderColor: ['rgb(255,991,130)'],
                            data: response.grafikdata,
                        }]
                    },
                    options: {
                        maintainAspectRatio: false,
                        plugins: {
                            legend: false // Hide legend
                        },
                        scales: {
                            y: {
                                display: false // Hide Y axis labels
                            },
                            x: {
                                display: false // Hide X axis labels
                            }
                        }
                    },
                    duration: 1000
                });
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });

    }
    $(document).ready(function() {


        tampilGrafik();
        listdatabarangrestock();

        $('#tombolTampil').click(function(e) {
            e.preventDefault();
            tampilGrafik();
        });
        $('#tombolBuatPermintaan').click(function(e) {
            e.preventDefault();
            window.location = "<?= base_url(); ?>main/RequestBarang";
        });

    });
</script>
<?= $this->endSection() ?>