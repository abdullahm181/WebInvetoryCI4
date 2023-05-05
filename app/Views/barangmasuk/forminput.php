<?= $this->extend('main/layout') ?>

<?= $this->section('judul') ?>
Input Barang Masuk
<?= $this->endSection() ?>

<?= $this->section('subjudul') ?>

<button type="button" class="btn btn-sm btn-warning" onclick="window.location='<?= site_url('barangmasuk/data') ?>'">
    <i class="fa fa-backward"></i> Kembali
</button>

<?= $this->endSection() ?>

<?= $this->section('isi') ?>
<div class="form-row">
    <div class="form-group col-md-6">
        <label for="">Input Faktur Barang Masuk</label>
        <input type="text" class="form-control" placeholder="No. Faktur" name="faktur" id="faktur">
    </div>
    <div class="form-group col-md-6">
        <label for="">Tanggal Faktur</label>
        <input type="date" class="form-control" name="tglfaktur" id="tglfaktur" value="<?= date('Y-m-d') ?>">
    </div>
</div>

<div class="card">
    <div class="card-header bg-primary">
        Input Barang
    </div>
    <div class="card-body">
        <div class="form-row">
            <div class="form-group col-md-3">
                <label for="">Kode Barang</label>
                <div class="input-group mb-3">
                    <input type="text" class="form-control" placeholder="Kode Barang" name="kdbarang" id="kdbarang">
                    <div class="input-group-append">
                        <button class="btn btn-outline-primary" type="button" id="tombolCariBarang"><i class="fa fa-search"></i></button>
                        <button class="btn btn-outline-primary" type="button" id="tombolCariBarangScan"><i class="fa fa-barcode"></i></button>
                    </div>
                </div>
            </div>
            <div class="form-group col-md-3">
                <label for="">Nama Barang</label>
                <input type="text" class="form-control" name="namabarang" id="namabarang" readonly>
            </div>
            <div class="form-group col-md-2">
                <label for="">Harga Jual</label>
                <input type="text" class="form-control" name="hargajual" id="hargajual" readonly>
            </div>
            <div class="form-group col-md-2">
                <label for="">Harga Beli</label>
                <input type="number" class="form-control" name="hargabeli" id="hargabeli">
            </div>
            <div class="form-group col-md-1">
                <label for="">Jumlah</label>
                <input type="number" class="form-control" name="jumlah" id="jumlah">
            </div>
            <div class="form-group col-md-1">
                <label for="">Aksi</label>
                <div class="input-group">
                    <button type="button" class="btn btn-sm btn-info" title="Tambah Item" id="tombolTambahItem">
                        <i class="fa fa-plus-square"></i>
                    </button>&nbsp;
                    <button type="button" class="btn btn-sm btn-warning" title="Reload Data" id="tombolReload">
                        <i class="fa fa-sync-alt"></i>
                    </button>
                </div>
            </div>
        </div>

        <div class="row" id="tampilDataTemp"></div>
        <div class="row justify-content-end">
            <button type="button" class="btn btn-sm btn-success" id="tombolSelesaiTransaksi">
                <i class="fa fa-save"></i>Selesai Transaksi
            </button>
        </div>
    </div>
</div>
<div class="modalcaribarang" style="display: none;">
    
</div>
<!-- Modal -->
<div class="modal fade" id="modalcaribarangscan" data-backdrop="static" data-keyboard="false" tabindex="-1" aria-labelledby="staticBackdropLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="staticBackdropLabel">Silahkan Cari Data Barang</h5>
                    <button type="button" class="close" onclick="resetCamera()" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="input-group mb-3">
                        <input type="text" class="form-control" placeholder="Silahkan cari barang berdasarkan Kode/Nama" id="cari">
                        <div class="input-group-append">
                            <button class="btn btn-outline-primary" type="button" id="btnCari">
                                <i class="fa fa-search"></i>
                            </button>
                        </div>
                    </div>
                    <!-- QR SCANNER CODE BELOW  -->
                    <div class="row">
                        <div class="col">
                            <div id="reader"></div>
                        </div>
                        <div class="col" style="padding: 30px">
                            <h4>Scan Result </h4>
                            <div id="result">
                                Result goes here
                            </div>
                        </div>

                    </div>
                    <div class="row viewdetaildatascan"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" onclick="resetCamera()" class="btn btn-secondary" data-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
    <style>
        #reader {
            width: 500px;
        }

        .result {
            background-color: green;
            color: #fff;
            padding: 20px;
        }


        #reader__scan_region {
            background: white;
        }
    </style>
<?= $this->endSection() ?>

<?= $this->section('javascript') ?>
<script type="text/javascript">
    // When scan is successful fucntion will produce data
    function onScanSuccess(qrCodeMessage) {
        html5QrCodeScanner.clear();
        $('#kdbarang').val(qrCodeMessage);
        $('#modalcaribarangscan').modal('hide');
        ambilDataBarang();
        // document.getElementById("result").innerHTML =
        //     '<span class="result">' + qrCodeMessage + "</span>";
        // ^ this will stop the scanner (video feed) and clear the scan area.
    }

    // When scan is unsuccessful fucntion will produce error message
    function onScanError(errorMessage) {
        // Handle Scan Error
    }

    // Setting up Qr Scanner properties
    var html5QrCodeScanner = new Html5QrcodeScanner("reader", {
        fps: 10,
        qrbox: 250
    });


    

    function resetCamera() {
        html5QrCodeScanner.clear();

    }

    function dataTemp() {
        let faktur = $('#faktur').val();
        if (faktur.length != 0) {
            $.ajax({
                type: "post",
                url: "/barangmasuk/dataTemp",
                data: {
                    faktur: faktur
                },
                dataType: "json",
                success: function(response) {
                    console.log(response);
                    if (response.data) {
                        $('#tampilDataTemp').html(response.data);
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }

    }

    function kosong() {
        $('#kdbarang').val('');
        $('#namabarang').val('');
        $('#hargajual').val('');
        $('#hargabeli').val('');
        $('#jumlah').val('');
        $('#kdbarang').focus();
    }

    function ambilDataBarang() {
        let kodebarang = $('#kdbarang').val();

        $.ajax({
            type: "post",
            url: "/barangmasuk/ambilDataBarang",
            data: {
                kodebarang: kodebarang
            },
            dataType: "json",
            success: function(response) {
                if (response.sukses) {
                    let data = response.sukses;
                    $('#namabarang').val(data.namabarang);
                    $('#hargajual').val(data.hargajual);
                    $('#hargabeli').focus();
                }
                if (response.error) {
                    alert(response.error);
                    kosong();
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    }

    $(document).ready(function() {
        dataTemp();

        $('#kdbarang').keydown(function(e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                ambilDataBarang();
            }
        });

        $('#tombolTambahItem').click(function(e) {
            e.preventDefault();
            let faktur = $('#faktur').val();
            let kodebarang = $('#kdbarang').val();
            let hargabeli = $('#hargabeli').val();
            let jumlah = $('#jumlah').val();
            let hargajual = $('#hargajual').val();

            if (faktur.length == 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Maaf, Faktur tidak boleh kosong'
                })
            } else if (kodebarang.length == 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Maaf, kodebarang wajib diisi'
                })

            } else if (hargabeli.length == 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Maaf, hargabeli wajib diisi'
                })

            } else if (jumlah.length == 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Maaf, jumlah wajib diisi'
                });
            } else {
                $.ajax({
                    type: "post",
                    url: "/barangmasuk/simpanTemp",
                    data: {
                        faktur: faktur,
                        kodebarang: kodebarang,
                        hargabeli: hargabeli,
                        hargajual: hargajual,
                        jumlah: jumlah
                    },
                    dataType: "json",
                    success: function(response) {
                        if (response.sukses) {
                            alert(response.sukses);
                            kosong();
                            dataTemp();
                        }
                    },
                    error: function(xhr, ajaxOptions, thrownError) {
                        alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                    }
                });
            }
        });

        $('#tombolReload').click(function(e) {
            e.preventDefault();
            dataTemp();
        });

        $('#tombolCariBarang').click(function(e) {
            e.preventDefault();
            $.ajax({
                url: "/barangmasuk/cariDataBarang",
                dataType: "json",
                success: function(response) {
                    if (response.data) {
                        $('.modalcaribarang').html(response.data).show();
                        $('#modalcaribarang').modal('show');
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });

        });

        $('#tombolCariBarangScan').click(function(e) {
            e.preventDefault();
            // in
            html5QrCodeScanner.render(onScanSuccess, onScanError);
            $('.modalcaribarang').show();
            $('#modalcaribarangscan').modal('show');

        });

        $('#tombolSelesaiTransaksi').click(function(e) {
            e.preventDefault();
            let faktur = $('#faktur').val();

            if (faktur.length == 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Pesan',
                    text: 'Maaf, Faktur tidak boleh kosong'
                });
            } else {
                Swal.fire({
                    title: 'Selesai Transaksi',
                    text: "Yakin transaksi ini disimpan ?",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Yes, simpan!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            type: "post",
                            url: "/barangmasuk/selesaiTransaksi",
                            data: {
                                faktur: faktur,
                                tglfaktur: $('#tglfaktur').val()
                            },
                            dataType: "json",
                            success: function(response) {
                                if (response.error) {
                                    Swal.fire({
                                        icon: 'error',
                                        title: 'Error',
                                        text: response.error
                                    });
                                }
                                if (response.sukses) {
                                    Swal.fire({
                                        icon: 'success',
                                        title: 'Berhasil',
                                        text: response.sukses
                                    }).then((result) => {
                                        if (result.isConfirmed) {
                                            window.location.reload();
                                        }
                                    });
                                }
                            },
                            error: function(xhr, ajaxOptions, thrownError) {
                                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                            }
                        });
                    }
                });
            }
        });
    });
</script>
<?= $this->endsection('javascript') ?>