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
            Data Input
          </div>
          <h2 class="page-title">
            Data Text
          </h2>
        </div>
      </div>
    </div>
  </div>
  <div class="page-body">
    <div class="container-xl">
        <div class="col-6">
            <div class="card">
                <div class="card-body">
                    <form method="POST">
                        <div class="row">
                            <div class="col-12">
                              <div class="input-icon mb-3">
                        
                                <textarea type="text" class="form-control" name="dataset" placeholder="input text"></textarea>
                              </div>
                            </div>
                          </div>
                <div class="row mt-2">
                  <div class="col-12">
                    <div class="form-group" type="submit">
                      <button class="btn btn-primary w-100">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-send" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                          <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                          <path d="M10 14l11 -11"></path>
                          <path d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5"></path>
                       </svg>
                       Simpan
                      </button>
                    </div>
                  </div>
                </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

        <?php
    // Membuat koneksi ke database
    $koneksi = new mysqli("localhost", "root", "", "analisis");

    // Memastikan koneksi berhasil
    if ($koneksi->connect_error) {
        die("Koneksi gagal: " . $koneksi->connect_error);
    }

    // Memeriksa apakah ada data dataset yang dikirimkan melalui POST
    if (isset($_POST['dataset'])){
        // Mengambil data dataset dari form
        $dataset = $_POST['dataset'];

        // Memisahkan baris dataset menjadi array
        $barisdataset = explode("\n", $dataset);

        // Memproses setiap baris dataset
        foreach ($barisdataset as $perbaris){
            // Memisahkan kolom dalam setiap baris menggunakan tab
            $kolom = explode("\t", $perbaris);

            // Memeriksa apakah kolom pertama ada isinya
            if (!empty($kolom[0])){
                // Menghindari SQL Injection dengan menggunakan parameterized query
                $full_text = $koneksi->real_escape_string($kolom[0]);
                
                // Menyimpan data ke database
                $sql = "INSERT INTO dataset (full_text) VALUES ('$full_text')";
                if (!$koneksi->query($sql)) {
                    echo "Error: " . $sql . "<br>" . $koneksi->error;
                }
            }
        }

        // Mengarahkan pengguna ke halaman tampil.php setelah data dimasukkan
        header("Location: tampil.php");
        exit(); // Penting untuk memastikan tidak ada output lain sebelum redirect
    }
        ?>
<?php
include 'layouts/footer.php';
?>