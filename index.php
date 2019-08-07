<?php
require "src/db.config.php";

?>
<!DOCTYPE html>
<html>
<head>
	<title><?=$sitetitle?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="description" content="<?=$sitedescription?>" />
    <meta name="keywords" content="<?=$sitekeywords?>" />
    <meta name="title" content="<?=$sitetitle?>" />
    <meta http-equiv="expires" content="0" />
    <meta http-equiv="cache-control" content="no-cache" />
    <meta http-equiv="Pragma" content="no-cache" />
    <meta name="language" content="turkish" />
    <meta name="robots" content="all, follow, noarchive" />
    <meta name="googlebot" content="all, follow, noarchive" />
    <meta name="distribution" content="global" />
    <meta name="revisit-after" content="1 days" />
    <meta name="rating" content="general" />
    <meta name="copyright" content="BaydoganMirac.net" />
    <meta name="author" content="Miraç Baydoğan" />
    <meta name="designer" content="Miraç Baydoğan" />
    <meta name="email" content="baydoganmirac@gmail.com" />
    <meta name="reply-to" content="baydoganmirac@gmail.com" />
	<meta charset="utf-8">
	<link rel="stylesheet" type="text/css" href="css/style.css">
	<script type="text/javascript" src="js/jquery-3.3.1.min.js"></script>
	<style type="text/css">
	canvas {
		-moz-user-select: none;
		-webkit-user-select: none;
		-ms-user-select: none;
	}
	</style>
	<script type="text/javascript">
		function loader(target, width){
			$(target).animate({width:width+'%'}, 1000);
			alert(target+" "+width);
		}
		$(document).ready(function(){


			$(".sButton").click(function(){
				<?php
				$questions = $db->query("SELECT * FROM questions", PDO::FETCH_ASSOC);
				foreach ($questions as $q) {
				?>
					var <?=permalink($q["question"], array(         
						 'delimiter' => '',
				         'limit' => null,
				         'lowercase' => true,
				         'replacements' => array(),
				         'transliterate' => true
	         		))?> = $('input[name=<?=permalink($q["question"])?>]:checked').val();
				<?php
				}
				?>
				$.ajax({
					type:'post',
					url:'src/ajax.php',
					data:{
						type:'save',
							<?php
							$questions = $db->query("SELECT * FROM questions", PDO::FETCH_ASSOC);
							foreach ($questions as $q) {
							?>
						<?=permalink($q["question"], array(         
									 'delimiter' => '',
							         'limit' => null,
							         'lowercase' => true,
							         'replacements' => array(),
							         'transliterate' => true
				         		))?>:<?=permalink($q["question"], array(         
									 'delimiter' => '',
							         'limit' => null,
							         'lowercase' => true,
							         'replacements' => array(),
							         'transliterate' => true
				         		))?>,
							<?php
							}
							?>
					},
					success:function(s){
						if(s){
							location.reload();
						}else{
							alert("HATA");
						}

					}
				})
			});
		});
	</script>
</head>
<body>
	<?php
		$ip = GetIP();
		$voters = $db->query("SELECT * FROM voters WHERE ip='$ip'");
		if($voters->rowCount() == 0){
	?>
	<div class="container">
		<h1>Anket</h1>
		<?php
			$questions = $db->query("SELECT * FROM questions", PDO::FETCH_ASSOC);
			foreach ($questions as $q) {
				$s = $q["question"];
				echo "<ul><h2>".$s."</h2>";
				$answers = $db->query("SELECT * FROM questions WHERE question='$s'")->fetch(PDO::FETCH_ASSOC);
				$exp = explode("|",$q["answers"]);
				foreach ($exp as $key) {
				?>
				  <li>
				    <input checked type="radio" id="<?=permalink($s."-".$key)?>" name="<?=permalink($s)?>" value="<?=permalink($s)?>###<?=permalink($key)?>">
				    <label for="<?=permalink($s."-".$key)?>"><?=$key?></label>
				    <div class="check"></div>
				  </li>
				<?php
				}
				echo "</ul>";
			}
		?>
		<input type="button" class="sButton" value="Oy Ver">
	</div>

	<?php
		}else{
	?>

	<div class="container-fluid">
		<h1>Anket Sonuçları</h1>
	<?php
		$pullData = $db->query("SELECT * FROM questions", PDO::FETCH_ASSOC);
		foreach ($pullData as $row) {
	?>
	<div class="questions">
		<h3><?=$row["question"]?></h3>
		<?php
			$votecount = explode(",", $row["vote"]);
			$total =0;
			foreach ($votecount as $VC) {
				$total +=$VC;
			}
			$answer = explode("|",$row["answers"]);
			$i =0;
			foreach ($answer as $Answerkey) {
					if($votecount[$i] == 0){$width=0;}else{$width = ceil($votecount[$i]/$total*100);}
					
			?>
					<div class='question'>
					<div class='answer'><?=$Answerkey?> </div>
					<div class="votecount"><?php if($votecount[$i]!=0){echo $votecount[$i]." <sub>OY</sub>";}?></div>
					<div class='progress'> 
						<script>
							$(document).ready(function(){
								$('.<?=permalink($row["question"], array('delimiter'=>'')).permalink($Answerkey, array('delimiter'=>''))?>').animate({width:"<?=$width?>%"}, 1000);
							});
						</script>
						<div class="progress-bar <?=permalink($row["question"], array('delimiter'=>'')).permalink($Answerkey, array('delimiter'=>''))?>">&nbsp;</div>
					</div>
					</div>
		<?php
					$i++;
			}
		?>
	</div>
	<?php
			}
		}
	?>
</body>
</html>