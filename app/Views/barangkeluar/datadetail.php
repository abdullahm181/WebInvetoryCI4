<table class="table table-sm table-hover table-bordered" style="width: 100%;" id="datadetail">
    <thead>
        <tr>
            <th colspan="5"></th>
            <th colspan="2">
            </th>
        </tr>
    </thead>
    <thead>
        <tr>
            <th>No</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Jumlah</th>
            <th>#</th>
        </tr>
    </thead>
    <tbody>
        <?php $no = 1;
        foreach ($tampildata->getResultArray() as $row) :
        ?>
            <tr>
                <td>
                    <?= $no++; ?>
                    <input type="hidden" value="<?= $row['id'] ?>" name="id">
                </td>
                <td><?= $row['detbrgkode'] ?></td>
                <td><?= $row['brgnama'] ?></td>
                <td style="text-align: right;"><?= number_format($row['detjml'], 0,",",".") ?></td>
                <td style="text-align: right;">
                    <button type="button" class="btn btn-sm btn-danger" onclick="hapusItem('<?= $row['id'] ?>')">
                        <i class="fa fa-trash-alt"></i>
                    </button>
                </td>
            </tr>
        <?php endforeach; ?>
    </tbody>
</table>
<script>
function hapusItem(id){
    Swal.fire({
        title: 'Hapus Item',
        html: `Ya Hapus Item Ini ?`,
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Ya, Hapus !',
        cancelButtonText: 'Tidak'
    }).then((result) => {
        if (result.isConfirmed) {
            $.ajax({
                type: "post",
                url: "/barangkeluar/hapusItemDetail",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(response) {
                    if (response.sukses) {
                        Swal.fire({
                           icon: 'Berhasil',
                           title: 'Berhasil menghapus',
                    
                    }); 
                        tampilDataDetail();
                        kosong();  
                    }
                },
                error: function(xhr, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }
    })
}

$('#datadetail tbody').on('click', 'tr', function() {
    let row = $(this).closest('tr');
    let kodebarang = row.find('td:eq(1)').text();
    let jml = row.find('td:eq(3)').text();
    let id = row.find('td input').val();
    $('#iddetail').val(id);
    $('#kodebarang').val(kodebarang);
    $('#jml').val(jml);

    $('#tombolBatal').fadeIn();
    $('#tombolEditItem').fadeIn();
    $('#kodebarang').prop('readonly', true);
    $('#tombolCariBarang').prop('disabled', true);
    $('#tombolSimpanItem').fadeOut();
    ambilDataBarang();
});

$(document).on('click','#tombolBatal', function(e){
    e.preventDefault();
    kosong();
    tampilDataDetail();
    $('#kodebarang').prop('readonly', false);
    $('#tombolCariBarang').prop('disabled', false);
    $('#tombolSimpanItem').fadeIn();
    $('#tombolEditItem').fadeOut();
    $('#tombolBatal').fadeOut();
});
</script>