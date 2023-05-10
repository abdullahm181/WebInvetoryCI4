<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cetak QR Code</title>
  <style>
         table, th, td {
            border: 1px solid black;
         }
      </style>
</head>

<body onload="window.print();" style="text-align: center;">
<?php
      foreach ($tampildata as $row) :
      ?>
<table style="width: 80%;">
 <tr>
  <td rowspan="3" style="text-align: center;"><img src="https://chart.googleapis.com/chart?cht=qr&chl=<?= $row['brgkode']; ?>&chs=160x160&chld=L|0" class="qr-code img-thumbnail img-responsive"></td>
  <td>Nama Barang</td>
  <td><?= $row['brgnama']; ?></td>
 </tr>
 <tr>
  <td>Kode Barang</td>
  <td><?= $row['brgkode']; ?></td>
 </tr>
 <tr>
  <td>Lokasi</td>
  <td><?= $row['loklorong'] . ' ~ ' . $row['lokrak']; ?></td>
 </tr>
</table>
<?php endforeach; ?>

  
</body>

</html>