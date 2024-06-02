<?php
    $koneksi = new mysqli("localhost", "root", "", "analisis");

    // Array untuk melacak teks yang sudah muncul sebelumnya
    $existing_texts = [];

    function normalizeAbbreviations($text) {
        // List kata singkatan dan kata utuhnya
        $abbreviations = array(
            " sy " => " saya ", " skrg " => " sekarang ", " cmn " => " cuman ", " gk " => " tidak ", " gak " => " tidak ", " poolll " => " banget ", " bgs " => " bagus ", " tpi " => " tapi ", " jdi " => " jadi ",
            " gmna " => " gimana ", " bt " => " buat ", " sdh " => " sudah ", " trs " => " terus ", " gt " => " seperti itu ", " spt " => " seperti ", " jkt " => " jakarta ", " dong " => " ",
            " ga " => " tidak ", " jg " => " juga ", " tp " => " tapi ", " krn " => " karena ", " yg " => " yang ", " bgmn " => " bagaimana ", " bs " => " bisa ", " kmrn " => " kemarin ",
            " gituuga " => " seperti itu juga ",  " thd " => " terhadap ", " kl " => " kalau ", " akn " => " akan ", " bomat " => " masa bodoh ", " tsb " => " tersebut ", " kesiap siagaan " => " siap siaga ",
            " gw " => " saya ", " dg " => " dengan ", " jd " => " jadi ", " tlh " => " telah ", " sdkt " => " sedikit ", " mgkin " => " mungkin ", " stlh " => " setelah ",
            " thn " => " tahun ", " jgn " => " jangan ", " slma " => " selama ", " dn " => " dan ", " sprti " => " seperti ", " jwb " => " jawab ", " nnti " => " nanti ", " n " => " ",
            " amp " => " dan ", " dr " => " dari ", " prabowogibran " => " prabowo gibran ", " gtmemutus " => " putus ", " memutuskn " => " memutuskan ", " rmh " => " rumah ",
            " kluarga " => " keluarga ", " sgt " => " sangat ", " dlm " => " dalam ", " smg " => " semoga ", " ttp " => " tetap ", " lg " => " lagi ", " mkwewenang nya " => " mk wewenangnya ",
            " knp " => " kenapa ", " nggak " => " tidak ", " skr " => " sekarang ", " klo " => " kalo", " bgt " => " banget ", " udh " => " udah ", " lelet " => " lama ", " gaje " => " tidak jelas",
            " karn " => " karena ", " apan " => " apaan ", " ny " => " ", " nya " => " ", " ksih " => " kasih", " dlu " => " dulu ", " tu " => " situ ", " d " => " di", " apl " => " aplikasi ", " mntap " => " bagus ", " aj " => " saja ", " dgn " => " dengan ",
            " pdhal " => " padahal ", " apk " => " aplikasi ", " yng " => " yang ", " ajh " => " saja ", " agk " => " agak ", " gk pp " => " tidak apa apa ", " min " => " admin ", " skarang " => " sekarang ", " bgus " => " bagus ", " ya " => " ", " di " => " ",
            " lamamales " => " lama malas ", " ni " => " "

            
            // Tambahkan kata singkatan dan kata utuh lainnya di sini
        );

        // Normalisasi kata singkatan
        foreach ($abbreviations as $abbreviation => $fullWord) {
            $text = preg_replace('/\b' . $abbreviation . '\b/i', $fullWord, $text);
        }

        return $text;
    }

    $ambil = $koneksi->query("SELECT * FROM dataset");

    while($tiap = $ambil->fetch_assoc())
    {
        $id_dataset = $tiap['id_dataset'];
        $full_text = $tiap['full_text'];

        // Menghapus username
        $full_text = preg_replace('/@\w+\s?/', '', $full_text);

        // Menghapus hastag
        $full_text = preg_replace('/#\w+\s?/', '', $full_text);

        // Conversi huruf kecil
        $full_text = strtolower($full_text);

        // Menghapus URL
        $full_text = preg_replace('/\b(?:https?|ftp):\/\/\S+/', '', $full_text);

        // Menghapus tanda baca kecuali spasi
        $full_text = preg_replace('/[^\p{L}\p{N}\s]/u', '', $full_text);
        $full_text = preg_replace('/ðŸ+/', '', $full_text);

        // Menghapus whitespace berlebihan
        $full_text = preg_replace('/\s+/', ' ', $full_text);

        // Normalisasi kata singkatan
        $full_text = normalizeAbbreviations($full_text);

        // Hapus baris teks yang duplikat
        if(!isset($existing_texts[$full_text])){
            $existing_texts[$full_text] = true;
            // Update ke database
            $koneksi->query("UPDATE dataset SET text_bersih= '$full_text' WHERE id_dataset = '$id_dataset'");
        } else {
            // Hapus data duplikat dari database
            $koneksi->query("DELETE FROM dataset WHERE id_dataset = '$id_dataset'");
        }
    }

    echo "<script>alert('Text telah diperbaiki')</script>";
    echo "<script>location= 'tampil.php'</script>";
?>