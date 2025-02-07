<?php include "php/db.php";

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    // Убираем недопустимые символы
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $phone = filter_var($_POST['phone'], FILTER_SANITIZE_STRING);
    $password = $_POST['password'];
    $password2 = $_POST['password2'];

    global $pdo;

    // Проверка на содержание букв и цифр
//    if (!preg_match("/^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$/", $password)) {
//        die("Ошибка: пароль должен содержать минимум 6 символов, включая хотя бы одну букву и одну цифру.");
//    }

    // Проверим, есть ли телефон в базе
    $select_user = $pdo->prepare("SELECT * FROM users WHERE phone = :phone");
    $select_user->bindParam(":phone", $phone);
    $select_user->execute();

    if ($select_user->rowCount() > 0) {
        $message[] = "Phone number already exists";
    } else {
        if ($password !== $password2) {
            $message[] = "Passwords do not match";
        } else {
            // Хешируем пароль перед сохранением в БД
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);

            // Добавляем пользователя в базу
            $insert_user = $pdo->prepare("INSERT INTO users (name, phone, password) VALUES (:name, :phone, :password)");
            $insert_user->bindParam(":name", $name);
            $insert_user->bindParam(":phone", $phone);
            $insert_user->bindParam(":password", $hashed_password);
            $insert_user->execute();

            // Получаем пользователя после добавления
            $select_user = $pdo->prepare("SELECT * FROM users WHERE phone = :phone");
            $select_user->bindParam(":phone", $phone);
            $select_user->execute();
            $row = $select_user->fetch(PDO::FETCH_ASSOC);

            if ($select_user->rowCount() > 0) {
                $_SESSION["id"] = $row['id'];
                $_SESSION["name"] = $row['name'];
                $_SESSION["phone"] = $row['phone'];
            }
        }
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



<header class="border-bottom">
    <div class="container">
        <div class="d-flex header">
            <div class="logo">LOGO</div>

            <ul class="nav flex-grow-1">
                <li class="white"><a href="#">pizza</a></li>
                <li><a href="#">burgers</a></li>
                <li><a href="#">snacks</a></li>
            </ul>


            <div class="telnubmer">
                <div class="small">a1 mts life</div>
                <a href="tel:+123-45-67">123-45-67</a>
            </div>


            <div class="text-end">
                <button class="btn btn-primary" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
                    cart
                </button>

                <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasExample" aria-labelledby="offcanvasExampleLabel">
                    <div class="offcanvas-header">
                        Cart
                        <button type="button" class="btn-close" data-bs-dismiss="offcanvas" aria-label="Close"></button>
                    </div>
                    <div class="offcanvas-body">
                        <input type="hidden" name="cart_data" id="cart_data">

                        <div class="cart-wrapper">
                            <!-- There are goods in cart -->
                        </div>

                        <div class="total-price">0</div>

                        <div class="btn-order">
                            <a href="order.php">Place an order</a>
                        </div>

                    </div>
                </div>
            </div>

            <li class="ms-2 nav-item dropdown" style="list-style-type: none;">
                <?php if(isset($_SESSION['id'])):?>
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?=$_SESSION['name']?><i class="fa fa-user" aria-hidden="true"></i>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="nav-link" href="orders.php">Orders</a></li>
                        <?php if($_SESSION['id'] == 1):?>
                            <li><a class="dropdown-item" href="admin/dashboard.php">Admin board</a></li>
                        <?php endif;?>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>

                <?php else:?>
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-user" aria-hidden="true"></i>
                    </a>

                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="login.php">Login</a></li>
                        <li><a class="dropdown-item" href="registration.php">Registration</a></li>
                    </ul>
                <?php endif;?>
            </li>

        </div>
</header>


<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-12 text-center mt-5">
                <div class="mb-5">Registration</div>
                <div class="reg-log-form">
                    <form method="post" action="registration.php">
                        <div class="row mb-3">
                            <label for="inputName3" class="col-sm-2 col-form-label">Name <sup>*</sup> </label>
                            <div class="col-sm-10">
                                <input type="text" class="form-control" id="inputName3" required name="name">
                            </div>
                        </div>
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
                        <div class="row mb-3">
                            <label for="inputPassword2" class="col-sm-2 col-form-label">Confirm Password <sup>*</sup> </label>
                            <div class="col-sm-10">
                                <input type="password" class="form-control" id="inputPassword2" required name="password2">
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