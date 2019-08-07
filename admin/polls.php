<script type="text/javascript">
function refresh() {
    setTimeout(function () {
        location.reload()
    }, 2000);
}
function polldelete(id){
	$.ajax({
		type: 'post',
		url: '../src/ajax.php',
		data: {type:'polldelete', id:id},
		success: function(e){
			if (e==1) {
				document.getElementById("alert-content").style.color = '#0f0';
				document.getElementById("alert-content").innerHTML = "Anket Başarıyla Silindi";
				refresh();
			}else{
				document.getElementById("alert-content").style.color = '#f00';
				document.getElementById("alert-content").innerHTML = "Anket Silinirken Hata Oluştu";
				refresh();
			}
		}
	});
}
function polleditfield(id){
	var class_name = "poll-" + id;
	var element = $("."+ class_name);
	element.toggle("fold");
}
function addfield(id){
    var addButton = $('.add_button'); 
    var wrapper = $('.wrapper-'+id); 
    var fieldHTML = '<div class="input-grup"><input type="text" name="'+id+'answers[]" id="'+id+'answers[]" value=""/><span class="input-span"></span><a href="javascript:removefield('+id+');" class="remove_button"><i class="fas fa-minus"></i></a></div>'; 
    $(wrapper).on('click', '.add_button', function(e){
        e.preventDefault();
            $(wrapper).append(fieldHTML); 
    });
}
function polladdinputfield(){
    var addButton = $('.add_button'); 
    var wrapper = $('.inputfield'); 
    var fieldHTML = '<div class="input-grup"><input type="text" name="addanswers[]" id="addanswers[]" value=""/><span class="input-span"></span><a href="javascript:removeaddinputfield();" class="remove_button"><i class="fas fa-minus"></i></a></div>'; 
    $(wrapper).on('click', '.add_button', function(e){
        e.preventDefault();
            $(wrapper).append(fieldHTML); 
    });
}
function removefield(id){
    var rwrapper = $('.wrapper-'+id); 
    $(rwrapper).on('click', '.remove_button', function(e){
        e.preventDefault();
        $(this).parent('div').remove(); 
    });
}
function removeaddinputfield(){
    var rwrapper = $('.inputfield'); 
    $(rwrapper).on('click', '.remove_button', function(e){
        e.preventDefault();
        $(this).parent('div').remove(); 
    });
}

function polledit(id){
	var questionname = $("#"+id+"question").val();
	var answers  = new Array();
	$("input[name='"+id+"answers[]']").each(function() {
	    answers.push($(this).val());
	});
	if(questionname == "" || answers == "" || id==""){
				document.getElementById("alert-content").style.color = '#f00';
				document.getElementById("alert-content").innerHTML = "Lütfen Boş Alan Bırakmayınız";
	}else{
		$.ajax({
			type: 'post',
			url: '../src/ajax.php',
			data:{type:"polledit", questionname:questionname, answers:answers, id:id},
			success: function (e){
				if(e == 1){
					document.getElementById("alert-content").style.color = '#0f0';
					document.getElementById("alert-content").innerHTML = "Anket Başarıyla Güncellendi";
					refresh();
				}else{
					document.getElementById("alert-content").style.color = '#f00';
					document.getElementById("alert-content").innerHTML = "Anket Güncellenirken Hata Oluştu";
					refresh();
				}
			}
		});
	}
}
function polladd(){
	var question = $("#question").val();
	var answers  = new Array();
	$("input[name='addanswers[]']").each(function() {
	    answers.push($(this).val());
	});
	if(question == "" || answers == ""){
				document.getElementById("alert-content").style.color = '#f00';
				document.getElementById("alert-content").innerHTML = "Lütfen Boş Alan Bırakmayınız";
	}else{
	$.ajax({
		type: 'post',
		url: '../src/ajax.php',
		data:{type:"addpoll", question:question, answers:answers},
		success: function (e){
			if(e == 1){
				document.getElementById("alert-content").style.color = '#0f0';
				document.getElementById("alert-content").innerHTML = "Anket Başarıyla Eklendi";
				refresh();
			}else{
				document.getElementById("alert-content").style.color = '#f00';
				document.getElementById("alert-content").innerHTML = "Anket Eklenirken Hata Oluştu";
			}
		}
	});
	}
}
</script>

<div class="container">
<div id="polladdfield">
  <div>
  	<div class="form-grup input-grup">
  		<label for="question">Soru</label>
  		<input type="text" name="question" id="question" placeholder="Anket Sorusu">
  		<span class="input-span"></span>
  	</div>
  	<div class="form-grup inputfield input-grup">
  		<label for="answers">Cevaplar</label>
   		<input type="text" name="addanswers[]" id="addanswers[]" placeholder="Cevap" /><a href="javascript:polladdinputfield();" class="add_button" title="Alan Ekle"><i class="fas fa-plus"></i></a>
   		<span class="input-span"></span>
	</div>
	<div class="poll-edit-button"><button class="submit-button" type="button" onclick="polladd()">KAYDET</button></div>
  </div>
</div>
<div id="alert">
	<div id="alert-content"></div>
	<div class="poll-add"><p class="tooltip-left">Anket Ekle</p><a href='javascript:$("#polladdfield").toggle("fold");'><i class="fas fa-plus"></i></a></div>
</div>
<div class="accordion" id="accordions">
<?php 
$query = $db->query("SELECT * FROM questions ORDER BY id DESC", PDO::FETCH_ASSOC);
	if($query->rowCount()){
		foreach ($query as $row) {
?>
	
  <div class="card bg-white">
    <div class="card-header grid" id="headingOne">
      <h2 class="mb-0">
        <button class="btn btn-link" type="button" data-toggle="collapse" data-target="#collapse<?=permalink($row['id'])?>" aria-expanded="false" aria-controls="collapse<?=permalink($row['id'])?>">
          <?=$row["question"]?>
          </button> 
      </h2>
         <div class="links"><a href="javascript:polldelete(<?=$row["id"]?>)"><i class="fas fa-trash-alt"></i></a><a href="javascript:polleditfield(<?=$row["id"]?>)"><i class="fas fa-edit"></i></a></div>

    </div>

    <div id="collapse<?=permalink($row['id'])?>" class="collapse" aria-labelledby="headingOne" data-parent="#accordions">
      <div class="card-body" >
			<h1 class="color-black">>Cevaplar Ve Oylar</h1>
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
			<div class="admin-answers">
					<div class="answer"><?=$Answerkey?> </div>
					<div class="votecount"><?php if($votecount[$i]!=0){echo $votecount[$i]." <sub>OY</sub>";}?></div>
					<div class="progress"> 
						<div class="progress-bar" style="width: <?=$width?>%"><?=$width?>%</div>
					</div>
			</div>
		<?php
					$i++;
			}
		?>
      </div>
    </div>
  </div>
  <div id="poll-edit" class="poll-<?=$row['id']?>">
  	<div class="form-grup">
  		<label for="<?=$row['id']?>question">Soru</label>
  		<input type="text" name="<?=$row['id']?>question" id="<?=$row['id']?>question" value="<?=$row['question']?>">
  	</div>
  	<div class="form-grup wrapper-<?=$row['id']?>">
  		<label for="<?=$row['id']?>answers">Cevaplar</label>
   		<input type="text" name="<?=$row['id']?>answers[]" id="<?=$row['id']?>answers[]" value="<?=$answer[0]?>"/><a href="javascript:addfield(<?=$row['id']?>);" class="add_button" title="Alan Ekle"><i class="fas fa-plus"></i></a>

  		<?php 
  		$skip = 0;
  			foreach ($answer as $key) {
  				if ($skip >0) {
	  				echo '<div class="input-grup"><input type="text" name="'.$row["id"].'answers[]" id="'.$row["id"].'answers[]" value="'.$key.'"/><span class="input-span"></span><a href="javascript:removefield('.$row["id"].');" class="remove_button"><i class="fas fa-minus"></i></a></div>';
  				}
  				$skip++;
   			}
  		?>
	</div>
	<div class="form-grup">
		<span class="editinfo">Ankette Yapılan Değişikler Verilen Oyları Sıfırlayacak Ve Kaydedilen Oy Veren Listesi Sıfırlanacak</span>
	</div>
	<div class="poll-edit-button"><button class="submit-button" type="button" onclick="polledit(<?=$row['id']?>)">KAYDET</button></div>
  </div>
<?php	
		}
	}
?>

</div>
</div>