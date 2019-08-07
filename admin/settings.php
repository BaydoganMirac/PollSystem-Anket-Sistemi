<script type="text/javascript">
function refresh() {
setTimeout(function () {
    location.reload()
}, 2000);
}
function editsettings(){
    var sitetitle = $("#sitetitle").val();
    var sitekeywords = $("#sitekeywords").val();
    var sitedescription = $("#sitedescription").val();
    if(sitetitle=="" || sitekeywords=="" || sitedescription==""){
        document.getElementById("alert-content").style.color = '#f00';
        document.getElementById("alert-content").innerHTML = "Lütfen Boş Alan Bırakmayınız";
    }else{
        $.ajax({
            type: 'post',
            url: '../src/ajax.php',
            data:{type:"editsettings", sitetitle:sitetitle, sitekeywords:sitekeywords, sitedescription:sitedescription},
            success: function (e){
                if(e == 1){
                    document.getElementById("alert-content").style.color = '#0f0';
                    document.getElementById("alert-content").innerHTML = "Ayarlar Kaydedildi";
                    refresh();
                }else{
                    document.getElementById("alert-content").style.color = '#f00';
                    document.getElementById("alert-content").innerHTML = "Ayarlar Kaydedilirken Hata Oluştu";
                    refresh();
                }
            }
        });
    }
}
</script>
<div class="container">
    <div id="alert-content"></div>
    <h1>Site Ayarları</h1>
    <div class="input-grup">
      <input type="text" id="sitetitle" placeholder="Site Başlığı" value="<?=$sitetitle?>">
      <span class="input-span"></span>
    </div>
    <div class="input-grup">
      <input type="text" id="sitekeywords" placeholder="Site Kelimeleri" value="<?=$sitekeywords?>">
      <span class="input-span"></span>
    </div>
    <div class="input-grup">
      <input type="text" id="sitedescription" placeholder="Site Açıklaması " value="<?=$sitedescription?>">
      <span class="input-span"></span>
    </div>
    <div class="float-right">
    <button class="submit-button" onclick="editsettings()">KAYDET</button>
    </div>
</div>