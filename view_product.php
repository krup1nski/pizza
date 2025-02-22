<?php
include "php/db.php";
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>View product</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="<?=BASE_URL?>css/main.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css" integrity="sha512-Evv84Mr4kqVGRNSgIGL/F/aIDqQb7xQ2vcrdIwxfjThSH8CSR7PBEakCr51Ck+w+/U6swU2Im1vVX0SVk9ABhg==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>

<?php include 'header.php'; ?>

<?php $pizza= select_one("pizzas", $_GET['id'])?>

<div class="main">
    <div class="container">
        <div class="row mt-5">
            <!--- левая часть - Картинка и описание товара--->
            <div class="col-sm-10 col-md-10">
                <div class="detail_picture">
                    <img src="<?=$pizza['img']?>" alt="">
                </div>
                <div class="desktop_desc">
                    <h4 class="details-name">Description</h4>
                    <div class="detail_desc"><?=$pizza['description']?></div>
                    <h4 class="details-name mt-3">Content</h4>
                    <div class="detail_props"><?=$pizza['content']?></div>
                </div>
            </div>

            <!--- правая часть и добавки для товара--->
            <div class="col-sm-14 col-md-14">
                <div class="detail_product_info">
                    <div class="item">
                        <h4 class="item_title"><?=$pizza['name']?></h4>
                        <div class="gray gramm">0 g</div>
                        <div class="pizza-size mt-2">
                            <div class="pizza-size-title gray">
                                size
                            </div>
                            <?php $sizes = selectSizes($pizza['id'])?>
                            <div class="pizza-size-choose">
                                <?php foreach ($sizes as $size): ?>
                                    <input type="radio" class="btn-check"
                                           name="pizzaSize-id-<?=$pizza['id']?>"
                                           id="size-<?=$size['size_name']?>-id-<?=$pizza['id']?>"
                                           value="value-<?=$size['size_name']?>"
                                           data-price="<?=$size['size_price']?>"
                                           data-gramm="<?=$size['gramm_price']?>">
                                    <label class="btn btn-gray pizza-size" for="size-<?=$size['size_name']?>-id-<?=$pizza['id']?>"><?=$size['size_name']?></label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="pizza-dough">
                            <div class="pizza-dough-title gray mt-2">
                                dough
                            </div>
                            <?php $doughs = selectDoughs($pizza['id'])?>

                            <div class="pizza-dough-choose">
                                <?php foreach ($doughs as $dough): ?>
                                    <input type="radio" class="btn-check" name="pizzaDough-id-<?=$pizza['id']?>" id="dough-<?=htmlspecialchars($dough['dough_name'])?>-id-<?=$pizza['id']?>" value="<?= htmlspecialchars($dough['dough_name']) ?>">
                                    <label class="btn btn-gray pizza-dough" for="dough-<?=htmlspecialchars($dough['dough_name'])?>-id-<?=$pizza['id']?>"><?= htmlspecialchars($dough['dough_name']); ?></label>
                                <?php endforeach; ?>
                            </div>

                        </div>

                        <div class="pizza-side" >
                            <div class="pizza-side-title gray mt-2">
                                side
                            </div>
                            <?php $sides = selectSides($pizza['id'])?>
                            <div class="pizza-side-choose">
                                <?php foreach ($sides as $side): ?>
                                    <input type="radio"
                                           class="btn-check"
                                           name="pizzaSide-id-<?=$pizza['id']?>"
                                           id="side-<?= htmlspecialchars($side['side_name'])?>-id-<?=$pizza['id']?>"
                                           value="side-<?= htmlspecialchars($side['side_name']) ?>"
                                           data-priceside="<?=$side['side_price']?>">
                                    <label class="btn btn-gray pizza-side" for="side-<?= htmlspecialchars($side['side_name'])?>-id-<?=$pizza['id']?>"><?= $side['side_name'] ?></label>
                                <?php endforeach; ?>
                            </div>
                        </div>

                        <div class="item-choose__price mt-3">
                            <div class="item_price" data-base-price="<?=$pizza['price']?>"><?=$pizza['price']?> BYN</div>
                        </div>

                        <div class="incard d-flex justify-content-between mt-3">
                            <div class="plus-minus ms-3 mt-2">
                                <span class="minus-item btn-gray50" data-action="minus">-</span>
                                <span class="pizza-count"  data-counter>1</span>
                                <span class="plus-item btn-gray50" data-action="plus">+</span>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="additives-wrapper mt-5">
                    <h4>Additives</h4>
                    <div class="additives">
                        <?php $adds = select_all('additives')?>
                        <?php foreach ($adds as $add): ?>
                        <div class="row col-sm-5 col-md-12 col-lg-5 me-4 mb-2">
                           <div class="add">

                                   <img src="<?=$add['img']?>" alt="">

                                   <div class="add_main_info">
                                       <div class="details-name"><?=$add['name']?></div>
                                       <div class="add_weight">15 g</div>
                                       <div class="details-price"><?=$add['price']?> BYN</div>
                                   </div>
                                   <div class="add_counter">
                                       <div class="plus-minus ms-3 mt-2">
                                           <span class="minus-item btn-gray50" data-action="minus">-</span>
                                           <span class="pizza-count"  data-counter>0</span>
                                           <span class="plus-item btn-gray50" data-action="plus">+</span>
                                       </div>
                                   </div>
                           </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="<?=BASE_URL?>js/cart-01.js"> </script>

</body>
</html>