<?php
    $koneksi = new mysqli("localhost", "root", "", "analisis");

    $dataset = [];
    $ambil = $koneksi->query("SELECT * FROM dataset");
    while($tiap = $ambil->fetch_assoc())
    {
        $id_dataset = $tiap['id_dataset'];
        $label_sentistrength= trim($tiap['label_sentistrength']);
        $koneksi->query("UPDATE dataset SET label_sentistrength= '$label_sentistrength' WHERE id_dataset = '$id_dataset'");

    }

    echo "<script>alert('label telah diseragamkan')</script>";
    echo "<script>location= 'tampil.php'</script>";
?>
