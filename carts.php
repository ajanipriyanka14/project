
<?php
session_start();
include "config.php";

if (!isset($_GET['id'])) {
    die("Cart ID Not Found");
}

$cart_id = intval($_GET['id']);

/* ================= GET CART ================= */

$query = mysqli_query(
    $conn,
    "SELECT * FROM carts WHERE id='$cart_id'"
);

if (!$query) {
    die("Database Error: " . mysqli_error($conn));
}

if (mysqli_num_rows($query) == 0) {
    die("Cart Record Not Found");
}

$cart = mysqli_fetch_assoc($query);


/* ================= CART DATA ================= */

$cake_name = $cart['cake_name'];
$quantity = $cart['quantity'];
$price = $cart['price'];
$total = $cart['total'];


/* ================= GET CAKE DETAILS ================= */

$cake_query = mysqli_query(
    $conn,
    "SELECT * FROM cake WHERE cake_name='" .
    mysqli_real_escape_string($conn, $cake_name) .
    "' LIMIT 1"
);

if (!$cake_query) {
    die("Cake Query Error: " . mysqli_error($conn));
}


/* ================= DEFAULT VALUES ================= */

$image = "";
$category = "";
$flavour = "";
$weight = "";


/* ================= CAKE FOUND ================= */

if (mysqli_num_rows($cake_query) > 0) {

    $cake = mysqli_fetch_assoc($cake_query);

    $image = $cake['image'];
    $category = $cake['category'];
    $flavour = $cake['flavour'];
    $weight = $cake['weight'];
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Your Cart | Swiffin Cake Shop</title>

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
    font-family:Arial,sans-serif;
}

/* ================= HEADER ================= */

.cart-header{
    text-align:center;
    padding:35px 15px 15px;
}

.cart-header h1{
    color:#E88F2A;
    font-size:35px;
    font-weight:bold;
}

.cart-header p{
    color:#aaa;
}

/* ================= MAIN ================= */

.cart-container{
    width:92%;
    max-width:1100px;
    margin:25px auto 60px;
}

/* ================= CART BOX ================= */

.cart-box{
    background:#111;
    border:1px solid #E88F2A;
    border-radius:20px;
    padding:30px;
    box-shadow:
        0 0 20px rgba(232,143,42,.30),
        0 0 50px rgba(232,143,42,.10);
}

/* ================= IMAGE ================= */

.cake-image{
    width:100%;
    height:350px;
    object-fit:cover;
    border-radius:15px;
    border:2px solid #E88F2A;
}

/* ================= CAKE NAME ================= */

.cake-name{
    color:#E88F2A;
    font-size:30px;
    font-weight:bold;
    margin-bottom:20px;
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
    color:#aaa;
    display:block;
    font-size:13px;
    margin-bottom:3px;
}

.detail strong{
    color:#fff;
    font-size:16px;
}

/* ================= PRICE ================= */

.price{
    color:#E88F2A !important;
    font-size:24px !important;
    font-weight:bold;
}

/* ================= FORM ================= */

.form-title{
    color:#E88F2A;
    font-size:20px;
    font-weight:bold;
    margin-top:25px;
    margin-bottom:15px;
}

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

/* ================= TOTAL ================= */

.total-box{
    background:#000 !important;
    color:#E88F2A !important;
    border:1px solid #E88F2A !important;
    font-size:22px;
    font-weight:bold;
}

<!-- ================= ADD TO CART BUTTON ================= --> 
<form method="POST" action="cart_insert.php">
 <!-- Cake Name --> 
 <input type="hidden" name="cake_name" value="<?php echo htmlspecialchars($cake_name); ?>" >
 <!-- Quantity -->
 <input type="hidden" name="quantity" value="<?php echo htmlspecialchars($quantity); ?>" > 
 <!-- Price --> <input type="hidden" name="price" value="<?php echo htmlspecialchars($price); ?>" > 
 <!-- Total --> <input type="hidden" name="total" value="<?php echo htmlspecialchars($total); ?>" >
 <button type="submit" name="add_to_cart" class="add-cart-btn" > <i class="fas fa-cart-plus">
 </i> Add To Cart </button> </form>

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

    .cart-container{
        width:95%;
    }

    .cart-box{
        padding:20px;
    }

    .cake-image{
        height:280px;
        margin-bottom:25px;
    }

    .cake-name{
        font-size:25px;
    }

}

</style>

</head>

<body>


<!-- ================= HEADER ================= -->

<div class="cart-header">

    <h1>
        <i class="fas fa-shopping-cart"></i>
        Your Cart
    </h1>

    <p>
        Review your cake before checkout
    </p>

</div>


<!-- ================= CART ================= -->

<div class="cart-container">

<div class="cart-box">

<div class="row g-4">


<!-- ================= IMAGE ================= -->

<div class="col-lg-5">

    <?php if (!empty($image)) { ?>

        <img
            src="img/<?php echo htmlspecialchars($image); ?>"
            class="cake-image"
            alt="<?php echo htmlspecialchars($cake_name); ?>"
        >

    <?php } else { ?>

        <div
            style="
            height:350px;
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

</div>


<!-- ================= DETAILS ================= -->

<div class="col-lg-7">

    <h2 class="cake-name">

        <?php echo htmlspecialchars($cake_name); ?>

    </h2>


    <!-- CATEGORY -->

    <div class="detail">

        <span>Category</span>

        <strong>
            <?php echo htmlspecialchars($category); ?>
        </strong>

    </div>


    <!-- FLAVOUR -->

    <div class="detail">

        <span>Flavour</span>

        <strong>
            <?php echo htmlspecialchars($flavour); ?>
        </strong>

    </div>


    <!-- WEIGHT -->

    <div class="detail">

        <span>Weight</span>

        <strong>
            <?php echo htmlspecialchars($weight); ?>
        </strong>

    </div>


    <!-- PRICE -->

    <div class="detail">

        <span>Price</span>

        <strong class="price">

            ₹<?php echo number_format($price,2); ?>

        </strong>

    </div>


    <!-- QUANTITY -->

    <div class="detail">

        <span>Quantity</span>

        <strong>

            <?php echo htmlspecialchars($quantity); ?>

        </strong>

    </div>


    <!-- TOTAL -->

    <div class="detail">

        <span>Total Amount</span>

        <strong class="price">

            ₹<?php echo number_format($total,2); ?>

        </strong>

    </div>


    <hr style="border-color:#444;">


    <!-- ================= USER FORM ================= -->

    <div class="form-title">

        <i class="fas fa-user"></i>
        Customer Details

    </div>


    <form method="POST" action="checkout.php">


        <!-- CART ID -->

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
                value=""
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
                value=""
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
                placeholder="Enter Mobile Number"
                value=""
                maxlength="10"
                required
            >

        </div>


        <!-- ADDRESS -->

        <div class="mb-3">

            <label class="form-label">
                Address
            </label>

            <textarea
                name="address"
                class="form-control"
                rows="3"
                placeholder="Enter Your Address"
                required
            ></textarea>

        </div>


      <a href="checkout.php?id=<?php echo $cart_id; ?>"
   class="checkout-btn"
   style="
       display:block;
       width:100%;
       text-align:center;
       text-decoration:none;
       background:#E88F2A;
       color:#fff;
       padding:14px;
       border-radius:30px;
       font-size:18px;
       font-weight:bold;
       margin-top:20px;
   ">

    <i class="fas fa-credit-card"></i>
    Proceed To Checkout

</a>


    </form>


    <!-- BACK -->

    <a
        href="product.php"
        class="back-link"
    >

        <i class="fas fa-arrow-left"></i>

        Continue Shopping

    </a>

</div>

</div>

</div>

</div>


</body>

</html>

