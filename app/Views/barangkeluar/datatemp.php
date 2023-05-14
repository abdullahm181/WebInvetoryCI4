<table class="table table-sm table-hover table-bordered" style="width: 100%;">
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
                <td><?= $no++; ?></td>
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
                url: "/barangkeluar/hapusItem",
                data: {
                    id: id
                },
                dataType: "json",
                success: function(response) {
                    if (response.sukses) {
                        Swal.fire({
                           icon: 'Berhasil',
                           title: 'Berhasil',
                    }); 
                        tampilDataTemp();
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
</script>