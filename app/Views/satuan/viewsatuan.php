<?= $this->extend('main/layout')?>
<?= $this->section('judul') ?>
Management Data Satuan
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
      <th style="width: 15%;">Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php $nomor = 1;
    foreach ($tampildata as $row) : ?>
      <tr>
        <td><?= $nomor++; ?></td>
        <td><?= $row['satnama']; ?></td>
        <td>
          <button class="btn btn-warning" onclick="edit_data(<?= $row['satid']; ?>)">Edit</button>
          <button class="btn btn-danger" onclick="delete_data(<?= $row['satid']; ?>)">Delete</button>
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
      <form id="addsatuan" name="addsatuan" method="POST" action="javascript:void(0);">
        <div class="modal-body">

          <input type="hidden" value=0 name="satid" id="satid">

          <div class="form-body">
            <div class="form-group">
              <label class="control-label col-md-3">Nama Satuan</label>
              <div class="col-md-9">
                <input name="satnama" id="satnama" placeholder="Nama satuan" class="form-control" type="text" required>
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

    $('#addsatuan')[0].reset();
    $('#modal_form').modal('show'); // show bootstrap modal
    //$('.modal-title').text('Add Person'); // Set Title to Bootstrap modal title
  }
  $("#addsatuan").submit(function(form) {

    //form.preventDefault();
    console.log($('#addsatuan').serialize());
    $.ajax({
      data: $('#addsatuan').serialize(),
      url: "<?php echo site_url('satuan/store'); ?>",
      type: "GET",
      dataType: 'json',
      success: function(res) {
        console.log(res);
        if (res['status']) {
          $('#addsatuan')[0].reset();
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
        url: "<?php echo site_url('satuan/delete') ?>/" + id,
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
    $('#addsatuan')[0].reset(); // reset form on modals
    <?php header('Content-type: application/json'); ?>
    //Ajax Load data from ajax
    $.ajax({
      url: "<?php echo site_url('satuan/get_data/') ?>/" + id,
      type: "GET",
      dataType: "JSON",
      success: function(data) {
        $('[name="satid"]').val(data.data.satid);
        $('[name="satnama"]').val(data.data.satnama);
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