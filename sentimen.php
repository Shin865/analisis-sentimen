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
            Data Testing
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
                        
                                <textarea type="text" class="form-control" name="kalimat" placeholder="input text"></textarea>
                              </div>
                            </div>
                          </div>
                <div class="row mt-2">
                  <div class="col-12">
                    <div class="form-group" type="submit">
                      <button class="btn btn-primary w-100" name="submit">
                        <svg xmlns="http://www.w3.org/2000/svg" class="icon icon-tabler icon-tabler-send" width="24" height="24" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" fill="none" stroke-linecap="round" stroke-linejoin="round">
                          <path stroke="none" d="M0 0h24v24H0z" fill="none"></path>
                          <path d="M10 14l11 -11"></path>
                          <path d="M21 3l-6.5 18a.55 .55 0 0 1 -1 0l-3.5 -7l-7 -3.5a.55 .55 0 0 1 0 -1l18 -6.5"></path>
                       </svg>
                       Uji Sentimen
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
if(isset($_POST['submit'])){
	if (PHP_SAPI != 'cli') {
		echo "<pre>";
	}

	$strings = array(
		1 => $_POST['kalimat'],
	);

	require_once __DIR__ . '/autoload.php';
	$sentiment = new \PHPInsight\Sentiment();

	$i=1;
	foreach ($strings as $string) {

		// calculations:
		$scores = $sentiment->score($string);
		$class = $sentiment->categorise($string);

		// output:
		if (in_array("pos", $scores)) {
		    echo "Got positif";
		}

		echo "\n\nHasil:";
		echo "\nKalimat: <b>$string</b>\n";
		echo "Arah sentimen: <b>$class</b>, nilai: ";
		// var_dump($scores);
		foreach ($scores as $skor) {
			echo $skor;
		}
		echo "\n\n";
		$i++;
	}
}
?>