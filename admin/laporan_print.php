<!DOCTYPE html>
<html>
<head>
  <title>Laporan Penjualan</title>
</head>
<body>

  <style type="text/css">
    body{
      font-family: sans-serif;
    }

    .table{
      width: 100%;
    }

    th,td{
    }
    .table,
    .table th,
    .table td {
      padding: 5px;
      border: 1px solid black;
      border-collapse: collapse;
    }
  </style>

    
  <center>
    <h2>Laporan Penjualan</h2>
  </center>

  <?php 
  include '../koneksi.php';
  if(isset($_GET['tanggal_sampai']) && isset($_GET['tanggal_dari'])){
    $tgl_dari = $_GET['tanggal_dari'];
    $tgl_sampai = $_GET['tanggal_sampai'];
    ?>
    <br/>

    <table class="">
      <tr>
        <td width="20%">DARI TANGGAL</td>
        <td width="1%">:</td>
        <td><?php echo $tgl_dari; ?></td>
      </tr>
      <tr>
        <td>SAMPAI TANGGAL</td>
        <td>:</td>
        <td><?php echo $tgl_sampai; ?></td>
      </tr>
    </table>

    <br/>

    <table class="table table-bordered table-striped text-center" id="table-datatable">
      <thead>
        <tr>
          <th width="1%">NO</th>
          <th>INVOICE</th>
          <th>TANGGAL BOOKING</th>
          <th>TANGGAL MAIN</th>
          <th>NAMA CUSTOMER</th>
          <th>STATUS</th>
          <th width="20%">JUMLAH</th>
        </tr>
      </thead>
      <tbody>
        <?php 
        $no = 1;
        $a = 0;
        $data = mysqli_query($koneksi,"SELECT * FROM invoice,customer WHERE invoice_customer=customer_id and date(invoice_tanggal) >= '$tgl_dari' AND date(invoice_tanggal) <= '$tgl_sampai'");
        while($i = mysqli_fetch_array($data)){
          $a++;
          $total[$a] = $i['invoice_total_bayar'];
          ?>
          <tr>
            <td><?php echo $no++ ?></td>
            <td>INVOICE-00<?php echo $i['invoice_id'] ?></td>
            <td><?php echo date('d-m-Y', strtotime($i['invoice_tanggal'])); ?></td>
            <td><?php echo date('d-m-Y', strtotime($i['invoice_tgl_main'])); ?></td>
            <td><?php echo $i['customer_nama'] ?></td>
            <td><?php echo $i['invoice_status']; ?></td>
            <td><?php echo "Rp. ".number_format($i['invoice_total_bayar'])." ,-" ?></td>
          </tr>
          <?php 
        }
        ?>
      </tbody>
      <tfoot style="border: none">
        <tr>
          <td colspan="5" style="border: none">TOTAL</td>
          <td class="text-center"><?php echo "Rp. ".number_format(array_sum($total))." ,-"; ?></td> 
        </tr>
      </tfoot>
    </table>

    <?php 
  }else{
    ?>

    <div class="alert alert-info text-center">
      Silahkan Filter Laporan Terlebih Dulu.
    </div>

    <?php
  }
  ?>
</body>

<script>
  window.print();
</script>
</html>