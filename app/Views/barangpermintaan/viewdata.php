
<?= $this->extend('main/layout') ?>

<?= $this->section('judul') ?>
Data Transaksi Barang Permintaan
<?= $this->endSection() ?>

<?= $this->section('subjudul') ?>
    <button type="button" class="btn btn-primary" onclick="window.location='<?= site_url('barangpermintaan/index') ?>'">
        <i class="fa fa-plus-circle"></i> Input Transaksi
    </button>
<?= $this->endSection() ?>

<?= $this->section('isi') ?>
<div class="card">
    <div class="card-body ">
        <table id="databarangpermintaan" class="table table-bordered table-hover display">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Surat Jalan</th>
                    <th>Tanggal</th>
                    <th>Jumlah item</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $no = 1;
                foreach ($databarang as $row) :
                ?>
                    <tr>
                        <td><?= $no++; ?></td>
                        <td><?= $row['nosuratjalan']; ?></td>
                        <td><?= date('d-m-Y', strtotime($row['tglpermintaan'])) ; ?></td>
                        <td align="center">
                            <?php 
                                $db = \Config\Database::connect();
                                $jumlahItem = $db->table('detail_barangpermintaan')->where('detnosuratjalan', $row['nosuratjalan'])->countAllResults();
                            ?>
                            <span style="cursor: pointer; font-weight: bold; color: blue;" onclick="detailItem('<?= $row['nosuratjalan'] ?>')"><?= $jumlahItem; ?></span>
                        </td>
                        <td>
                            <button type="button" class="btn btn-sm btn-outline-info" title="Edit Transaksi" onclick="edit('<?= sha1($row['nosuratjalan']) ?>')">
                                <i class="fa fa-edit"></i>
                            </button>
                            &nbsp;
                            <button type="button" class="btn btn-sm btn-outline-danger" title="Hapus Transaksi" onclick="hapusTransaksi('<?= $row['nosuratjalan'] ?>')">
                                <i class="fa fa-trash-alt"></i>
                            </button>
                        </td>
                    </tr>
            </tbody>
            <?php endforeach; ?>
        </table>
    </div>
</div>
<div class="viewmodal" style="display: none;"></div>


<?= $this->endSection() ?>
<?= $this->section('javascript') ?>
<script type="text/javascript">
function hapusTransaksi(nosuratjalan){
    Swal.fire({
        title: 'Hapus Transaksi',
        text: "Yakin hapus transaksi ini?",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Yes, Hapus!'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "post",
                url: "/barangpermintaan/hapusTransaksi",
                data: {
                    nosuratjalan : nosuratjalan
                },
                dataType: "json",
                success: function (response) {
                    console.log(response);
                    if (response.sukses) {
                        Swal.fire({
                           icon: 'success',
                           title: 'Berhasil',
                           html: response.sukses
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.reload();
                        }
                    });    
                    }
                },
                error: function(xhr, ajaxOptions, thrownError){
                        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                    }
            });
        }
    })
}

function edit(nosuratjalan){
    window.location.href = ('/barangpermintaan/edit/') + nosuratjalan;
}

function detailItem(nosuratjalan){
    $.ajax({
        type: "post",
        url: "/barangpermintaan/detailItem",
        data: {
            nosuratjalan : nosuratjalan
        },
        dataType: "json",
        success: function (response) {
            if (response.data){
                $('.viewmodal').html(response.data).show();
                $('#modalitem').modal('show');
            }
        },
        error: function(xhr, ajaxOptions, thrownError){
            alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
        }
    });
}

$(document).ready(function () {
    $('#databarangpermintaan').DataTable();
   
});
</script>
    <?= $this->endSection() ?>