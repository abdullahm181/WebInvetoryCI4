<?= $this->extend('main/layout') ?>
<?= $this->section('judul') ?>
Management Data Lokasi
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
      <th>Lorong</th>
      <th>Rak</th>
      <th>Kode</th>
      <th style="width: 15%;">Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php $nomor = 1;
    foreach ($tampildata as $row) : ?>
      <tr>
        <td><?= $nomor++; ?></td>
        <td><?= $row['loklorong']; ?></td>
        <td><?= $row['lokrak']; ?></td>
        <td><?= $row['lokkode']; ?></td>
        <td>
          <button class="btn btn-warning" onclick="edit_data(<?= $row['lokid']; ?>)">Edit</button>
          <button class="btn btn-danger" onclick="delete_data(<?= $row['lokid']; ?>)">Delete</button>
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
      <form id="addLokasi" name="addLokasi" method="POST" action="javascript:void(0);">
        <div class="modal-body">

          <input type="hidden" value=0 name="lokid" id="lokid">

          <div class="form-body">
            <div class="form-group">
              <label class="control-label col-md-3">Nama Lorong</label>
              <div class="col-md-9">
                <input name="loklorong" id="loklorong" placeholder="Nama Lorong" class="form-control" type="text" onkeyup="  var start = this.selectionStart;  var end = this.selectionEnd;  this.value = this.value.toUpperCase();  this.setSelectionRange(start, end);" required>
                <small class="form-text text-muted">Masukan nama lorong tanpa spasi,dan diakhiri dengan dua digit nomor (1 menjadi 01)</small>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Nama Rak</label>
              <div class="col-md-9">
                <input name="lokrak" id="lokrak" placeholder="Nama Rak" class="form-control" type="text" onkeyup="  var start = this.selectionStart;  var end = this.selectionEnd;  this.value = this.value.toUpperCase();  this.setSelectionRange(start, end);" required>
                <small class="form-text text-muted">Masukan nama rak tanpa spasi,dan diakhiri dengan dua digit nomor (1 menjadi 01)</small>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Kode</label>
              <div class="col-md-9">
                <input name="lokkode" id="lokkode" placeholder="Kode" class="form-control" type="text" onkeyup="  var start = this.selectionStart;  var end = this.selectionEnd;  this.value = this.value.toUpperCase();  this.setSelectionRange(start, end);" required>
                <small class="form-text text-muted">Masukan Kode tanpa spasi,dan diakhiri dengan dua digit nomor (1 menjadi 01)</small>
              </div>
            </div>
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

  function add_data() {

    $('#addLokasi')[0].reset();
    $('#modal_form').modal('show'); // show bootstrap modal
    //$('.modal-title').text('Add Person'); // Set Title to Bootstrap modal title
  }
  $("#addLokasi").submit(function(form) {

    //form.preventDefault();
    console.log($('#addLokasi').serialize());
    $.ajax({
      data: $('#addLokasi').serialize(),
      url: "<?php echo site_url('lokasi/store'); ?>",
      type: "GET",
      dataType: 'json',
      success: function(res) {
        console.log(res);
        if (res['status']) {
          $('#addLokasi')[0].reset();
          $('#modal_form').modal('hide');
          location.reload();

        } else {
          Swal.fire({
            icon: 'error',
            title: 'Error! The data cant be ' + res['pesan'],
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

  function delete_data(id) {
    if (confirm('Are you sure delete this data?')) {
      // ajax delete data from database
      $.ajax({
        url: "<?php echo site_url('lokasi/delete') ?>/" + id,
        type: "POST",
        dataType: "JSON",
        success: function(data) {
          location.reload();
        },
        error: function(jqXHR, textStatus, errorThrown) {
          alert('Error deleting data');
        }
      });
    }
  }

  function edit_data(id) {
    $('#addLokasi')[0].reset(); // reset form on modals
    <?php header('Content-type: application/json'); ?>
    //Ajax Load data from ajax
    $.ajax({
      url: "<?php echo site_url('lokasi/get_data/') ?>/" + id,
      type: "GET",
      dataType: "JSON",
      success: function(data) {
        $('[name="lokid"]').val(data.data.lokid);
        $('[name="loklorong"]').val(data.data.loklorong);
        $('[name="lokrak"]').val(data.data.lokrak);
        $('[name="lokkode"]').val(data.data.lokkode);
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