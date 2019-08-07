<?php
require "db.config.php";

// Anket cevap kaydı
if($_POST["type"]=="save"){
				$questions = $db->query("SELECT * FROM questions", PDO::FETCH_ASSOC);
				foreach ($questions as $q) {
					$Question = $q["question"];
					$answer = explode('###',$_POST[permalink($q["question"], array('delimiter' => '','limit' => null,'lowercase' => true,'replacements' => array(),'transliterate' => true))]);
					$dbAnswer = explode("|", $q["answers"]);
					$voteArr = explode(",", $q["vote"]);
					$counter = 1;
					foreach ($dbAnswer as $dbKey) {
						if(permalink($dbKey) == $answer[1] && permalink($q["question"]) == $answer[0]){
							$voteArr[$counter-1] +=1;
							$newVoteArr = implode(",", $voteArr);
							$updateVote = $db->prepare("UPDATE questions SET
									vote = :newvote
									WHERE question='$Question' LIMIT 1
								");
							$update = $updateVote->execute(array(
								'newvote' => $newVoteArr
								
							));
						}

						$counter++;	
					}
				}
				$ip = GetIP();
				$datestamp = time();
				$insertVoters = $db->prepare("INSERT INTO voters SET
					ip= :ip,
					datestamp = :datestamp
				");
				$insert = $insertVoters->execute(array(
					"ip" => $ip,
					'datestamp' => $datestamp
				));				
				if($update && $insert){
					echo "1";
				}else{
					echo "0";
				}
}
// Anketin Silinmesi
if ($_POST["type"]=="polldelete") {
	$query = $db->prepare("DELETE FROM questions WHERE id = :id");
	$delete = $query->execute(array(
	   'id' => $_POST['id']
	));
	if ($delete) {
		echo "1";
	}else{
		echo "2";
	}
}
// Anketin Düzenlenmesi
if($_POST["type"]=="polledit"){
	$id = $_POST["id"];
	$questionname = trim(htmlspecialchars($_POST["questionname"]));
	$answers = $_POST["answers"];
	$newanswers = "";
	$numItems = count($answers);
	$i = 0;
	foreach ($answers as $key) {
		  if(++$i == $numItems) {
		    $newanswers .= $key;
		  }else{
		    $newanswers .= $key."|";
		  }
	}
	$newvote = "";
	for($i=1; $i<=$numItems; $i++){
		  if($i == $numItems) {
		    $newvote .= "0";
		  }else{
		    $newvote .= "0,";
		  }
	}
	$pollsave = $db->prepare("UPDATE questions SET 
		question = :newquestion,
		answers = :newanswers,
		vote = :newvote
	  WHERE id='$id' ORDER BY id DESC LIMIT 1");
 	$edit = $pollsave->execute(array(
 		'newquestion' => $questionname,
 		'newanswers' => $newanswers,
 		'newvote' => $newvote
 	));
 	if($edit){
 		$query_truncate = $db->prepare("TRUNCATE TABLE voters");
 		$truncate = $query_truncate->execute();
 		if ($truncate) {
 			echo "1";
 		}else{
 			echo "3";
 		}
 	}else{
 		echo "2";
 	}
}
// Yeni Anket Ekleme
if($_POST["type"]=="addpoll"){
	$question = htmlspecialchars($_POST["question"]);
	$answers = $_POST["answers"];
	$newanswers = "";
	$numItems = count($answers);
	$i = 0;
	foreach ($answers as $key) {
		  if(++$i == $numItems) {
		    $newanswers .= $key;
		  }else{
		    $newanswers .= $key."|";
		  }
	}
	$newvote = "";
	for($i=1; $i<=$numItems; $i++){
		  if($i == $numItems) {
		    $newvote .= "0";
		  }else{
		    $newvote .= "0,";
		  }
	}
	$pollsave = $db->prepare("INSERT INTO questions SET 
		question = :question,
		answers = :answers,
		vote = :vote");
 	$add = $pollsave->execute(array(
 		'question' => $question,
 		'answers' => $newanswers,
 		'vote' => $newvote
 	));
 	if($add){
 		$query_truncate = $db->prepare("TRUNCATE TABLE voters");
 		$truncate = $query_truncate->execute();
 		if ($truncate) {
 			echo "1";
 		}else{
 			echo "3";
 		}
 	}else{
 		echo "2";
 	}
}
if ($_POST["type"]=="editsettings") {
	$sitetitle = trim(htmlspecialchars($_POST["sitetitle"]));
	$sitekeywords = trim(htmlspecialchars($_POST["sitekeywords"]));
	$sitedescription = trim(htmlspecialchars($_POST["sitedescription"]));
	$query = $db->prepare("UPDATE settings SET
		sitetitle = :sitetitle,
		sitekeywords = :sitekeywords,
		sitedescription = :sitedescription
	WHERE id=1 ORDER BY id ASC LIMIT 1");
	$update = $query->execute(array(
		"sitetitle" => $sitetitle,
		"sitekeywords" => $sitekeywords,
		"sitedescription" => $sitedescription
	));
	if($update){
		echo "1";
	}else{
		echo "2";
	}
}
if ($_POST["type"] == "login") {
	$username = trim(htmlspecialchars($_POST["username"]));
	$password = md5(htmlspecialchars(trim($_POST["password"])));

	$query = $db->query("SELECT COUNT(*) FROM admins WHERE username='$username' and password='$password' ORDER BY id ASC");
	$count = $query->fetchColumn();
	if($count == 1){
		ob_start();
		session_start();
		$_SESSION["pollsystem-admin"] = $username;
		echo "girisyapildi";
	}else{
		echo "1";
	}
}
?>