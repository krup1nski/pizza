<?php include "../php/db.php";

if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])){
    $id = intval($_GET["id"]);
    $product = select_all("pizzas", ['id'=>$id]);
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["del_id"])) {
    $id = intval($_GET["del_id"]);

    global $pdo;
    $sql = "UPDATE pizzas SET img = NULL WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->bindParam(":id", $id, PDO::PARAM_INT);
    $stmt->execute();

    header("Location: edit.php?id=$id");
    exit();
}


if($_SERVER['REQUEST_METHOD']=="POST" && isset($_POST['send_form_edit'])){
    $id = intval($_POST['id']);
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $slug = filter_var($_POST['slug'], FILTER_SANITIZE_STRING);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $price = $_POST['price'];
    $content = filter_var($_POST['content'], FILTER_SANITIZE_STRING);
    $publish = isset($_POST['publish']) ? $_POST['publish'] : 0;

    if(!empty($_FILES["profile_img"]["name"])){
        $img_name = $_FILES["profile_img"]["name"];
        $filetmpname = $_FILES["profile_img"]["tmp_name"];
        $filetype = $_FILES["profile_img"]["type"];
        $destination = "../img/pizza/".$img_name;

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

    $params=[
            'name'=>$name,
            'slug'=>$slug,
            'description'=>$description,
            'img'=>"img/pizza/".$img_name,
            'price'=>$price,
            'content'=>$content,
            'publish'=>$publish
    ];
    update("pizzas",$id, $params);


    global $pdo;
////////////////////////////////////////////////////////////////////////////////
    $sizes = select_all('sizes');

// Удаляем все старые записи для этой пиццы
    $deleteStmt = $pdo->prepare("DELETE FROM pizzassizes WHERE id_pizza = ?");
    $deleteStmt->execute([$id]);

// Подготовленный запрос на вставку новых размеров
    $insertStmt = $pdo->prepare("INSERT INTO pizzassizes (id_pizza, id_size) VALUES (?, ?)");

// Перебираем все размеры и вставляем только отмеченные чекбоксы
    foreach ($sizes as $size) {
        $size_id = (int) $size['id'];

        if (!empty($_POST["size-".$size['name']])) {
            $insertStmt->execute([$id, $size_id]);
        }
    }

////////////////////////////////////////////////////////////////////////////////
    $sides = select_all('sides');

    $deleteStmt = $pdo->prepare("DELETE FROM pizzassides WHERE id_pizza = ?");
    $deleteStmt->execute([$id]);

    $insertStmt = $pdo->prepare("INSERT INTO pizzassides (id_pizza, id_side) VALUES (?, ?)");

    foreach ($sides as $side) {
        $side_id = (int) $side['id'];

        if (!empty($_POST["side-".$side['name']])) {
            $insertStmt->execute([$id, $side_id]);
        }
    }
    ////////////////////////////////////////////////////////////////////////////////
    $doughs = select_all('doughs');

    $deleteStmt = $pdo->prepare("DELETE FROM pizzasdoughs WHERE id_pizza = ?");
    $deleteStmt->execute([$id]);

    $insertStmt = $pdo->prepare("INSERT INTO pizzasdoughs (id_pizza, id_dough) VALUES (?, ?)");

    foreach ($doughs as $dough) {
        $dough_id = (int) $dough['id'];

        if (!empty($_POST["dough-".$dough['name']])) {
            $insertStmt->execute([$id, $dough_id]);
        }
    }

    header("Location: edit.php?id=$id");
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
        <div class="mt-3 mb-3">EDIT</div>
        <form action="edit.php" method="post" enctype="multipart/form-data">
            <input type="hidden" name="id" value="<?=$product[0]['id']?>">
            <div class="mb-3">
                <label for="InputName" class="form-label">Name</label>
                <input type="text" class="form-control" value="<?=$product[0]['name']?>" name="name" id="InputName">
            </div>
            <div class="mb-3">
                <label for="InputSlug" class="form-label">Slug</label>
                <input type="text" class="form-control" value="<?=$product[0]['slug']?>" name="slug" id="InputSlug">
            </div>
            <div class="mb-3">
                <label for="InputDescription" class="form-label">Description</label>
                <input type="text" class="form-control" value="<?=$product[0]['description']?>" name="description" id="InputDescription">
            </div>

            <div class="pic_product">
                <a href="edit.php?del_id=<?=$product[0]['id']?>"
                   onclick="return confirm('Are you sure to delete this pic?')"
                   class="btn btn-danger">
                    <i class="fa-solid fa-trash"></i>
                </a>
                <img src="<?=BASE_URL?><?=$product[0]['img']?>" alt="">
            </div>

            <div class="load_pic">
                <label for="formFile" class="form-label"></label>
                <input class="form-control" name="profile_img" type="file" id="formFile">
            </div>

            <div class="mb-3">
                <label for="InputPrice" class="form-label">Price</label>
                <input type="text" class="form-control" value="<?=$product[0]['price']?>" name="price" id="InputPrice">
            </div>

            <div class="mb-3">
                <label for="InputContent" class="form-label">Content</label>
                <input type="text" class="form-control" value="<?=$product[0]['content']?>" name="content" id="InputContent">
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="publish" value="1" <?php echo $product[0]['publish']? 'checked': '';?>  id="flexCheckIndeterminate">
                <label class="form-check-label" for="flexCheckIndeterminate">
                    Publish
                </label>
            </div>

            <div>-------Sizes------->
                <?php
                $sizes = select_all('sizes');
                $product_sizes = select_all('pizzassizes', ['id_pizza' => $_GET['id']]);

                // Создаем массив с уже выбранными размерами
                $selected_sizes = array_column($product_sizes, 'id_size');
                ?>

                <?php foreach ($sizes as $size): ?>
                    <div class="form-check form-check-inline">
                        <!-- Скрытое поле для отправки 0, если чекбокс не отмечен -->
                        <input type="hidden" name="size-<?= $size['name'] ?>" value="0">

                        <input class="form-check-input"
                               type="checkbox"
                               id="size-<?=$size['id']?>"
                               name="size-<?=$size['name']?>"
                               value="<?=$size['id']?>"
                            <?= in_array($size['id'], $selected_sizes) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="size-<?=$size['id']?>"><?=$size['name']?></label>
                    </div>
                <?php endforeach; ?>
            </div>

            <div>-------Sides------->
                <?php
                $sides = select_all('sides');
                $product_sides = select_all('pizzassides', ['id_pizza' => $_GET['id']]);

                // Создаем массив с уже выбранными бортиками
                $selected_sides = array_column($product_sides, 'id_side');
                ?>

                <?php foreach($sides as $side): ?>
                    <div class="form-check form-check-inline">
                        <!-- Скрытое поле для отправки 0, если чекбокс не отмечен -->
                        <input type="hidden" name="side-<?= $side['name'] ?>" value="0">
                        <input class="form-check-input"
                               type="checkbox"
                               id="size-<?=$side['id']?>"
                               name="side-<?=$side['name']?>"
                               value="<?=$side['id']?>"
                            <?= in_array($side['id'], $selected_sides) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="inlineCheckbox1"><?=$side['name']?></label>
                    </div>
                <?php endforeach; ?>
            </div>

            <div>-------Doughs------->
                <?php
                $doughs = select_all('doughs');
                $product_doughs = select_all('pizzasdoughs', ['id_pizza' => $_GET['id']]);

                // Создаем массив с уже выбранными бортиками
                $selected_doughs = array_column($product_doughs, 'id_dough');
                ?>
                <?php foreach ($doughs as $dough): ?>
                    <div class="form-check form-check-inline">
                        <!-- Скрытое поле для отправки 0, если чекбокс не отмечен -->
                        <input type="hidden" name="dough-<?= $dough['name'] ?>" value="0">

                        <input class="form-check-input"
                               type="checkbox"
                               id="dough-<?=$dough['id']?>"
                               name="dough-<?=$dough['name']?>"
                               value="<?=$dough['id']?>"
                            <?= in_array($dough['id'], $selected_doughs) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="dough-<?=$dough['id']?>"><?=$dough['name']?></label>
                    </div>
                <?php endforeach; ?>
            </div>

            <button type="submit" class="btn btn-outline-warning" name="send_form_edit">Apply</button>
        </form>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>