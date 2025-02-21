<?php include "../php/db.php";

tt($_POST);
tt($_GET);

if($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET["id"])){
    $id = $_GET["id"];
    $product = select_all("pizzas", ['id'=>$id]);
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
        <?php tt($product)?>
        <form action="create.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="InputName" class="form-label">Name</label>
                <input type="text" class="form-control" value="<?=$product[0]['name']?>" name="name" id="InputName">
            </div>
            <div class="mb-3">
                <label for="InputSlug" class="form-label">Slug</label>
                <input type="text" class="form-control" value="<?=$product[0]['slug']?>" name="slus" id="InputSlug">
            </div>
            <div class="mb-3">
                <label for="InputDescription" class="form-label">Description</label>
                <input type="text" class="form-control" value="<?=$product[0]['description']?>" name="description" id="InputDescription">
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
                        <input class="form-check-input"
                               type="checkbox"
                               id="size-<?=$size['id']?>"
                               name="size-<?=$size['id']?>"
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
                        <input class="form-check-input"
                               type="checkbox"
                               id="size-<?=$side['id']?>"
                               name="side-<?=$side['id']?>"
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
                <?php foreach($doughs as $dough): ?>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input"
                               type="checkbox"
                               id="size-<?=$dough['id']?>"
                               name="dough-<?=$dough['id']?>"
                               value="<?=$dough['id']?>"
                            <?= in_array($dough['id'], $selected_doughs) ? 'checked' : '' ?>>
                        <label class="form-check-label" for="inlineCheckbox1"><?=$dough['name']?></label>
                    </div>
                <?php endforeach; ?>
            </div>

            <button type="submit" class="btn btn-outline-warning" name="send_form_edit">
        </form>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>