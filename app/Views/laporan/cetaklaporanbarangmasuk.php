<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Barang Masuk</title>
</head>

<body onload="window.print();">
    <table style="width: 100%; border-collapse: collapse; text-align: center;" border="1">
        <tr>
            <td>
                <table style="width: 100%; text-align: center;" border="0">
                    <tr style="text-align: center;">
                        <td>
                            <h1>PT. Surya Citra Utama Mandiri</h1>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
        <tr>
            <td>
                <table style="width: 100%; text-align: center;" border="0">
                    <tr style="text-align: center;">
                        <td>
                            <h3><u>Laporan Barang Masuk</u></h3>
                            <br>
                            Periode : <?= $tglawal ?> s/d <?= $tglakhir ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h5><u>Barang Masuk</u></h5>

                            <center>
                                <table border="1" cellpadding="5" style="border-collapse: collapse; border: 1px solid #000; width: 80%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>No. Faktur</th>
                                            <th>No. Surat Jalan</th>
                                            <th>Tanggal</th>
                                            <th>Total Item</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        foreach ($datalaporan->getResultArray() as $row) :
                                            $db = \Config\Database::connect();
                                $jumlahItem = $db->table('detail_barangmasuk')->where('detfaktur', $row['faktur'])->countAllResults();
                                        ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><?= $row['faktur']; ?></td>
                                                <td><?= $row['nosuratjalan']; ?></td>
                                                <td><?= $row['tglfaktur']; ?></td>
                                                <td style="text-align: right;">
                                                    <?= number_format($jumlahItem, 0, ",", ".") ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </center>
                            <br>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h5><u>Group Per Barang</u></h5>

                            <center>
                                <table border="1" cellpadding="5" style="border-collapse: collapse; border: 1px solid #000; width: 80%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Barang</th>
                                            <th>Kode Barang</th>
                                            <th>Jumlah</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        $totalSeluruhJumlah = 0;
                                        foreach ($datagroup->getResultArray() as $row) :
                                            $totalSeluruhJumlah += $row['QTY']
                                        ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><?= $row['brgnama']; ?></td>
                                                <td><?= $row['brgkode']; ?></td>
                                                <td style="text-align: right;">
                                                    <?= number_format($row['QTY'], 0, ",", ".") ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr>
                                            <th colspan="3">Total Seluruh Jumlah</th>
                                            <th style="text-align: right;">
                                                <?= number_format($totalSeluruhJumlah, 0, ",", ".") ?>
                                            </th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </center>
                            <br>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <h5><u>Detail Barang Masuk</u></h5>

                            <center>
                                <table border="1" cellpadding="5" style="border-collapse: collapse; border: 1px solid #000; width: 80%;">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Barang</th>
                                            <th>Kode Barang</th>
                                            <th>Tanggal Masuk</th>
                                            <th>Faktur</th>
                                            <th>No Surat Jalan</th>
                                            <th>Jumlah</th>
                                            <th>Input By</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $no = 1;
                                        
                                        foreach ($datadetail as $row) :
                                           
                                        ?>
                                            <tr>
                                                <td><?= $no++; ?></td>
                                                <td><?= $row->brgnama; ?></td>
                                                <td><?= $row->brgkode; ?></td>
                                                <td><?= $row->tglfaktur; ?></td>
                                                <td><?= $row->faktur; ?></td>
                                                <th><?= $row->nosuratjalan; ?></th>
                                                <td >
                                                    <?= number_format($row->detjml, 0, ",", ".") ?>
                                                </td>
                                                <td><?= $row->usernamalengkap; ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    
                                </table>
                            </center>
                            <br>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>