<?php include "../php/db.php";

tt($_POST);
if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["send_form_create"])){
    $name = filter_var($_POST['name'], FILTER_SANITIZE_STRING);
    $slug = createSlug($name);
    $description = filter_var($_POST['description'], FILTER_SANITIZE_STRING);
    $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_FLOAT);
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

    global $pdo;
    $insert_user = $pdo->prepare("INSERT INTO pizzas (name, slug, description, img, price, content, publish) VALUES (:name, :slug, :description, :profile_img, :price, :content, :publish)");
    $insert_user->bindParam(":name", $name);
    $insert_user->bindParam(":slug", $slug);
    $insert_user->bindParam(":description", $description);
    $insert_user->bindParam(":profile_img", $img_name);
    $insert_user->bindParam(":price", $price);
    $insert_user->bindParam(":content", $content);
    $insert_user->bindParam(":publish", $publish);
    $insert_user->execute();


    $select_user = $pdo->prepare("SELECT * FROM pizzas WHERE name = :name");
    $select_user->bindParam(":name", $name);
    $select_user->execute();
    $row = $select_user->fetch(PDO::FETCH_ASSOC);

    if ($select_user->rowCount() > 0) {
        foreach ($_POST as $key => $value) {
            if (str_starts_with($key, 'size')) {
                $params = [
                    "id_pizza" => (int) $row['id'],
                    "id_size" => (int) $value
                ];
                tt($params);
                add_to_db('pizzassizes',$params );
            }


            if (str_starts_with($key, 'side')) {
                $params = [
                    "id_pizza" => (int) $row['id'],
                    "id_side" => (int) $value
                ];
                tt($params);
                add_to_db('pizzassides',$params );
            }
            if (str_starts_with($key, 'dough')) {
                $params = [
                    "id_pizza" => (int) $row['id'],
                    "id_dough" => (int) $value
                ];
                tt($params);
                add_to_db('pizzasdoughs',$params );
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
        <div class="mt-3 mb-3">Create</div>
        <form action="create.php" method="post" enctype="multipart/form-data">
            <div class="mb-3">
                <label for="InputName" class="form-label">Name</label>
                <input type="text" class="form-control" name="name" id="InputName">
            </div>
            <div class="mb-3">
                <label for="InputDescription" class="form-label">Description</label>
                <input type="text" class="form-control" name="description" id="InputDescription">
            </div>

            <div class="load_pic">
                <label for="formFile" class="form-label"></label>
                <input class="form-control" name="profile_img" type="file" id="formFile">
            </div>

            <div class="mb-3">
                <label for="InputPrice" class="form-label">Price</label>
                <input type="text" class="form-control" name="price" id="InputPrice">
            </div>

            <div class="mb-3">
                <label for="InputContent" class="form-label">Content</label>
                <input type="text" class="form-control" name="content" id="InputContent">
            </div>

            <div class="form-check">
                <input class="form-check-input" type="checkbox" name="publish" value="1" id="flexCheckIndeterminate">
                <label class="form-check-label" for="flexCheckIndeterminate">
                    Publish
                </label>
            </div>

            <div>-------Sizes------->
            <?php $sizes = select_all('sizes'); ?>
            <?php foreach($sizes as $size): ?>
            <div class="form-check form-check-inline">
                <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="size-<?=$size['name']?>" value="<?=$size['id']?>">
                <label class="form-check-label" for="inlineCheckbox1"><?=$size['name']?></label>
            </div>
            <?php endforeach; ?>
            </div>

            <div>-------Sides------->
                <?php $sides = select_all('sides'); ?>
                <?php foreach($sides as $side): ?>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="side-<?=$side['name']?>" value="<?=$side['id']?>">
                        <label class="form-check-label" for="inlineCheckbox1"><?=$side['name']?></label>
                    </div>
                <?php endforeach; ?>
            </div>

            <div>-------Doughs------->
                <?php $doughs = select_all('doughs'); ?>
                <?php foreach($doughs as $dough): ?>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" type="checkbox" id="inlineCheckbox1" name="dough-<?=$dough['name']?>" value="<?=$dough['id']?>">
                        <label class="form-check-label" for="inlineCheckbox1"><?=$dough['name']?></label>
                    </div>
                <?php endforeach; ?>
            </div>

            <button type="submit" class="btn btn-outline-warning" name="send_form_create">
        </form>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

</body>
</html>