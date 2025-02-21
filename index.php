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

<?php include 'header.php'; ?>

<div class="main">
    <div class="container">
        <div id="carouselExample" class="carousel slide mt-3 mb-5">
            <div class="carousel-inner">
                <div class="carousel-item active">
                    <img src="https://slutsk.esh.by/upload/iblock/8fa/30dqccn3chhb48c3ez08e3l6h6mlqqxq.jpg" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="https://slutsk.esh.by/upload/iblock/8fa/30dqccn3chhb48c3ez08e3l6h6mlqqxq.jpg" class="d-block w-100" alt="...">
                </div>
                <div class="carousel-item">
                    <img src="https://slutsk.esh.by/upload/iblock/8fa/30dqccn3chhb48c3ez08e3l6h6mlqqxq.jpg" class="d-block w-100" alt="...">
                </div>
            </div>
            <button class="carousel-control-prev" type="button" data-bs-target="#carouselExample" data-bs-slide="prev">
                <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Previous</span>
            </button>
            <button class="carousel-control-next" type="button" data-bs-target="#carouselExample" data-bs-slide="next">
                <span class="carousel-control-next-icon" aria-hidden="true"></span>
                <span class="visually-hidden">Next</span>
            </button>
        </div>

        <div class="filter">
            <div class="filter-choose">
                <form action="index.php" method="GET">
                    <div class="d-flex justify-content-end align-items-center">
                    <label for="sort_by">Sort by:</label>
                    <select class="form-select select-fltr" name="sort_by" id="sort_by">
                        <option value="popularity" <?php if(isset($_POST['sort_by']) && $_POST['sort_by'] == 'popularity'): ?> selected <?php endif; ?> >Popularity</option>
                        <option value="name" <?php if(isset($_POST['sort_by']) && $_POST['sort_by'] == 'name'): ?> selected <?php endif; ?> >Name</option>
                        <option value="desc_price" <?php if(isset($_POST['sort_by']) && $_POST['sort_by'] == 'desc_price'): ?> selected <?php endif; ?> >Price descending</option>
                        <option value="asc_price" <?php if(isset($_POST['sort_by']) && $_POST['sort_by'] == 'asc_price'): ?> selected <?php endif; ?> >Price ascending</option>
                    </select>

                    <button type="submit" class="btn btn-outline-warning" name="send_form_indx">
                        <i class="fa-solid fa-check"></i>
                    </button>
                    </div>
                </form>
            </div>
        </div>
        <div class="row">
            <div class="goods col-12 d-flex flex-wrap">

                <?php
                // Preserve sorting in URL instead of using POST
                $sort_by = isset($_GET['sort_by']) ? $_GET['sort_by'] : null;

                // Count all products with sorting
                if ($sort_by) {
                    $all_products = select_all_filter("pizzas", $sort_by);
                } else {
                    $all_products = select_all('pizzas', ['publish' => 1]);
                }

                $count = count($all_products);

                // Pagination setup
                $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
                $limit = 4;
                $offset = ($page - 1) * $limit;
                $total_pages = ceil($count / $limit);

                // Fetch paginated products with sorting
                $all_products = pag('pizzas', $limit, $offset, $sort_by);
                ?>




                <!--вывод всех товаров из таблицы pizzas-->
                <?php foreach ($all_products as $pizza): ?>
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
                        <button data-cart type="button" class="btn btn-orange">add</button>
                    </div>
                    </div>
            </div>
                <?php endforeach; ?>


        </div>
        </div>



        <?php
        $current_page = max(1, min($total_pages, $page));
        $range = 2; // Number of pages before and after current
        $start_page = max(1, $current_page - $range);
        $end_page = min($total_pages, $current_page + $range);

        // Preserve sorting in URL
        $sort_by_param = isset($_GET['sort_by']) ? '&sort_by=' . urlencode($_GET['sort_by']) : '';
        ?>
        <div class="mt-5 d-flex justify-content-center">
        <nav aria-label="Page navigation example">
            <ul class="pagination">
                <li class="page-item <?= $current_page == 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=1<?= $sort_by_param ?>">First</a>
                </li>
                <li class="page-item <?= $current_page == 1 ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $current_page - 1 . $sort_by_param ?>">Previous</a>
                </li>

                <?php for ($i = $start_page; $i <= $end_page; $i++): ?>
                    <li class="page-item <?= $i == $current_page ? 'active' : '' ?>">
                        <a class="page-link" href="?page=<?= $i . $sort_by_param ?>"><?= $i ?></a>
                    </li>
                <?php endfor; ?>

                <li class="page-item <?= $current_page == $total_pages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $current_page + 1 . $sort_by_param ?>">Next</a>
                </li>
                <li class="page-item <?= $current_page == $total_pages ? 'disabled' : '' ?>">
                    <a class="page-link" href="?page=<?= $total_pages . $sort_by_param ?>">Last</a>
                </li>
            </ul>
        </nav>
        </div>
    </div>
</div>


<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
<script src="./js/cart-01.js"> </script>

</body>
</html>