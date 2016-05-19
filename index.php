<? $image = "/www/image.php"; ?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta charset="UTF-8">
    <title>Батутный центр</title>
    
    <link type="text/css" rel="stylesheet" href="style.css">
</head>
<body>
<div id="nav">
    <div id="nav_container">
        <div class="nav_element">
            <img src="img/vk.png">
            <img src="img/instagram.png">
        </div>
        <div class="nav_element">
            <img src="img/home.png">г. Ярославль, ул. Свободы, д.46/3 (территория Казарм)
        </div>
        <div class="nav_element">
            <img src="img/phone.png">+7 (4852) 32-72-00
        </div>
        <div class="nav_element">
            <img src="img/mail.png">levelup76@yandex.ru
        </div>
    </div>
</div>
<div id="logo"><img src="img/logo.png"></div>
<div id="stripe">
    <div id="stripe1"></div>
</div>
<div id="container"> <!-- Камера -->
    <img id="camera_hidden" hidden src="<?=$image?>"/>
    <img id="camera_online" src="<?=$image?>"/>
</div>
<script>
    function new_photo(){
        var camera_hidden = document.getElementById("camera_hidden");
        camera_hidden.src = "<?=$image?>?"+Math.random();
        camera_hidden.onload = function(){
            document.getElementById("camera_online").src = camera_hidden.src;
            setTimeout(new_photo,100);
        };
        //camera_hidden.onerror = setTimeout(new_photo,3000);
    };
    setTimeout(new_photo,1);



    /*document.getElementById("camera_online").onerror = function(){
        document.getElementById("camera_online").src = document.getElementById("camera_hidden").src;
    };*/
</script>
</body>
</html>