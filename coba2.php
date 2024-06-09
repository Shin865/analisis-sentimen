<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.3.1/dist/css/bootstrap.min.css" integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
<div class="container">
    <form method="post" enctype="multipart/form-data">
        <div class="mb-3">
            <label for="text">Train</label>
            <input class="form-control" type="file" name="train">
        </div>
        <div class="mb-3">
            <label for="text">Test</label>
            <input class="form-control" type="file" name="test">
        </div>
        <div>
            <button type="submit" name="kirim" class="btn btn-primary">Submit</button>
        </div>
    </form>
</div>

<?php
if (isset($_POST['kirim'])) {
    $train = array();
    $file = fopen($_FILES['train']['tmp_name'], 'r');
    while (!feof($file)) {
        $isi = fgets($file);
        foreach (explode("\n", $isi) as $key => $value) {
            if(!empty($value)){
                if (isset($value[0]) && isset($value[1]) && isset($value[2])) {
                    $train[$value[0]]['text'] = $value[2];
                    $train[$value[0]]['class'] = $value[1];
                }
            }
        }
    }
    fclose($file);

    $test = array();
    $file = fopen($_FILES['test']['tmp_name'], 'r');
    while (!feof($file)) {
        $isi = fgets($file);
        foreach (explode("\n", $isi) as $key => $value) {
            if(!empty($value)){
                if (isset($value[0]) && isset($value[1]) && isset($value[2])) {
                    $train[$value[0]]['text'] = $value[2];
                    $train[$value[0]]['class'] = $value[1];
                }
            }
        }
    }
    //membuat list class
    $list_class = array();
    foreach ($train as $key => $v) {
        $list_class[] = $v['class'];
    }
    $list_class = array_unique($list_class);

    $jumlah_training = count($train);

    $jumlah_train_class = array();
    foreach ($list_class as $key => $per_class) {
        $jumlah_train_class[$per_class] = 0;
        foreach ($train as $key => $v) {
            if ($per_class == $v['class']) {
                $jumlah_train_class[$per_class] += 1;
            }
        }
    }

    $prior = array();
    foreach ($jumlah_train_class as $per_class => $jumlah_per_class) {
        $prior[$per_class] = $jumlah_per_class / $jumlah_training;
    }

    $kamus = array();
    foreach ($prior as $per_class => $nilai_prior) {
        $kamus[$per_class] = array();
        foreach ($train as $key => $v) {
            if ($per_class == $v['class']) {
                $text_pecah = explode(" ", $v['text']);
                foreach ($text_pecah as $key => $kata) {
                    $kamus[$per_class][] = $kata;
                }
            }
        }
    }

    $kamus_unik = array();
    foreach ($kamus as $per_class => $per_kamus) {
        $kamus_unik[$per_class] = array_count_values($per_kamus);
    }

    $kamus_kata = array();
    foreach ($train as $key => $v) {
        foreach (explode(" ", $v['text']) as $key => $kata) {
            $kamus_kata[] = $kata;
        }
    }
    $kamus_kata_unik = array_unique($kamus_kata);
    $probabilitas = array();
    foreach ($test as $key => $per_test) {
        $text = $per_test['text'];
        $kata = explode(" ", $text);

        foreach ($prior as $per_class => $nilai_prior) {
            foreach ($kata as $kukuruyuk => $per_kata) {
                $jumlah_kata = isset($kamus_unik[$per_class][$per_kata]) ? $kamus_unik[$per_class][$per_kata] : 0;
                $jumlahkatadiclass = count($kamus[$per_class]);

                $jumlahkataunik = count($kamus_kata_unik);
                $x = ($jumlah_kata + 1) / ($jumlahkatadiclass + $jumlahkataunik);

                $probabilitas[$key][$per_class][$kukuruyuk] = $x;
            }
        }
    }

    $hasil_probabilitas = array();
    foreach ($probabilitas as $key => $per_test) {
        foreach ($per_test as $per_kelas => $isi_kelas) {
            $hasil_probabilitas[$key][$per_kelas] = $prior[$per_kelas] * array_product($isi_kelas);
        }
    }

    $sorting = array();
    foreach ($hasil_probabilitas as $key => $perdata) {
        arsort($perdata);
        $sorting[$key] = key($perdata);
    }
} else {
    $test = null;
}
?>

<?php if (isset($test)) : ?>

    <div class="container">
        <?php
        $no = 1;
        ?>
        <table class="table table-bordered">
            <tr>
                <th>No</th>
                <th>Text</th>
                <th>Class</th>
            </tr>
            <?php foreach ($test as $key => $value) : ?>
                <tr>
                    <td><?php echo $key; ?></td>
                    <td><?php echo $value['text']; ?></td>
                    <td><?php echo $sorting[$key]; ?></td>
                </tr>
                <?php $no++; ?>
            <?php endforeach ?>
        </table>
    </div>
<?php endif ?>