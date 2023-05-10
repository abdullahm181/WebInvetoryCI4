<?= $this->extend('main/layout') ?>
<?= $this->section('judul') ?>
Management Data EOQ Barang
<?= $this->endsection('judul') ?>

<?= $this->section('subjudul') ?>

<?= form_button('', '<i class="fa fa-plus-circle"></i> Tambah Data', [
  'class' => 'btn btn-primary',
  'onclick' => "add_data()"
]) ?>

<?= $this->endsection('subjudul') ?>


<?= $this->section('isi') ?>



<table id="table_id" class="table table-striped table-bordered" cellspacing="0" width="100%">
  <thead>
    <tr>
      <th style="width: 5%;">No</th>
      <th>Nama</th>
      <th>Kode</th>
      <th>Harga</th>
      <th>Jumlah Kebutuhan Tahunan</th>
      <th>Biaya Pemesanan</th>
      <th>Biaya Penyimpanan</th>
      <th>Penjualan Tertinggi Harian</th>
      <th>Lead Time Telama</th>
      <th>Rerata Penjualan Harian</th>
      <th>Rerata Lead Time</th>
      <th style="width: 15%;">Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php $nomor = 1;
    foreach ($tampildata as $row) : ?>
      <tr>
        <td><?= $nomor++; ?></td>
        <td><?= $row['brgnama']; ?></td>
        <td><?= $row['brgkode']; ?></td>
        <td><?= $row['brgharga']; ?></td>
        <td><?= $row['jumlahkebutuhantahun']; ?></td>
        <td><?= $row['biayapesan']; ?></td>
        <td><?= $row['biayapenyimpanan']; ?></td>
        <td><?= $row['penjualantertinggiharian']; ?></td>
        <td><?= $row['leadtimeterlama']; ?></td>
        <td><?= $row['ratapenjualanharian']; ?></td>
        <td><?= $row['rataleadtime']; ?></td>
        <td>
          <button class="btn btn-warning" onclick="edit_data(<?= $row['brgid']; ?>)">Edit</button>
        </td>
      </tr>

    <?php endforeach; ?>
  </tbody>
</table>

<?= $this->endsection('isi') ?>

<?= $this->section('modal') ?>
<!-- Bootstrap modal -->
<!-- Modal -->
<div class="modal fade" id="modal_form" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <form id="addBarang" name="addBarang" method="POST" action="javascript:void(0);">
        <div class="modal-body">
          <input type="hidden" value=0 name="brgid" id="brgid">

          <div class="form-body">
            <div class="form-group">
              <label class="control-label ">Kode Barang</label>
              <input name="brgkode" id="brgkode" placeholder="Kode Barang" class="form-control" type="text" readonly>
            </div>
            <div class="form-group">
              <label class="control-label ">Nama Barang</label>
              <input name="brgnama" id="brgnama" placeholder="Nama Barang" class="form-control" type="text" readonly>
            </div>

            <div class="form-group">
              <label class="control-label ">Harga</label>
              <input type="number" class="form-control" id="brgharga" name="brgharga" value=0 required>
            </div>
            <div class="form-group">
              <label class="control-label ">Jumlah Kebutuhan Tahunan</label>
              <input type="number" class="form-control" id="jumlahkebutuhantahun" name="jumlahkebutuhantahun" value=0 required>
            </div>
            <div class="form-group">
              <label class="control-label ">Biaya Pemesanan</label>
              <input type="number" class="form-control" id="biayapesan" name="biayapesan" value=0 required>
            </div>
            <div class="form-group">
              <label class="control-label ">Biaya Penyimpanan</label>
              <input  type="number" step="0.0001" class="form-control" id="biayapenyimpanan" name="biayapenyimpanan" value=0 required>
              <small class="form-text text-muted">Masukan dalam bilangan pecahan yang mewakilkan persen  (2,50% menjadi 0,025)</small>
            </div>
            <div class="form-group">
              <label class="control-label ">Penjualan Tertinggi Harian</label>
              <input type="number" class="form-control" id="penjualantertinggiharian" name="penjualantertinggiharian" value=0 required>
            </div>
            <div class="form-group">
              <label class="control-label ">Lead Time Terlama</label>
              <input type="number" class="form-control" id="leadtimeterlama" name="leadtimeterlama" value=0 required>
            </div>
            <div class="form-group">
              <label class="control-label ">Rata-rata Penjualan Harian</label>
              <input type="number" class="form-control" id="ratapenjualanharian" name="ratapenjualanharian" value=0 required>
            </div>
            <div class="form-group">
              <label class="control-label ">Rata-rata Leadtime</label>
              <input type="number" class="form-control" id="rataleadtime" name="rataleadtime" value=0 required>
            </div>

          </div>
          <div class="modal-footer">
            <input type="submit" value="Save" class="btn btn-primary" />
            <button type="button" class="btn btn-danger" data-dismiss="modal">Cancel</button>
          </div>
      </form>
    </div>
  </div>
</div>

<!-- End Bootstrap modal -->
<?= $this->endsection('modal') ?>

<?= $this->section('javascript') ?>
<script type="text/javascript">
  $(document).ready(function() {
    var datatabel = $('#table_id').DataTable();
  });

  $("#addBarang").submit(function(form) {

    //form.preventDefault();
    console.log($('#addBarang').serialize());
    $.ajax({
      url: "<?php echo site_url('EOQ/data_update'); ?>",
      method: "POST",
      data: new FormData(this),
      processData: false,
      contentType: false,
      cache: false,
      dataType: "json",
      success: function(res) {
        console.log(res);
        if (res['status']) {
          $('#addBarang')[0].reset();
          $('#modal_form').modal('hide');
          location.reload();

        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error! the process edit get  error ',
            showConfirmButton: true,
            timer: 1500
          });

        }


      },
      error: function(data) {
        console.log(data);
      }
    });


  });

  function edit_data(id) {
    $('#addBarang')[0].reset(); // reset form on modals
    <?php header('Content-type: application/json'); ?>
    //Ajax Load data from ajax
    $.ajax({
      url: "<?php echo site_url('barang/get_data/') ?>/" + id,
      type: "GET",
      dataType: "JSON",
      success: function(data) {

        $('#brgstok').prop('readonly', true);

        $('[name="brgid"]').val(data.data.brgid);
        $('[name="brgnama"]').val(data.data.brgnama);
        $('[name="brgkode"]').val(data.data.brgkode);
        $('[name="brgharga"]').val(data.data.brgharga);
        $('[name="jumlahkebutuhantahun"]').val(data.data.jumlahkebutuhantahun);
        $('[name="biayapesan"]').val(data.data.biayapesan);
        $('[name="biayapenyimpanan"]').val(data.data.biayapenyimpanan);
        $('[name="penjualantertinggiharian"]').val(data.data.penjualantertinggiharian);
        $('[name="leadtimeterlama"]').val(data.data.leadtimeterlama);
        $('[name="ratapenjualanharian"]').val(data.data.ratapenjualanharian);
        $('[name="rataleadtime"]').val(data.data.rataleadtime);

        $('#modal_form').modal('show'); // show bootstrap modal when complete loaded
        $('.modal-title').text('Edit data'); // Set title to Bootstrap modal title
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.log(jqXHR);
        alert('Error get data from ajax');
      }
    });
  }
</script>
<?= $this->endsection('javascript') ?>