<?php include "php/db.php";
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
        </div>
</header>


<div class="main">
    <div class="container">
        <div class="filter">
            <div class="filter-ttl">
                Pizza
            </div>
            <div class="filter-choose">
                <form action="index.php" method="post">
                    <label for="sort_by">Сортировать по:</label>
                    <select class="form-select" name="sort_by" id="sort_by">
                        <option value="popularity" <?php if(isset($_POST['sort_by']) && $_POST['sort_by'] == 'popularity'): ?> selected <?php endif; ?> >Популярность</option>
                        <option value="name" <?php if(isset($_POST['sort_by']) && $_POST['sort_by'] == 'name'): ?> selected <?php endif; ?> >По названию</option>
                        <option value="desc_price" <?php if(isset($_POST['sort_by']) && $_POST['sort_by'] == 'desc_price'): ?> selected <?php endif; ?> >Цена по убыванию</option>
                        <option value="asc_price" <?php if(isset($_POST['sort_by']) && $_POST['sort_by'] == 'asc_price'): ?> selected <?php endif; ?> >Цена по возрастанию</option>
                    </select>

                    <button type="submit" class="btn btn-outline-warning" name="send_form_indx">
                        <i class="fa-solid fa-check"></i> Применить
                    </button>
                </form>

            </div>
        </div>
        <div class="row">
            <div class="goods col-12 d-flex flex-wrap">

                <?php
                if (isset($_POST['sort_by'])) {
                    $all_pizzas = select_all_filter("pizzas", $_POST['sort_by']);
                } else {
                    $all_pizzas = select_all("pizzas");
                }
                ?>

                <!--вывод всех товаров из таблицы pizzas-->
                <?php foreach ($all_pizzas as $pizza): ?>
                <div class="item itemWrapper m-3" data-id="<?=$pizza['id']?>" style="width: 18rem;">
                    <div style="text-align: center;" >
                <img class="item_img" src="<?=$pizza['img']?>" alt=""></div>
                <h4 style="text-align: center;" class="item_title">
                    <a href="view_product.php?id=<?=$pizza['id']?>"><?=$pizza['name']?></a>
                </h4>
                    <div class="gray gramm" style="text-align: center;">0 g</div>
                    <div class="pizza-size mt-2" style="text-align: center;">
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

                    <div class="pizza-dough" style="text-align: center;">
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

                    <div class="pizza-side" style="text-align: center;">
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

                    <div class="item-choose__price mt-3" style="text-align: center;">
                        <div class="item_price" data-base-price="<?=$pizza['price']?>"><?=$pizza['price']?> BYN</div>
                    </div>

                    <div class="incard d-flex justify-content-between mt-3">
                    <div class="plus-minus ms-3 mt-2">
                        <span class="minus-item btn-gray50" data-action="minus">-</span>
                        <span class="pizza-count"  data-counter>1</span>
                        <span class="plus-item btn-gray50" data-action="plus">+</span>
                    </div>

                    <div class="item-choose__incart me-3 mb-3">
                        <button data-cart type="button" class="btn btn-warning"><i class="fa-solid fa-cart-shopping"></i></button>
                    </div>
                    </div>
            </div>
                <?php endforeach; ?>


        </div>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="./js/cart-01.js"> </script>

</body>
</html>