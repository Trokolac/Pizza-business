<?php
require_once './Pizza.class.php';
require_once './User.class.php';
require_once './Helper.class.php'; 

if( !User::isLoggedIn() ) {
    $loggedInUser = new User();
    $loggedInUser->loadLoggedInUser();
    Helper::addError('404 Page not found.');
    header("Location: ./index.php");
    die();
}


    $p = new Pizza();
    $pizzas = $p->allPizzas();

    if( isset($_POST['add_to_cart']) ) {
        if( $p->addToCart($_POST['quantity'],$_POST['pizza_id']) ) {
            Helper::addMessage("Pizza added to cart, check cart.");
            header("Location: ./menu.php");
            die();
        } else {
            header("Location: ./menu.php");
            die();
        }
    }

    


?>

<?php include './header.layout.php'; ?>


<!-- Card showing pizza information -->

<div class="container-fluid">
    <div class="row">
        <?php foreach($pizzas as $pizza) { ?>
            <div class="col-md-3 mb-3">
                <div class="card mx-auto" style="width: 100%; height:475px;">
                <img class="card-img-top product-image" src="<?php echo ($pizza->img) ? $pizza->img : './img/no-image.png' ?>">
                    <div class="card-body text-center">
                        <h4 class="card-title"><?php echo $pizza->title; ?></h4>
                        <div class="overflow-auto" style="height:75px;"><?php echo $pizza->description; ?></div>
                        <?php
                            $pizzaPriceUsd = $pizza->price;
                            $pizzaPriceEur = number_format($pizzaPriceUsd * 0.845073,2);
                        ?>
                        <h5 class="card-text">Cost: $<?php echo $pizzaPriceUsd; ?> / €<?php echo $pizzaPriceEur; ?></h5>   
                        <form action="./menu.php" method="post">
                        <input type="hidden" name="pizza_id" class="form-control" value="<?php echo $pizza->id ?>">
                        <input type="number" name="quantity" class="form-control" value="1" min="1">
                        <input type="submit" name="add_to_cart" class="btn btn-warning" value="Add to Cart" style="margin-top: 7px;">
                        </form>
                    </div>
                </div>
            </div>
        <?php } ?>
    </div>
</div>

<?php include './footer.layout.php'; ?> 