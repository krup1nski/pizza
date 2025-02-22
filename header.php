<header class="border-bottom">
    <div class="container">
        <div class="d-flex header">
            <div class="logo">
                <a href="<?=BASE_URL?>index.php"><img src="<?=BASE_URL?>img/pizza-logo.png" alt=""></a>
            </div>


            <ul class="nav flex-grow-1">
                <li class="white"><a href="">pizza</a></li>
                <li><a href="#">burgers</a></li>
                <li><a href="#">snacks</a></li>
            </ul>


            <div class="text-end">
                <button class="btn btn-orange" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasExample" aria-controls="offcanvasExample">
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

                        <div class="btn-order btn btn-orange">
                            <a href="order.php">Place an order</a>
                        </div>

                    </div>
                </div>
            </div>

            <li class="ms-2 nav-item dropdown" style="list-style-type: none;">
                <?php if(isset($_SESSION['id'])):?>
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <?=$_SESSION['name']?>
                    </a>
                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="profile.php">Profile</a></li>
                        <?php if($_SESSION['id'] == 1):?>
                            <li><a class="dropdown-item" href="<?=BASE_URL?>admin/dashboard.php">Admin board</a></li>
                        <?php endif;?>
                        <li><a class="dropdown-item" href="logout.php">Logout</a></li>
                    </ul>

                <?php else:?>
                    <a class="nav-link dropdown-toggle" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="fa fa-user" aria-hidden="true"></i>
                    </a>

                    <ul class="dropdown-menu">
                        <li><a class="dropdown-item" href="<?=BASE_URL?>login.php">Login</a></li>
                        <li><a class="dropdown-item" href="<?=BASE_URL?>registration.php">Registration</a></li>
                    </ul>
                <?php endif;?>
            </li>

        </div>
</header>
