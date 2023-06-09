<?= $this->extend('main/layout') ?>

<?= $this->section('judul') ?>
Cetak Laporan
<?= $this->endSection() ?>

<?= $this->section('subjudul') ?>
<i class="fa fa-file"> Silahkan Pilih Laporan Yang Ingin Dicetak</i>
<?= $this->endSection() ?>

<?= $this->section('isi') ?>
<div class="row">
    <div class="col-lg-4">
        <button type="button" class="btn btn-block btn-lg btn-success" style="padding-top: 50px; padding-bottom: 50px;" onclick="window.location=('/laporan/cetak_barang_masuk')">
            <i class="fa fa-file"> LAPORAN BARANG MASUK</i>
        </button>
    </div>
    <div class="col-lg-4">
    <button type="button" class="btn btn-block btn-lg btn-warning" style="padding-top: 50px; padding-bottom: 50px;" onclick="window.location=('/laporan/cetak_barang_keluar')">
            <i class="fa fa-file"> LAPORAN BARANG KELUAR</i>
        </button>

    </div>
    <div class="col-lg-4">
    <button type="button" class="btn btn-block btn-lg btn-info" style="padding-top: 50px; padding-bottom: 50px;" onclick="window.location=('/laporan/stokakhir')">
            <i class="fa fa-file"> LAPORAN STOK AKHIR</i>
        </button>

    </div>
    </div>
</div>


<?= $this->endSection() ?>