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
      <th>Lokasi</th>
      <th style="width: 15%;">Aksi</th>
    </tr>
  </thead>
  <tbody>
    <?php $nomor = 1;
    foreach ($tampildata as $row) : ?>
      <tr>
        <td><?= $nomor++; ?></td>
        <td>
          <?php if($row['brggambar']!='' || $row['brggambar']!=null): ?>
          <img src="<?= '../uploads/'. $row['brggambar'] ?>" width="auto" height="80"/>
          <?php endif; ?>
      </td>
        <td><?= $row['brgnama']; ?></td>
        <td><?= $row['brgkatid']; ?></td>
        <td><?= $row['brgsatid']; ?></td>
        <td><?= $row['brgkode']; ?></td>
        <td><?= $row['brgharga']; ?></td>
        <td><?= $row['brgstok']; ?></td>
        <td><?= $row['loklorong'].'-'.$row['lokrak']; ?></td>
        <td>
        <button class="btn btn-success" onclick="cetak(<?= $row['brgid']; ?>)">Cetak</button>
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
              <label class="control-label col-md-3">Kode Barang</label>
              <div class="col-md-9">
                <input name="brgkode" id="brgkode" placeholder="Kode Barang" class="form-control" type="text" readonly>
              </div>
            </div>
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
          
                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Pilih Satuan</label>
              <div class="col-sm-9">
                <select name="brgsatid" id="brgsatid" class="form-control" required>

                </select>
              </div>
            </div>
            <div class="form-group">
              <label class="control-label col-md-3">Pilih Lokasi</label>
              <div class="col-sm-9">
                <select name="brglokid" id="brglokid" class="form-control" required>
                  
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
    init_modal(true,null)
    $('#modal_form').modal('show'); // show bootstrap modal
    //$('.modal-title').text('Add Person'); // Set Title to Bootstrap modal title
  }

  function init_modal(isAdd = true,data) {
    $('#preview').empty();
    $.ajax({
      url: "<?php echo site_url('kategori/get_all'); ?>",
      type: "GET",
      dataType: 'json',
      success: function(result) {
        $('#brgkatid').empty();
        $('#brgkatid').append(`<option ${isAdd?'selected':''} value="" disabled>=pilih=</option>`);
        $.each(result, function(i, value) {
          if(isAdd==false){
            if (value.katid == data.brgkatid)
            $('#brgkatid').append('<option value=' + value.katid + ' selected>' + value.katnama + '</option>');
          else
            $('#brgkatid').append('<option value=' + value.katid + '>' + value.katnama + '</option>');
          }else
            $('#brgkatid').append('<option value=' + value.katid + '>' + value.katnama + '</option>');
          
        });
      }
    });
    $.ajax({
      url: "<?php echo site_url('satuan/get_all'); ?>",
      type: "GET",
      dataType: 'json',
      success: function(result) {
        $('#brgsatid').empty();
        $('#brgsatid').append(`<option  ${isAdd?'selected':''} value="" disabled>=pilih=</option>`);
        $.each(result, function(i, value) {
          if(isAdd==false){
            if (value.satid == data.brgsatid )
            $('#brgsatid').append('<option value=' + value.satid + ' //selected>' + value.satnama + '</option>');
          else
            $('#brgsatid').append('<option value=' + value.satid + '>' + value.satnama + '</option>');
          }else
            $('#brgsatid').append('<option value=' + value.satid + '>' + value.satnama + '</option>');
          
        });
      }
    });
    $.ajax({
      url: "<?php echo site_url('lokasi/get_all'); ?>",
      type: "GET",
      dataType: 'json',
      success: function(result) {
        $('#brglokid').empty();
        $('#brglokid').append(`<option  ${isAdd?'selected':''} value="" disabled>=pilih=</option>`);
        $.each(result, function(i, value) {
          if(isAdd==false){
            if (value.lokid == data.brglokid )
            $('#brglokid').append('<option value=' + value.lokid + ' selected>' + value.loklorong + ' - ' + value.lokrak + '</option>');
          else
            $('#brglokid').append('<option value=' + value.lokid + '>' + value.loklorong + ' - ' + value.lokrak + '</option>');
          }else
            $('#brglokid').append('<option value=' + value.lokid + '>' + value.loklorong + ' - ' + value.lokrak + '</option>');
          
        });
      }
    });
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
  function cetak(id) {
    // ajax delete data from database
    window.location="<?php echo site_url('barang/cetakkode') ?>/" + id
    
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
        init_modal(false,data)
        $('#brgstok').prop('readonly', true);
        //$('[name="brgkode"]').hide();
        $('[name="brgid"]').val(data.data.brgid);
        $('[name="brgnama"]').val(data.data.brgnama);
        $('[name="brgstok"]').val(data.data.brgstok);
        $('[name="brgkatid"]').val(data.data.brgkatid);
        $('[name="brgsatid"]').val(data.data.brgsatid);
        $('[name="brgkode"]').val(data.data.brgkode);
        $('[name="brgharga"]').val(data.data.brgharga);
        //$('[name="brggambar"]').val(data.data.brggambar);
        $('[name="brglokid"]').val(data.data.brglokid);
        if (data.data.brggambar != '' && data.data.brggambar!=null) {
          $('#preview').html('<img src="../uploads/' + data.data.brggambar + '" width="auto" height="200"/>');
        }
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