<?= $this->extend('main/layout') ?>

<?= $this->section('judul') ?>
Input Transaksi Barang Keluar
<?= $this->endSection() ?>

<?= $this->section('subjudul') ?>
<button type="button" class="btn btn-warning" onclick="window.location='<?= site_url('barangkeluar/data') ?>'">
    <i class="fa fa-plus-backward"></i> Kembali
</button>
<?= $this->endSection() ?>

<?= $this->section('isi') ?>
<div class="row">
    <div class="col-lg-4">
        <div class="form-group">
            <label for="">No. Faktur</label>
            <input type="text" name="nofaktur" id="nofaktur" class="form-control" value="<?= $nofaktur ?>" readonly>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group">
            <label for="">Tgl. Faktur</label>
            <input type="date" name="tglfaktur" id="tglfaktur" class="form-control" value="<?= date('Y-m-d') ?>">
        </div>
    </div>
    <div class="col-lg-4">
        <div class="form-group">
            <label for="">Nama Pelanggan</label>
            <div class="input-group mb-3">
                <input type="text" class="form-control" placeholder="Nama Pelanggan" name="namapelanggan" id="namapelanggan" >
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-2">
        <div class="form-group">
            <label for="">Kode Barang</label>
            <div class="input-group mb-3">
                <input type="text" class="form-control" name="kodebarang" id="kodebarang">
                <div class="input-group-append">
                    <button class="btn btn-outline-primary" type="button" id="tombolCariBarang"><i class="fa fa-search" title="Cari barang "></i></button>
                    <button class="btn btn-outline-primary" type="button" id="tombolCariBarangScan"><i class="fa fa-barcode" title="Cari barang  Scan"></i></button>
                </div>
            </div>
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label for="">Nama Barang</label>
            <input type="text" name="namabarang" id="namabarang" class="form-control" readonly>
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-group">
            <label for="">harga Jual (Rp)</label>
            <input type="text" name="hargajual" id="hargajual" class="form-control" readonly>
        </div>
    </div>
    <div class="col-lg-2">
        <div class="form-group">
            <label for="">Qty (Rp)</label>
            <input type="number" name="jml" id="jml" class="form-control" value="1">
        </div>
    </div>
    <div class="col-lg-3">
        <div class="form-group">
            <label for="">#</label>
            <div class="input-group mb-3">
                <button type="button" class="btn btn-success" title="Simpan Item" id="tombolSimpanItem">
                    <i class="fa fa-save"></i>
                </button>&nbsp;
                <button type="button" class="btn btn-info" title="Selesai Transaksi" id="tombolSelesaiTransaksi">
                    Selesai Transaksi
                </button>&nbsp;
                
            </div>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-lg-12 tampilDataTemp">

    </div>
</div>
<div class="viewmodal" style="display: none;"></div>
<script src="https://app.sandbox.midtrans.com/snap/snap.js" data-client-key="SB-Mid-client-XPPD3CKQ-XDI4set"></script>
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
        
        console.log(qrCodeMessage);
        $('#kodebarang').val(qrCodeMessage);
        html5QrCodeScanner.clear();
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

    function kosong() {
        $('#kodebarang').val('');
        $('#hargajual').val('');
        $('#namabarang').val('');
        $('#jml').val('1');
        $('#kodebarang').focus();
    }

    function simpanItem() {
        let nofaktur = $('#nofaktur').val();
        let kodebarang = $('#kodebarang').val();
        let namabarang = $('#namabarang').val();
        let hargajual = $('#hargajual').val();
        let jml = $('#jml').val();

        if (kodebarang.length == 0) {
            Swal.fire('Error', 'Kode barang harus diinputkan', 'error');
            kosong();
        } else {
            $.ajax({
                type: "post",
                url: "/barangkeluar/simpanItem",
                data: {
                    nofaktur: nofaktur,
                    kodebarang: kodebarang,
                    namabarang: namabarang,
                    jml: jml,
                    hargajual: hargajual
                },
                dataType: "json",
                success: function(response) {
                    if (response.error) {
                        Swal.fire('Error', response.error, 'error');
                        kosong();
                    }

                    if (response.sukses) {
                        Swal.fire('berhasil', response.sukses, 'success');
                        tampilDataTemp();
                        kosong();
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }
    }

    function ambilDataBarang() {
        let kodebarang = $('#kodebarang').val();
        if (kodebarang.length == 0) {
            Swal.fire('Error', 'Kode barang harus diinputkan', 'error');
            kosong();
        } else {
            $.ajax({
                type: "post",
                url: "/barangkeluar/ambilDataBarang",
                data: {
                    kodebarang: kodebarang
                },
                dataType: "json",
                success: function(response) {
                    console.log(response);
                    if (response.error) {
                        Swal.fire('Error', response.error, 'error');
                        kosong();
                    }

                    if (response.sukses) {
                        let data = response.sukses;

                        $('#namabarang').val(data.namabarang);
                        $('#hargajual').val(data.hargajual);
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        }
    }

    function tampilDataTemp() {
        let faktur = $('#nofaktur').val();
        $.ajax({
            type: "post",
            url: "/barangkeluar/tampilDataTemp",
            data: {
                nofaktur: faktur
            },
            dataType: "json",
            beforeSend: function() {
                $('.tampilDataTemp').html('<i class="fa fa-spin fa-spinner"></i>');
            },
            success: function(response) {
                if (response.data) {
                    $('.tampilDataTemp').html(response.data);
                }
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    }

    function buatNoFaktur() {
        let tanggal = $('#tglfaktur').val();
        $.ajax({
            type: "post",
            url: "/barangkeluar/buatNoFaktur",
            data: {
                tanggal: tanggal
            },
            dataType: "json",
            success: function(response) {
                $('#nofaktur').val(response.nofaktur);
                tampilDataTemp();
            },
            error: function(xhr, ajaxOptions, thrownError) {
                alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
            }
        });
    }

    $(document).ready(function() {
        tampilDataTemp();
        $('#tglfaktur').change(function(e) {
            buatNoFaktur();
        });

        $('#kodebarang').keydown(function(e) {
            if (e.keyCode == 13) {
                e.preventDefault();
                ambilDataBarang();
            }
        });

        $('#tombolSimpanItem').click(function(e) {
            e.preventDefault();
            simpanItem()
        });

        $('#tombolCariBarang').click(function(e) {
            e.preventDefault();
            $.ajax({
                url: "/barangkeluar/modalCariBarang",
                dataType: "json",
                success: function(response) {
                    if (response.data) {
                        $('.viewmodal').html(response.data).show();
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
            let namapelanggan = $('#namapelanggan').val();
            if (namapelanggan.length == 0) {
                Swal.fire('Error', 'Nama pelanggan harus diinputkan', 'error');
            }else{
                $.ajax({
                type: "post",
                url: "/barangkeluar/modalPembayaran",
                data: {
                    nofaktur: $('#nofaktur').val(),
                    tglfaktur: $('#tglfaktur').val(),
                    namapelanggan: $('#namapelanggan').val(),
                    totalharga: $('#totalharga').val()
                },
                dataType: "json",
                success: function(response) {
                    if (response.error) {
                        Swal.fire('Error', response.error, 'error');
                    }

                    if (response.data) {
                        $('.viewmodal').html(response.data).show();
                        $('#modalpembayaran').modal('show');
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
            }
            
        });

        $('#tombolPay').click(function(e) {
            e.preventDefault();
            $.ajax({
                type: "post",
                url: "/barangkeluar/payMidtrans",
                data: {
                    nofaktur: $('#nofaktur').val(),
                    tglfaktur: $('#tglfaktur').val(),
                    namapelanggan: $('#namapelanggan').val(),
                    totalharga: $('#totalharga').val()
                },
                dataType: "json",
                success: function(response) {
                    if (response.error) {
                        Swal.fire('Error', response.error, 'error');
                    } else {
                        snap.pay(response.snapToken, {
                            // Optional
                            onSuccess: function(result) {
                                let dataResult = JSON.stringify(result, null, 2);
                                let dataObj = JSON.parse(dataResult);

                                $.ajax({
                                    type: "post",
                                    url: "/barangkeluar/finishMidtrans",
                                    data: {
                                        nofaktur: response.nofaktur,
                                        tglfaktur: response.tglfaktur,
                                        namapelanggan: response.namapelanggan,
                                        totalharga: response.totalharga,
                                        order_id: dataObj.order_id,
                                        payment_type: dataObj.payment_type,
                                        transaction_status: dataObj.transaction_status,
                                    },
                                    dataType: "json",
                                    success: function(response) {
                                        if (response.sukses) {
                                            alert(response.sukses);
                                            window.location.reload();
                                        }
                                    }
                                });
                            },
                            // Optional
                            onPending: function(result) {
                                let dataResult = JSON.stringify(result, null, 2);
                                let dataObj = JSON.parse(dataResult);

                                $.ajax({
                                    type: "post",
                                    url: "/barangkeluar/finishMidtrans",
                                    data: {
                                        nofaktur: response.nofaktur,
                                        tglfaktur: response.tglfaktur,
                                        namapelanggan: response.namapelanggan,
                                        totalharga: response.totalharga,
                                        order_id: dataObj.order_id,
                                        payment_type: dataObj.payment_type,
                                        transaction_status: dataObj.transaction_status,
                                    },
                                    dataType: "json",
                                    success: function(response) {
                                        if (response.sukses) {
                                            alert(response.sukses);
                                            window.location.reload();
                                        }
                                    }
                                });
                            },
                            // Optional
                            onError: function(result) {
                                let dataResult = JSON.stringify(result, null, 2);
                                let dataObj = JSON.parse(dataResult);

                                $.ajax({
                                    type: "post",
                                    url: "/barangkeluar/finishMidtrans",
                                    data: {
                                        nofaktur: response.nofaktur,
                                        tglfaktur: response.tglfaktur,
                                        namapelanggan: response.namapelanggan,
                                        totalharga: response.totalharga,
                                        order_id: dataObj.order_id,
                                        payment_type: dataObj.payment_type,
                                        transaction_status: dataObj.transaction_status,
                                    },
                                    dataType: "json",
                                    success: function(response) {
                                        if (response.sukses) {
                                            alert(response.sukses);
                                            window.location.reload();
                                        }
                                    }
                                });
                            }
                        });
                    }
                },
                error: function(xhr, ajaxOptions, thrownError) {
                    alert(xhr.status + "\n" + xhr.responseText + "\n" + thrownError);
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>