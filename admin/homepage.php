<?php 
$queryV = $db->query("SELECT * FROM voters", PDO::FETCH_ASSOC);
$queryQ = $db->query("SELECT * FROM questions", PDO::FETCH_ASSOC);
$counter =0;
foreach ($queryV as $row) {
	$today =round((time() - $row["datestamp"]) / 86400);
	if($today == 0){
		$counter ++;
	}
}
?>
<div class="row">
	<div class="col-md-4">
		<div class="card card-user">
              <div class="card-body">
                <div class="author">
                    <div class="block block-one"></div>
                    <div class="block block-two"></div>
                    <div class="block block-three"></div>
                    <div class="block block-four"></div>               
                    <div class="infocard">
                    	<div class="infocardcount"><?=$queryQ->rowCount()?></div>
                    	<div class="infocardtext">Anket</div>
                    	<div class="infocarddesc">Aktif Olarak Yayımda</div>
                    </div>
              </div>
          	</div>
        </div>
	</div>	
	<div class="col-md-4">
		<div class="card card-user">
              <div class="card-body">
                <div class="author">
                    <div class="block block-one"></div>
                    <div class="block block-two"></div>
                    <div class="block block-three"></div>
                    <div class="block block-four"></div>               
                    <div class="infocard">
                    	<div class="infocardcount"><?=$queryV->rowCount()?></div>
                    	<div class="infocardtext">Kişi</div>
                    	<div class="infocarddesc">Anketleri Cevapladı</div>
                    </div>
              </div>
          	</div>
        </div>
	</div>	
	<div class="col-md-4">
		<div class="card card-user">
              <div class="card-body">
                <div class="author">
                    <div class="block block-one"></div>
                    <div class="block block-two"></div>
                    <div class="block block-three"></div>
                    <div class="block block-four"></div>               
                    <div class="infocard">
                    	<div class="infocardcount"><?=$counter?></div>
                    	<div class="infocardtext">Kişi</div>
                    	<div class="infocarddesc">Bugün Anketi Cevapladı</div>
                    </div>
              </div>
          	</div>
        </div>
	</div>
</div>
<canvas id="myChart" style="width: 100%;"></canvas>
<script>
$(document).ready(function(){
	var ctx = document.getElementById('myChart').getContext('2d');
	var myChart = new Chart(ctx, {
	  type: 'line',
	  data: {
	    labels: [
	    <?php 
	    	for($i = 4; $i>=0; $i--){
	    		echo '"'.(date("d")-$i).' '.date("M").' '.date("Y").'",';
	    	}
	    ?>
	    ],
	    datasets: [{
	      label: 'Oy Kullananlar',
	      data: [
	      <?php
			$countDay = array(0,0,0,0,0);
			$queryVS = $db->query("SELECT * FROM voters", PDO::FETCH_ASSOC);
			foreach ($queryVS as $sc) {
				$today = round((time() - $sc["datestamp"]) / 86400); 
				if($today == 0){
					$countDay[4] +=1;
				}else if($today == 1){
					$countDay[3] +=1;
				}else if($today == 2){
					$countDay[2] +=1;
				}else if($today == 3){
					$countDay[1] +=1;
				}else if($today == 4){
					$countDay[0] +=1;
				}
			}
			foreach ($countDay as $days) {
				echo '"'.$days.'",';
			}
	      ?>],
	      borderColor: "rgb(13, 255, 146, 0.6)",
	      backgroundColor: "rgb(255,255,255,0)"
	    }]
	  },
	  	options: {
				responsive: true,
				title: {
					display: true,
					text: 'Son 5 Günlük Analiz'
				},
				tooltips: {
					mode: 'index',
					intersect: false,
				},
				hover: {
					mode: 'nearest',
					intersect: true
				},
				scales: {
					xAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Gün'
						}
					}],
					yAxes: [{
						display: true,
						scaleLabel: {
							display: true,
							labelString: 'Kişi Sayısı'
						}
					}]
				}
			}
		
	});
});
</script>