<?php
// Sambungkan ke database MySQL
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "analisis";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Koneksi database gagal: " . $conn->connect_error);
}

// Buat kueri SQL untuk mengambil data dari tabel
$sql = "SELECT label_sentistrength, COUNT(*) as count FROM dataset GROUP BY label_sentistrength";
$result = $conn->query($sql);

$dataPoints = array();

if ($result->num_rows > 0) {
    // Proses hasil kueri dan format data ke dalam array
    while ($row = $result->fetch_assoc()) {
        $label_sentistrength = $row["label_sentistrength"];
        $count = $row["count"];
        $dataPoints[] = array("label_sentistrength" => $label_sentistrength, "y" => $count);
    }
} else {
    echo "Tidak ada data.";
}

// Tutup koneksi database
$conn->close();
?>
<?php
include 'layouts/main.php';
include 'layouts/sidebar.php';
include 'layouts/header.php';
?>
<style>
    .wordcloud-container {
        width: 100%;
        text-align: center;
    }

    .wordcloud-image {
        max-width: 100%;
        height: auto;
    }

    .wordcloud-title {
        text-align: center;
    }
</style>

<script>
    window.onload = function() {
        // Script chart

        // Chart pie
        var pieChart = new CanvasJS.Chart("pieChartContainer", {
            theme: "light2",
            animationEnabled: true,
            title: {
                text: "Distribusi Sentimen (Pie Chart)"
            },
            data: [{
                type: "pie",
                indexlabel_sentistrength: "{y}",
                yValueFormatString: "#,##0",
                indexlabel_sentistrengthPlacement: "inside",
                indexlabel_sentistrengthFontColor: "#36454F",
                indexlabel_sentistrengthFontSize: 18,
                indexlabel_sentistrengthFontWeight: "bolder",
                showInLegend: true,
                legendText: "{label_sentistrength}",
                dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
            }]
        });
        pieChart.render();

        // Chart kolom
        var columnChart = new CanvasJS.Chart("columnChartContainer", {
            theme: "light2",
            animationEnabled: true,
            title: {
                text: "Distribusi Sentimen (Column Chart)"
            },
            data: [{
                type: "column",
                dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
            }]
        });
        columnChart.render();

        // Chart garis
        var lineChart = new CanvasJS.Chart("lineChartContainer", {
            theme: "light2",
            animationEnabled: true,
            title: {
                text: "Distribusi Sentimen (Line Chart)"
            },
            data: [{
                type: "line",
                dataPoints: <?php echo json_encode($dataPoints, JSON_NUMERIC_CHECK); ?>
            }]
        });
        lineChart.render();
    }
</script>

<!-- Tombol kembali dengan Bootstrap -->
<div class="page-header d-print-none">
    <div class="container-xl">
        <div class="row g-2 align-items-center">
            <div class="col">
                <!-- Page pre-title -->
                <div class="page-pretitle">
                    Visualisasi
                </div>
                <h2 class="page-title">
                    Visualisasi Data
                    <!--{{ date('d-m-Y',strtotime(date('Y-m-d'))) }}-->
                </h2>
            </div>
        </div>
    </div>
</div>
<div class="page-body">
    <div class="container-xl">
        <div class="row">
            <div class="col-md-6 col-xl-3">
                <div class="card card-sm">
                    <div class="row align-items-center">
                        <div class="col">
                            <div id="columnChartContainer"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card card-sm">
                    <div class="row align-items-center">
                        <div class="col">
                            <div id="lineChartContainer"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card card-sm">
                    <div class="row align-items-center">
                        <div class="col">
                            <div id="pieChartContainer"></div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-6 col-xl-3">
                <div class="card card-sm">
                    <div class="row align-items-center">
                        <div class="col">
                            <div class="wordcloud-container">
                                <?php
                                // Panggil skrip Python untuk menghasilkan word cloud
                                exec('python generate_wordcloud.py');
                                echo "<h2 class='wordcloud-title'>WordCloud</h2>";
                                // Tampilkan gambar word cloud
                                echo '<img src="wordcloud_image.png" class="wordcloud-image" alt="Word Cloud" >';
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- Container untuk grafik -->
            </div>
        </div>
    </div>





    <!-- Script untuk CanvasJS -->
    <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

    <?php
    include 'layouts/footer.php';
    ?>