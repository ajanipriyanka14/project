<?php

session_start();
include "config.php";

/* ================= CHECK CART ID ================= */

if (!isset($_GET['cart_id'])) {
    die("Cart ID Not Found");
}

$cart_id = intval($_GET['cart_id']);


/* ================= GET CART DATA ================= */

$query = mysqli_query(
    $conn,
    "SELECT * FROM carts WHERE id='$cart_id' LIMIT 1"
);

if (!$query) {
    die("Database Error: " . mysqli_error($conn));
}

if (mysqli_num_rows($query) == 0) {
    die("Cart Record Not Found");
}

$cart = mysqli_fetch_assoc($query);


/* ================= CART DATA ================= */

$customer_name = $cart['customer_name'];
$email         = $cart['email'];
$mobile        = $cart['mobile'];
$address       = $cart['address'];
$cake_name     = $cart['cake_name'];
$quantity      = $cart['quantity'];
$amount        = $cart['total'];

$error = "";


/* ================= PAYMENT ================= */

if (isset($_POST['submit'])) {

    $payment_method = mysqli_real_escape_string(
        $conn,
        $_POST['payment_method']
    );


    if (empty($payment_method)) {

        $error = "Please select payment method.";

    } else {


        /* ================= COD ================= */

        if ($payment_method == "COD") {

            $payment_status = "Pending";

            $payment_date = date("Y-m-d");


            $insert = mysqli_query(
                $conn,
                "INSERT INTO payment
                (
                    order_id,
                    customer_name,
                    payment_method,
                    amount,
                    payment_status,
                    payment_date
                )
                VALUES
                (
                    '$cart_id',
                    '$customer_name',
                    'Cash On Delivery',
                    '$amount',
                    '$payment_status',
                    '$payment_date'
                )"
            );


            if ($insert) {

                header(
                    "Location: place_order.php?cart_id=" .
                    $cart_id .
                    "&payment_method=COD"
                );

                exit();

            } else {

                $error = mysqli_error($conn);
            }


        } else {


            /* ================= ONLINE PAYMENT ================= */

            $payment_status = "Paid";

            $payment_date = date("Y-m-d");


            $insert = mysqli_query(
                $conn,
                "INSERT INTO payment
                (
                    order_id,
                    customer_name,
                    payment_method,
                    amount,
                    payment_status,
                    payment_date
                )
                VALUES
                (
                    '$cart_id',
                    '$customer_name',
                    '$payment_method',
                    '$amount',
                    '$payment_status',
                    '$payment_date'
                )"
            );


            if ($insert) {

                header(
                    "Location: place_order.php?cart_id=" .
                    $cart_id .
                    "&payment_method=" .
                    urlencode($payment_method)
                );

                exit();

            } else {

                $error = mysqli_error($conn);
            }

        }

    }

}

?>


<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<title>
Payment | Swiffin Cake Shop
</title>


<!-- Bootstrap -->

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet"
>


<!-- Font Awesome -->

<link
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
rel="stylesheet"
>


<style>

*{
    box-sizing:border-box;
}


body{

    margin:0;

    background:#000;

    color:#fff;

    font-family:Arial,sans-serif;

    min-height:100vh;
}


/* ================= PAYMENT BOX ================= */

.payment-box{

    width:92%;

    max-width:700px;

    margin:50px auto;

    background:#111;

    padding:35px;

    border-radius:20px;

    border:1px solid #E88F2A;

    box-shadow:
        0 0 20px rgba(232,143,42,.35),
        0 0 45px rgba(232,143,42,.12);
}


/* ================= TITLE ================= */

h2{

    color:#E88F2A;

    text-align:center;

    font-weight:bold;

    margin-bottom:30px;
}


h2 i{

    margin-right:8px;
}


/* ================= ORDER DETAILS ================= */

.order-details{

    background:#1b1b1b;

    border:1px solid #333;

    border-radius:12px;

    padding:20px;

    margin-bottom:25px;
}


.order-row{

    display:flex;

    justify-content:space-between;

    padding:10px 0;

    border-bottom:1px solid #333;
}


.order-row:last-child{

    border-bottom:none;
}


.order-row span:first-child{

    color:#aaa;
}


.order-row span:last-child{

    color:#fff;

    font-weight:bold;
}


.amount{

    color:#E88F2A !important;

    font-size:22px;
}


/* ================= LABEL ================= */

label{

    color:#E88F2A;

    font-weight:bold;

    margin-bottom:8px;
}


/* ================= PAYMENT OPTIONS ================= */

.payment-option{

    display:flex;

    align-items:center;

    gap:12px;

    background:#1b1b1b;

    border:1px solid #333;

    border-radius:10px;

    padding:14px;

    margin-bottom:10px;

    cursor:pointer;

    transition:.3s;
}


.payment-option:hover{

    border-color:#E88F2A;

    background:#222;
}


.payment-option input{

    width:18px;

    height:18px;

    accent-color:#E88F2A;
}


.payment-option i{

    color:#E88F2A;

    width:25px;

    text-align:center;
}


/* ================= PAY BUTTON ================= */

.btn-payment{

    background:#E88F2A;

    color:#fff;

    width:100%;

    min-height:52px;

    border:none;

    border-radius:30px;

    font-size:18px;

    font-weight:bold;

    margin-top:15px;

    transition:.3s;
}


.btn-payment:hover{

    background:#d67d18;

    color:#fff;
}


/* ================= BACK ================= */

.back{

    text-align:center;

    margin-top:20px;
}


.back a{

    color:#E88F2A;

    text-decoration:none;

    font-weight:bold;
}


.back a:hover{

    color:#fff;
}


/* ================= MOBILE ================= */

@media(max-width:600px){

    .payment-box{

        margin:25px auto;

        padding:20px;
    }

}

</style>

</head>


<body>


<div class="container">


<div class="payment-box">


<h2>

<i class="fas fa-credit-card"></i>

Payment

</h2>


<?php if ($error != "") { ?>

<div class="alert alert-danger">

<?php echo htmlspecialchars($error); ?>

</div>

<?php } ?>


<!-- ================= ORDER DETAILS ================= -->

<div class="order-details">


<div class="order-row">

<span>Customer Name</span>

<span>
<?php echo htmlspecialchars($customer_name); ?>
</span>

</div>


<div class="order-row">

<span>Email</span>

<span>
<?php echo htmlspecialchars($email); ?>
</span>

</div>


<div class="order-row">

<span>Mobile</span>

<span>
<?php echo htmlspecialchars($mobile); ?>
</span>

</div>


<div class="order-row">

<span>Cake</span>

<span>
<?php echo htmlspecialchars($cake_name); ?>
</span>

</div>


<div class="order-row">

<span>Quantity</span>

<span>
<?php echo htmlspecialchars($quantity); ?>
</span>

</div>


<div class="order-row">

<span>Total Amount</span>

<span class="amount">

₹<?php echo number_format($amount,2); ?>

</span>

</div>


</div>


<form method="POST">


<!-- ================= PAYMENT METHOD ================= -->

<label>

<i class="fas fa-wallet"></i>

Select Payment Method

</label>


<!-- COD -->

<label class="payment-option">

<input
    type="radio"
    name="payment_method"
    value="COD"
    required
>

<i class="fas fa-money-bill-wave"></i>

<span>
Cash On Delivery
</span>

</label>


<!-- UPI -->

<label class="payment-option">

<input
    type="radio"
    name="payment_method"
    value="UPI"
>

<i class="fas fa-qrcode"></i>

<span>
UPI Payment
</span>

</label>


<!-- CARD -->

<label class="payment-option">

<input
    type="radio"
    name="payment_method"
    value="Debit / Credit Card"
>

<i class="fas fa-credit-card"></i>

<span>
Debit / Credit Card
</span>

</label>


<!-- NET BANKING -->

<label class="payment-option">

<input
    type="radio"
    name="payment_method"
    value="Net Banking"
>

<i class="fas fa-building-columns"></i>

<span>
Net Banking
</span>

</label>


<!-- GOOGLE PAY -->

<label class="payment-option">

<input
    type="radio"
    name="payment_method"
    value="Google Pay"
>

<i class="fab fa-google-pay"></i>

<span>
Google Pay
</span>

</label>


<!-- PHONEPE -->

<label class="payment-option">

<input
    type="radio"
    name="payment_method"
    value="PhonePe"
>

<i class="fas fa-mobile-screen-button"></i>

<span>
PhonePe
</span>

</label>


<!-- PAYTM -->

<label class="payment-option">

<input
    type="radio"
    name="payment_method"
    value="Paytm"
>

<i class="fas fa-mobile-screen-button"></i>

<span>
Paytm
</span>

</label>


<!-- PAY NOW -->

<button
    type="submit"
    name="submit"
    class="btn-payment"
>

<i class="fas fa-lock"></i>

Pay Now

</button>


</form>


<div class="back">

<a href="checkout.php?id=<?php echo $cart_id; ?>">

← Back To Checkout

</a>

</div>


</div>

</div>


</body>

</html>