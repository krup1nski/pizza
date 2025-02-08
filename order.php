<?php
include "php/db.php"; // Подключаем БД

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    header("Content-Type: application/json"); // Указываем, что ответ JSON

    $name = $_POST["name"];
    $phone = $_POST["phone"];
    $email = $_POST["email"];
    $delivery = $_POST["delivery"];
    $address = $_POST["address"];
    $payment = $_POST["payment"];
    $cart_data = json_decode($_POST["cart_data"], true);

    if (!$name || !$phone || empty($cart_data)) {
        echo json_encode(["success" => false, "message" => "Заполните все обязательные поля"]);
        exit();
    }

    global $pdo;
    $stmt = $pdo->prepare("INSERT INTO orders (name, phone, email, delivery, address, payment) VALUES (?, ?, ?, ?, ?, ?)");
    $stmt->execute([$name, $phone, $email, $delivery, $address, $payment]);
    $order_id = $pdo->lastInsertId();

    foreach ($cart_data as $item) {
        $stmt = $pdo->prepare("INSERT INTO order_items (order_id, product_id, title, size, dough, side, price, quantity) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->execute([
            $order_id,
            $item["id"],
            $item["title"],
            $item["size"],
            $item["dough"],
            $item["side"],
            $item["price"],
            $item["count"]
        ]);
    }

    echo json_encode(["success" => true]);
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


<div class="main">
    <div class="container">
        <div class="row">
            <div class="col-6">
                <h2 class="mt-5">Order Registration</h2>
<!--                <form id="order-form" method="POST">-->
<!--                <div class="row" style="margin-left: 0;">-->
<!--                    <div class="col">-->
<!--                        <label for="name" class="form-label">name *</label>-->
<!--                        <input type="text" class="form-order" required id="name">-->
<!--                    </div>-->
<!--                    <div class="col">-->
<!--                        <label for="tel" class="form-label">tel number *</label>-->
<!--                        <input type="text" class="form-order" required id="tel" placeholder="+375(__)___-__-__">-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="mt-3">-->
<!--                    <label for="inputEmail4" class="form-label">Email</label>-->
<!--                    <input type="email" class="form-order" id="inputEmail4">-->
<!--                </div>-->
<!--                <div class="mt-3">-->
<!--                    <div>Delivery* </div>-->
<!--                    <div>-->
<!--                    <select class="form-order-select" name="sort_by" id="sort_by">-->
<!--                        <option value="kurer">Курьером</option>-->
<!--                        <option value="sam">Самовызов</option>-->
<!--                    </select>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="row mt-3" style="margin-left: 0;">-->
<!--                    <div class="col-6">-->
<!--                        <label for="Street" class="form-label">Street</label>-->
<!--                        <input type="text" class="form-order" id="Street">-->
<!--                    </div>-->
<!--                    <div class="col">-->
<!--                        <label for="House" class="form-label">House</label>-->
<!--                        <input type="text" class="form-order" id="House">-->
<!--                    </div>-->
<!--                    <div class="col">-->
<!--                        <label for="Entrance" class="form-label">Entrance</label>-->
<!--                        <input type="text" class="form-order" id="Entrance">-->
<!--                    </div>-->
<!--                    <div class="col">-->
<!--                        <label for="Floor" class="form-label">Floor</label>-->
<!--                        <input type="text" class="form-order" id="Floor">-->
<!--                    </div>-->
<!--                    <div class="col">-->
<!--                        <label for="Flat" class="form-label">Flat</label>-->
<!--                        <input type="text" class="form-order" id="Flat">-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="mt-3">-->
<!--                    <div>Payment *</div>-->
<!--                    <div>-->
<!--                        <select class="form-order-select" name="sort_by" id="sort_by">-->
<!--                            <option value="nalichnie">Наличными</option>-->
<!--                            <option value="kartoi">Картой при получении</option>-->
<!--                        </select>-->
<!--                    </div>-->
<!--                </div>-->
<!--                <div class="mb-3 mt-3">-->
<!--                    <label for="exampleFormControlTextarea1" class="form-label">Example textarea</label>-->
<!--                    <textarea class="form-order" id="exampleFormControlTextarea1" rows="3"></textarea>-->
<!--                </div>-->
<!---->
<!--                <button type="submit" class="btn btn-warning">Заказать</button>-->
<!--                </form>-->

                <form id="order-form">
                    <input type="hidden" id="cart_data" name="cart_data">

                    <input type="text" name="name" placeholder="Ваше имя" required>
                    <input type="tel" name="phone" placeholder="Телефон" required>
                    <input type="email" name="email" placeholder="Email" required>

                    <select name="delivery">
                        <option value="pickup">Самовывоз</option>
                        <option value="delivery">Доставка</option>
                    </select>

                    <input type="text" name="address" placeholder="Адрес доставки">

                    <select name="payment">
                        <option value="cash">Наличными</option>
                        <option value="card">Картой</option>
                    </select>


                    <button type="submit">Оформить заказ</button>
                </form>


            </div>
            <div class="col-6">
                <div class="cart-wrapper m-5">
                    <div class="total-price">0 BYN</div>

                    <!-- There are goods in cart -->
                </div>
            </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="./js/cart-01.js"> </script>

</body>
</html>