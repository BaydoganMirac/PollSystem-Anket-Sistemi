<?php
ob_start();
session_start();
if(isset($_SESSION["pollsystem-admin"])){
  header("location:homepage.html");
}
?>
<!DOCTYPE html>
<html>
<head>
	<title>PollSystem Anket Sistemi - Admin Girişi</title>
	  <link href="https://use.fontawesome.com/releases/v5.0.6/css/all.css" rel="stylesheet">
	  <link href="../css/admin.css" rel="stylesheet">
	  <script src="../js/core/jquery.min.js"></script>
	  <script type="text/javascript">
	  	function login(){
	  		var username = $("#username").val();
	  		var password = $("#password").val();
	  		if(username == "" || password == ""){
				document.getElementById("alert-content").style.color = '#f00';
				document.getElementById("alert-content").innerHTML = "Lütfen Boş Alan Bırakmayınız";
	  		}else{
				$.ajax({
					type: 'post',
					url: '../src/ajax.php',
					data:{type:"login", username:username, password:password},
					success: function (e){
						if(e == 1){
							document.getElementById("alert-content").style.color = '#f00';
							document.getElementById("alert-content").innerHTML = "Giriş Yapılamadı. Lütfen Bilgilerinizi Kontrol Ediniz.";	
						}else if(e == "girisyapildi"){
							document.getElementById("alert-content").style.color = '#0F0';
							document.getElementById("alert-content").innerHTML = "Giriş Yapıldı. Yönlendiriliyorsunuz.";
							setTimeout(window.location = "homepage.html", 3000);
						}
					}
				});
	  		}
	  	}
	  </script>
</head>
<body class="signin-body">
	<h1>PollSystem</h1> 
	<div class="login-form">
	    <div class="input-grup">
	      <input type="text" id="username" placeholder="Kullanıcı Adı">
	      <span class="input-span"></span>
	    </div>
	    <div class="input-grup">
	      <input type="password" id="password" placeholder="Şifre">
	      <span class="input-span"></span>
	    </div>
		<button class="submit-button" onclick="login()">Giriş Yap</button>
	 	<div id="alert-content"></div>
	</div>
</body>
</html>