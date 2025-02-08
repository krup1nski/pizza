<?php include "php/db.php";

// Загрузка фото профиля
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["upload_img"])){
    tt($_FILES);
    if(!empty($_FILES["profile_img"]["name"])){
        $img_name = $_FILES["profile_img"]["name"];
        $filetmpname = $_FILES["profile_img"]["tmp_name"];
        $filetype = $_FILES["profile_img"]["type"];
        $destination = "img/".$img_name;

        // проверяем произошла ли загрузка или нет
        $result = move_uploaded_file($filetmpname, $destination);
        if($result){
            $_POST['profile_img'] = $img_name;
        }else{
            $message = "Sorry, there was an error uploading your file.";
        }
    }else{
        $message = "No file was uploaded.";
    }

    global $pdo;

    $update_photo = $pdo->prepare("UPDATE users SET img = :profile_img WHERE phone = :phone");
    $update_photo->bindParam(":profile_img", $img_name);
    $update_photo->bindParam(":phone", $_SESSION['phone']);

    if($update_photo->execute()){
        $message = "✅ Фото успешно обновлено!";
    } else {
        $message = "❌ Ошибка обновления фото в базе данных.";
    }
}

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="css/main.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

<?php include 'header.php'; ?>
<?php
if (isset($message)){
    echo $message;
}
?>
<div class="main">
    <div class="container">
        <div class="row">
            <div class="profile_img col-md-4 d-flex flex-column align-items-center">
                <?php $user = select_one('users', $_SESSION['id']);?>
                <div class="profile_name h4 text-center"><?=$user['name']?></div>

                <?php if(!empty($user['img'])):?>
                    <img src="img/<?=$user['img']?>" class="rounded-circle img-fluid" alt="Profile Picture">
                <?php else:?>
                    <img src="img/profile.jpg" class="rounded-circle img-fluid" alt="Profile Picture">
                <?php endif ?>

                <form action="profile.php" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label for="formFile" class="form-label">Выберите файл</label>
                        <input class="form-control" name="profile_img" type="file" id="formFile">
                    </div>
                    <input type="submit" name="upload_img" value="Upload" class="btn btn-primary">
                </form>

            </div>
            <div class="profile_info col-md-8">
                <!-- Additional Profile Info Goes Here -->
            </div>
        </div>
    </div>
</div>



<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="./js/cart-01.js"> </script>

</body>
</html>