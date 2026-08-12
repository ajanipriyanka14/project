<?php

session_start();
include "config.php";

/* ================= CHECK CART ID ================= */

if (!isset($_GET['id']) && !isset($_POST['cart_id'])) {
    die("Cart ID Not Found");
}

if (isset($_POST['cart_id'])) {
    $cart_id = intval($_POST['cart_id']);
} else {
    $cart_id = intval($_GET['id']);
}

/* ================= GET CART ================= */

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

/* ================= CART DETAILS ================= */

$cake_name = $cart['cake_name'];
$quantity  = $cart['quantity'];
$price     = $cart['price'];
$total     = $cart['total'];

/* ================= GET CAKE DETAILS ================= */

$cake_name_safe = mysqli_real_escape_string($conn, $cake_name);

$cake_query = mysqli_query(
    $conn,
    "SELECT * FROM cake
     WHERE cake_name='$cake_name_safe'
     LIMIT 1"
);

if (!$cake_query) {
    die("Cake Query Error: " . mysqli_error($conn));
}

$image = "";
$category = "";
$flavour = "";
$weight = "";

if (mysqli_num_rows($cake_query) > 0) {

    $cake = mysqli_fetch_assoc($cake_query);

    $image    = $cake['image'];
    $category = $cake['category'];
    $flavour  = $cake['flavour'];
    $weight   = $cake['weight'];
}


/* ================= SAVE CUSTOMER DETAILS ================= */

if (isset($_POST['checkout'])) {

    $customer_name = mysqli_real_escape_string(
        $conn,
        trim($_POST['customer_name'])
    );

    $email = mysqli_real_escape_string(
        $conn,
        trim($_POST['email'])
    );

    $mobile = mysqli_real_escape_string(
        $conn,
        trim($_POST['mobile'])
    );

    $address = mysqli_real_escape_string(
        $conn,
        trim($_POST['address'])
    );


    /* ================= VALIDATION ================= */

    if (
        empty($customer_name) ||
        empty($email) ||
        empty($mobile) ||
        empty($address)
    ) {

        echo "<script>
                alert('Please fill all customer details');
                window.history.back();
              </script>";

        exit();
    }


    /* ================= EMAIL VALIDATION ================= */

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        echo "<script>
                alert('Please enter a valid email address');
                window.history.back();
              </script>";

        exit();
    }


    /* ================= MOBILE VALIDATION ================= */

    if (!preg_match('/^[0-9]{10}$/', $mobile)) {

        echo "<script>
                alert('Please enter a valid 10 digit mobile number');
                window.history.back();
              </script>";

        exit();
    }


    /* ================= UPDATE CART ================= */

    $update = mysqli_query(
        $conn,
        "UPDATE carts SET
            customer_name='$customer_name',
            email='$email',
            mobile='$mobile',
            address='$address'
         WHERE id='$cart_id'"
    );


    if (!$update) {

        die(
            "Customer Details Error: " .
            mysqli_error($conn)
        );

    }


    /* ================= GO TO PAYMENT ================= */

    header(
        "Location: payment.php?cart_id=" . $cart_id
    );

    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Checkout | Swiffin Cake Shop</title>


<!-- Bootstrap -->

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">


<!-- Font Awesome -->

<link
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
rel="stylesheet">


<style>

*{
    box-sizing:border-box;
}

body{
    margin:0;
    padding:0;
    background:#000;
    color:#fff;
    font-family:Arial, sans-serif;
}


/* ================= HEADER ================= */

.checkout-header{
    text-align:center;
    padding:40px 15px 20px;
}

.checkout-header h1{
    color:#E88F2A;
    font-size:36px;
    font-weight:bold;
}

.checkout-header p{
    color:#aaa;
}


/* ================= CONTAINER ================= */

.checkout-container{
    width:92%;
    max-width:1100px;
    margin:20px auto 60px;
}


/* ================= BOX ================= */

.checkout-box{
    background:#111;
    border:1px solid #E88F2A;
    border-radius:20px;
    padding:30px;

    box-shadow:
        0 0 20px rgba(232,143,42,.30),
        0 0 50px rgba(232,143,42,.10);
}


/* ================= TITLE ================= */

.section-title{
    color:#E88F2A;
    font-size:22px;
    font-weight:bold;
    margin-bottom:20px;
}


/* ================= CAKE IMAGE ================= */

.cake-image{
    width:100%;
    height:300px;
    object-fit:cover;
    border-radius:15px;
    border:2px solid #E88F2A;
}


/* ================= DETAILS ================= */

.detail{
    background:#1b1b1b;
    border:1px solid #333;
    border-radius:8px;
    padding:12px 15px;
    margin-bottom:10px;
}

.detail span{
    display:block;
    color:#aaa;
    font-size:13px;
    margin-bottom:3px;
}

.detail strong{
    color:#fff;
    font-size:16px;
}

.price{
    color:#E88F2A !important;
    font-size:22px !important;
    font-weight:bold;
}


/* ================= FORM ================= */

.form-label{
    color:#E88F2A;
    font-weight:bold;
}

.form-control{
    background:#080808;
    color:#fff;
    border:1px solid #444;
    padding:12px;
    border-radius:8px;
}

.form-control::placeholder{
    color:#777;
}

.form-control:focus{
    background:#080808;
    color:#fff;
    border-color:#E88F2A;
    box-shadow:0 0 8px rgba(232,143,42,.35);
}


/* ================= PAYMENT BUTTON ================= */

.payment-btn{
    width:100%;
    background:#E88F2A;
    color:#fff;
    border:none;
    padding:14px;
    border-radius:30px;
    font-size:18px;
    font-weight:bold;
    margin-top:20px;
}

.payment-btn:hover{
    background:#d67d18;
    color:#fff;
}


/* ================= BACK ================= */

.back-link{
    display:block;
    text-align:center;
    margin-top:20px;
    color:#E88F2A;
    text-decoration:none;
    font-weight:bold;
}

.back-link:hover{
    color:#fff;
}


/* ================= MOBILE ================= */

@media(max-width:768px){

    .checkout-box{
        padding:20px;
    }

    .checkout-header h1{
        font-size:28px;
    }

}

</style>

</head>


<body>


<!-- ================= HEADER ================= -->

<div class="checkout-header">

    <h1>

        <i class="fas fa-shopping-bag"></i>

        Checkout

    </h1>

    <p>
        Complete your delivery details
    </p>

</div>



<!-- ================= MAIN ================= -->

<div class="checkout-container">

<div class="checkout-box">

<div class="row g-4">


<!-- ================= LEFT : CAKE ================= -->

<div class="col-lg-5">

    <div class="section-title">

        <i class="fas fa-cake-candles"></i>

        Your Cake

    </div>


    <?php if (!empty($image)) { ?>

        <img
            src="img/<?php echo htmlspecialchars($image); ?>"
            class="cake-image"
            alt="<?php echo htmlspecialchars($cake_name); ?>"
        >

    <?php } else { ?>

        <div
            style="
            height:300px;
            border:2px solid #E88F2A;
            border-radius:15px;
            display:flex;
            align-items:center;
            justify-content:center;
            color:#888;
            "
        >

            <i class="fas fa-cake-candles fa-4x"></i>

        </div>

    <?php } ?>


    <h3
        style="
        color:#E88F2A;
        margin-top:20px;
        font-weight:bold;
        "
    >

        <?php echo htmlspecialchars($cake_name); ?>

    </h3>


    <div class="detail">

        <span>Category</span>

        <strong>

            <?php echo htmlspecialchars($category); ?>

        </strong>

    </div>


    <div class="detail">

        <span>Flavour</span>

        <strong>

            <?php echo htmlspecialchars($flavour); ?>

        </strong>

    </div>


    <div class="detail">

        <span>Weight</span>

        <strong>

            <?php echo htmlspecialchars($weight); ?>

        </strong>

    </div>


    <div class="detail">

        <span>Quantity</span>

        <strong>

            <?php echo htmlspecialchars($quantity); ?>

        </strong>

    </div>


    <div class="detail">

        <span>Price</span>

        <strong class="price">

            ₹<?php echo number_format($price,2); ?>

        </strong>

    </div>


    <div class="detail">

        <span>Total Amount</span>

        <strong class="price">

            ₹<?php echo number_format($total,2); ?>

        </strong>

    </div>

</div>



<!-- ================= RIGHT : CUSTOMER ================= -->

<div class="col-lg-7">


    <div class="section-title">

        <i class="fas fa-user"></i>

        Customer Details

    </div>


    <form method="POST" action="checkout.php">


        <input
            type="hidden"
            name="cart_id"
            value="<?php echo $cart_id; ?>"
        >


        <!-- NAME -->

        <div class="mb-3">

            <label class="form-label">
                Customer Name
            </label>

            <input
                type="text"
                name="customer_name"
                class="form-control"
                placeholder="Enter Your Name"
                required
            >

        </div>


        <!-- EMAIL -->

        <div class="mb-3">

            <label class="form-label">
                Email
            </label>

            <input
                type="email"
                name="email"
                class="form-control"
                placeholder="Enter Your Email"
                required
            >

        </div>


        <!-- MOBILE -->

        <div class="mb-3">

            <label class="form-label">
                Mobile Number
            </label>

            <input
                type="text"
                name="mobile"
                class="form-control"
                placeholder="Enter 10 Digit Mobile Number"
                maxlength="10"
                pattern="[0-9]{10}"
                required
            >

        </div>


        <!-- ADDRESS -->

        <div class="mb-3">

            <label class="form-label">
                Delivery Address
            </label>

            <textarea
                name="address"
                class="form-control"
                rows="4"
                placeholder="Enter Your Complete Delivery Address"
                required
            ></textarea>

        </div>


        <!-- PROCEED TO PAYMENT -->

        <button
            type="submit"
            name="checkout"
            class="payment-btn"
        >

            <i class="fas fa-credit-card"></i>

            Proceed To Payment

        </button>


    </form>


    <!-- BACK TO CART -->

    <a
        href="carts.php?id=<?ph