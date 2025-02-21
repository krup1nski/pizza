<?php
include "../php/db.php";

if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["del_id"])){
    $id=$_GET["del_id"];
    delete_product('pizzas', $id);
    header("Location:dashboard.php");
}

?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="../css/main.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

<?php include '../header.php'; ?>

<div class="main">
    <div class="container">
        <?php $products = select_all('pizzas')?>
        <table class="table">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Title</th>
                <th scope="col">Actions</th>
            </tr>
            </thead>

            <tbody>
            <?php foreach ($products as $product): ?>
            <tr>
                <th scope="row"><?=$product['id']?></th>
                <td><?=$product['name']?></td>
                <td>
                    <div class="product_actions d-flex">
                        <div class="show_product">
                            <a href="view.php?id=<?=$product['id']?>"><button type="submit" class="btn btn-primary"><i class="fa-solid fa-eye"></i></a>
                        </div>
                        <div class="edit_product">
                            <a href="edit.php?id=<?=$product['id']?>"><button type="submit" class="btn btn-warning"><i class="fa-solid fa-pen"></i></a>
                        </div>
                        <div class="delete_product">
                            <a href="dashboard.php?del_id=<?=$product['id']?>"><button type="submit" onclick="return confirm('are you sure to delete this product?')" class="btn btn-danger"><i class="fa-solid fa-trash"></i></button></a>
                        </div>
                    </div>
                </td>
            </tr>
            <?php endforeach;?>
            </tbody>
        </table>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>