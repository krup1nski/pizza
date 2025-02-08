<?php include "php/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    $password = $_POST['password'];

    global $pdo;

    // Проверка на содержание букв и цифр
//    if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/", $password)) {
//        die("Ошибка: пароль должен содержать минимум 6 символов, включая хотя бы одну букву и одну цифру.");
//    }

    $select_user = $pdo->prepare("SELECT * FROM users WHERE phone = :phone");
    $select_user->bindParam(":phone", $phone);
    $select_user->execute();
    $row = $select_user->fetch(PDO::FETCH_ASSOC);

    if ($select_user->rowCount() > 0) {
        $_SESSION["id"] = $row['id'];
        $_SESSION["name"] = $row['name'];
        $_SESSION["phone"] = $row['phone'];
        header("location: index.php");
    }else{
        $message = "Invalid phone number or password";
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
            <div class="col-12 text-center mt-5">
                <div class="mb-5">Registration</div>
                <div class="reg-log-form">
                    <form method="post" action="login.php">
                        <div class="row mb-3">
                            <label for="inputPhone" class="col-sm-2 col-form-label">Phone number <sup>*</sup> </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputPhone" required name="phone">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <label for="inputPassword" class="col-sm-2 col-form-label">Password <sup>*</sup> </label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="inputPassword" required name="password">
                            </div>
                        </div>
                        <button type="submit" class="btn btn-primary">Sign in</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="./js/cart-01.js"> </script>

</body>
</html>