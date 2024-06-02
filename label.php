<?php
$koneksi = new mysqli("localhost", "root", "", "analisis");

$dataset = [];
$ambil = $koneksi->query("SELECT * FROM dataset");
while ($tiap = $ambil->fetch_assoc()) {
    $dataset[] = $tiap;
}

// Fungsi untuk menjalankan perintah dan menutup proses setelah selesai
function send($cmd)
{
    $handle = popen($cmd, 'r');
    pclose($handle);
}

// Jalankan skrip batch menggunakan fungsi send
send('start C:\xampp\htdocs\analisis-sentiment\sentistrength_id\sentistrength_id.bat');
header("Refresh:0"); // Me-refresh halaman untuk menampilkan perubahan

echo "<script>alert('label telah dibuat')</script>";
echo "<script>location= 'tampil.php'</script>";
