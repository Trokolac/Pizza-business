<?php
require_once './Pizza.class.php';
require_once './User.class.php';
require_once './Helper.class.php';

Helper::sessionStart();

if( !User::isLoggedIn() ) {
    $loggedInUser = new User();
    $loggedInUser->loadLoggedInUser();
    Helper::addError('404 Page not found.');
    header("Location: ./index.php");
    die();
}
  
$pizzaObject = new Pizza();

if( isset($_POST['remove_from_cart']) ) {
    if( $pizzaObject->removeFromCart($_POST['cart_id']) ) {
        Helper::addMessage('Pizza removed from cart.');
        header("Location: ./cart.php");
        die();
    } else {
        Helper::addError('Failed to remove pizza from cart.');
        header("Location: ./cart.php");
        die();
    }
}

if( isset($_POST['mail_to']) ) {
    $productMail = new Pizza();
    $productMail->mail();
    if($productMail){
        Helper::addMessage('Your order has been successful.');
        header("Location: ./cart.php");
        die();
    } else {
        Helper::addError('Please refresh page and try again.');
        header("Location: ./cart.php");
        die();
    }
  }

$pizzaProduct = $pizzaObject->getCart();

if(empty($pizzaProduct)) {
    Helper::addWarning('Cart is empty you can not browse it.');
    header("Location: ./index.php");
    die();
}

?>

<?php include './header.layout.php'; ?>

<!-- Cart information -->

<div class="container-fluid">
    
    <div class="row">
        <div class="col-md-7">

            <div class="row">
                <div class="col-md-3"><h4>Cart</h4></div>
                <div class="col-md-9"></div>
            </div>

            <table class="table">

                <thead>
                    <tr>
                    <th>Title</th>
                    <th>*</th>
                    <th>Price</th>
                    <th>Total</th>
                    <th>Actions</th>
                    </tr>
                </thead>

                <tbody>
                    <?php $total = 0; ?>
                    <?php foreach($pizzaProduct as $pizzaCart) { ?>
                    <?php $total += number_format($pizzaCart->quantity * $pizzaCart->price, 2); ?>
                    <tr>
                        <th><?php echo $pizzaCart->title; ?></th>
                        <td><?php echo $pizzaCart->quantity; ?></td>
                        <?php
                        $pizzaPriceUsd = number_format($pizzaCart->price, 2);
                        $pizzaPriceEur = number_format($pizzaPriceUsd * 0.845073, 2);
                        $pizzaPriceTotalUsd = number_format($pizzaCart->quantity * $pizzaPriceUsd, 2);
                        $pizzaPriceTotalEur = number_format($pizzaPriceTotalUsd * 0.845073, 2);
                        ?>
                        <td>$<?php echo $pizzaPriceUsd; ?> / €<?php echo $pizzaPriceEur; ?></td>
                        <td>$<?php echo $pizzaPriceTotalUsd; ?> / €<?php echo $pizzaPriceTotalEur; ?></td>
                        <td>
                        <form action="./cart.php" method="post">
                            <input type="hidden" name="cart_id" value="<?php echo $pizzaCart->id ?>" />
                            <button name="remove_from_cart" class="btn btn-sm btn-outline-danger"><i class="far fa-trash-alt"></i> Delete</button>
                        </form>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>

                <tfoot>
                <tr>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                <td></td>
                </tr>
            </tfoot>

            </table>

            <form action="./cart.php" method="post">
            <div class="form-row">
                <div class="form-group col-md-12">
                <label for="inputAdress"><b>Adress:</b></label>
                <input type="text" class="form-control" id="inputAdress" placeholder="Reisdence address" name="adress" required>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group col-md-12">
                <label for="phone"><b>Phone number:</b></label>
                <input class="form-control" type="tel" id="phone" name="phone" placeholder=""
                    pattern="[0-9]{3}-[0-9]{3}-[0-9]{4}"
                    required>
                <small class="float-right">Format: 123-456-7890</small>
                </div>
            </div>

            <div class="form-row">
                <div class="col-md-12">
                    <div class="md-form">
                        <label for="message"> <b>Message:</b> </label>
                        <input type="hidden" name="order" value="<?php foreach($pizzaProduct as $pizzaCart) { ?><?php echo $pizzaCart->title; ?> x <?php echo $pizzaCart->quantity; ?>&#10;<?php } ?>">
                        <textarea rows="3" type="text" id="message" name="message" class="form-control md-textarea"></textarea>
                    </div>
                </div>
            </div>

            <div class="form-row mt-4 mb-5">
                <div class="col-md-3"></div>
                <div class="col-md-7">
                    <div class="row">
                        <div class="col-md-5"><h6>Total cart price:</h6></div>
                        <?php 
                            $totalPriceUsd = number_format($total, 2); 
                            $totalPriceEur = number_format($totalPriceUsd * 0.845073, 2);
                        ?>
                        <div class="col-md-7"><h5>$<?php echo $totalPriceUsd; ?> / €<?php echo $totalPriceEur; ?></h5></div>
                    </div>
                </div>
                <div class="col-md-2">
                    <button name="mail_to" class="btn btn-sm btn-warning float-right">Order</button>
                </div>
            </div>

            </form>

        </div>
        <div class="col-md-5">
        PLACEHOLDER
        </div>
    </div>
</div>

<?php include './footer.layout.php'; ?>