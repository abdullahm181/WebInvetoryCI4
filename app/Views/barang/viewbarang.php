<?= $this->extend('main/layout') ?>
<?= $this->section('judul') ?>
Management Data Barang
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
      <th>Gambar</th>
      <th>Nama</th>
      <th>Kategori</th>
      <th>Satauan</th>
      <th>Kode</th>
      <th>Harga</th>
      <th>Stok</th>
      <th style="width: 15%;">Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php $nomor = 1;
    foreach ($tampildata as $row) : ?>
      <tr>
        <td><?= $nomor++; ?></td>
        <td><?= $row['brggambar']; ?></td>
        <td><?= $row['brgnama']; ?></td>
        <td><?= $row['brgkatid']; ?></td>
        <td><?= $row['brgsatid']; ?></td>
        <td><?= $row['brgkode']; ?></td>
        <td><?= $row['brgharga']; ?></td>
        <td><?= $row['brgstok']; ?></td>
        <td>
          <button class="btn btn-warning" onclick="edit_data(<?= $row['brgid']; ?>)">Edit</button>
          <button class="btn btn-danger" onclick="delete_data(<?= $row['brgid']; ?>)">Delete</button>
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
              <label class="control-label col-md-3">Nama Barang</label>
              <div class="col-md-9">
                <input name="brgnama" id="brgnama" placeholder="Nama Barang" class="form-control" type="text" required>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Pilih Kategori</label>
              <div class="col-md-9">
                <select name="brgkatid" id="brgkatid" class="form-control" required>
                  <option selected value="">=pilih=</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Pilih Satuan</label>
              <div class="col-sm-9">
                <select name="brgsatid" id="brgsatid" class="form-control" required>
                  <option selected value="">=pilih=</option>
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Harga</label>
              <div class="col-sm-9">
                <input type="number" class="form-control" id="brgharga" name="brgharga" value=0 required>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Stok</label>
              <div class="col-sm-9">
                <input type="number" class="form-control" id="brgstok" name="brgstok" value=0 required>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Upload Gambar (<i>jika ada</i>)</label>
              <div class="col-sm-4">
                <input type="file" id="brggambar" name="brggambar">
              </div>
            </div>
            <div class="form-group">
              <div id="preview"></div>
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

  function imagePreview(fileInput) {
    if (fileInput.files && fileInput.files[0]) {
      var fileReader = new FileReader();
      fileReader.onload = function(event) {
        $('#preview').html('<img src="' + event.target.result + '" width="auto" height="200"/>');
      };
      fileReader.readAsDataURL(fileInput.files[0]);
    }
  }
  $("#brggambar").change(function() {
    imagePreview(this);
  });

  function add_data() {

    $('#addBarang')[0].reset();
    $.ajax({
      url: "<?php echo site_url('kategori/get_all'); ?>",
      type: "GET",
      dataType: 'json',
      success: function(result) {
        $.each(result, function(i, value) {
          $('#brgkatid').append('<option value=' + value.katid + '>' + value.katnama + '</option>');
        });
      }
    });
    $.ajax({
      url: "<?php echo site_url('satuan/get_all'); ?>",
      type: "GET",
      dataType: 'json',
      success: function(result) {
        $.each(result, function(i, value) {
          $('#brgsatid').append('<option value=' + value.satid + '>' + value.satnama + '</option>');
        });
      }
    });
    $('#modal_form').modal('show'); // show bootstrap modal
    //$('.modal-title').text('Add Person'); // Set Title to Bootstrap modal title
  }
  $("#addBarang").submit(function(form) {

    //form.preventDefault();
    console.log($('#addBarang').serialize());
    $.ajax({
      url: "<?php echo site_url('barang/store'); ?>",
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
          //location.reload();

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
        url: "<?php echo site_url('barang/delete') ?>/" + id,
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
    $('#addBarang')[0].reset(); // reset form on modals
    <?php header('Content-type: application/json'); ?>
    //Ajax Load data from ajax
    $.ajax({
      url: "<?php echo site_url('barang/get_data/') ?>/" + id,
      type: "GET",
      dataType: "JSON",
      success: function(data) {
        $('[name="brgid"]').val(data.data.brgid);
        $('[name="brgnama"]').val(data.data.brgnama);
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