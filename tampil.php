<?php
$koneksi = new mysqli("localhost", "root", "", "analisis");

$dataset = [];
$ambil = $koneksi->query("SELECT * FROM dataset");
while ($tiap = $ambil->fetch_assoc()) {
    $dataset[] = $tiap;
}
//echo "<pre>";
//print_r($dataset);
//echo "</pre>";
?>
<?php
include 'layouts/main.php';
include 'layouts/sidebar.php';
include 'layouts/header.php';
?>
<div class="page-header d-print-none">
    <div class="container-xl">
      <div class="row g-2 align-items-center">
        <div class="col">
          <!-- Page pre-title -->
          <div class="page-pretitle">
            Overview
          </div>
          <h2 class="page-title">
            Data Text
          </h2>
        </div>
      </div>
    </div>
  </div>
    <div class="page-header d-print-none">
        <div class="container-xl">
            <div class="row">
                <div class="col-12">
                    <table class="table table-bordered" id="example">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Full Text</th>
                                <th>Text Bersih</th>
                                <th>Hasil Sentistrength</th>
                                <th>Label</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($dataset as $key => $values) : ?>
                                <tr>
                                    <td><?php echo $key + 1 ?></td>
                                    <td><?php echo $values['full_text'] ?></td>
                                    <td><?php echo $values['text_bersih'] ?></td>
                                    <td><?php echo $values['hasil_sentistrength'] ?></td>
                                    <td><?php echo $values['label_sentistrength'] ?></td>
                                </tr>
                            <?php endforeach ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
<?php
include 'layouts/footer.php';
?>