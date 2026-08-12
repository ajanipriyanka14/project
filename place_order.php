<?php

session_start();
include "config.php";

/* ================= CHECK CART ID ================= */

if (!isset($_GET['cart_id'])) {
    die("Cart ID Not Found");
}

$cart_id = intval($_GET['cart_id']);


/* ================= PAYMENT METHOD ================= */

$payment_method = "";

if (isset($_GET['payment_method'])) {
    $payment_method = mysqli_real_escape_string(
        $conn,
        $_GET['payment_method']
    );
}


/* ================= GET CART DATA ================= */

$query = mysqli_query(
    $conn,
    "SELECT * FROM carts
     WHERE id='$cart_id'
     LIMIT 1"
);

if (!$query) {
    die("Database Error: " . mysqli_error($conn));
}

if (mysqli_num_rows($query) == 0) {
    die("Cart Record Not Found");
}

$cart = mysqli_fetch_assoc($query);


/* ================= CART DETAILS ================= */

$customer_name = $cart['customer_name'];
$cake_name     = $cart['cake_name'];
$quantity      = $cart['quantity'];
$amount        = $cart['total'];


/* ================= ORDER STATUS ================= */

$status = "Pending";


/* ================= ORDER DATE ================= */

$order_date = date("Y-m-d H:i:s");


/* ================= INSERT ORDER ================= */

$insert_order = mysqli_query(
    $conn,

    "INSERT INTO orders
    (
        customer_name,
        cake_name,
        quantity,
        amount,
        status,
        order_date
    )

    VALUES
    (
        '$customer_name',
        '$cake_name',
        '$quantity',
        '$amount',
        '$status',
        '$order_date'
    )"
);


/* ================= CHECK ORDER ================= */

if (!$insert_order) {

    die(
        "Order Insert Error: " .
        mysqli_error($conn)
    );

}


/* ================= GET ORDER ID ================= */

$new_order_id = mysqli_insert_id($conn);


/* ================= DELETE CART ================= */

mysqli_query(
    $conn,
    "DELETE FROM carts
     WHERE id='$cart_id'"
);

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
Order Confirmation | Swiffin Cake Shop
</title>


<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet"
>


<link
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
rel="stylesheet"
>


<style>

body{

    margin:0;

    background:#000;

    color:#fff;

    font-family:Arial,sans-serif;

}


.confirmation{

    width:92%;

    max-width:650px;

    margin:70px auto;

    background:#111;

    border:1px solid #E88F2A;

    border-radius:20px;

    padding:40px;

    text-align:center;

    box-shadow:
        0 0 25px rgba(232,143,42,.35);

}


.success-icon{

    font-size:70px;

    color:#28a745;

    margin-bottom:20px;

}


h1{

    color:#E88F2A;

    font-weight:bold;

    margin-bottom:15px;

}


.message{

    color:#aaa;

    margin-bottom:30px;

}


.order-details{

    background:#1b1b1b;

    border:1px solid #333;

    border-radius:12px;

    padding:20px;

    text-align:left;

}


.detail-row{

    display:flex;

    justify-content:space-between;

    gap:20px;

    padding:10px 0;

    border-bottom:1px solid #333;

}


.detail-row:last-child{

    border-bottom:none;

}


.detail-row span:first-child{

    color:#aaa;

}


.detail-row span:last-child{

    color:#fff;

    font-weight:bold;

    text-align:right;

}


.total{

    color:#E88F2A !important;

    font-size:20px;

}


.btn-home{

    display:block;

    width:100%;

    background:#E88F2A;

    color:#fff;

    text-decoration:none;

    padding:14px;

    border-radius:30px;

    margin-top:25px;

    font-weight:bold;

}


.btn-home:hover{

    background:#d67d18;

    color:#fff;

}

</style>

</head>


<body>


<div class="confirmation">


<div class="success-icon">

<i class="fas fa-circle-check"></i>

</div>


<h1>

Order Confirmed!

</h1>


<p class="message">

Thank you for ordering from
<strong>Swiffin Cake Shop</strong>.

</p>


<div class="order-details">


<div class="detail-row">

<span>Order ID</span>

<span>

#<?php echo $new_order_id; ?>

</span>

</div>


<div class="detail-row">

<span>Customer Name</span>

<span>

<?php echo htmlspecialchars($customer_name); ?>

</span>

</div>


<div class="detail-row">

<span>Cake</span>

<span>

<?php echo htmlspecialchars($cake_name); ?>

</span>

</div>


<div class="detail-row">

<span>Quantity</span>

<span>

<?php echo htmlspecialchars($quantity); ?>

</span>

</div>


<div class="detail-row">

<span>Payment Method</span>

<span>

<?php echo htmlspecialchars($payment_method); ?>

</span>

</div>


<div class="detail-row">

<span>Total Amount</span>

<span class="total">

₹<?php echo number_format($amount,2); ?>

</span>

</div>


<div class="detail-row">

<span>Order Status</span>

<span>

<?php echo htmlspecialchars($status); ?>

</span>

</div>


<div class="detail-row">

<span>Order Date</span>

<span>

<?php echo htmlspecialchars($order_date); ?>

</span>

</div>


</div>


<a
href="index1.php"
class="btn-home"
>

<i class="fas fa-home"></i>

Back To Home

</a>


</div>


</body>

</html>