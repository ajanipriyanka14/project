
<?php
session_start();
include("config.php");


/* =====================================================
   CUSTOMER LOGIN SESSION
===================================================== */

$customer_logged_in = false;
$customer_name = "";
$customer_email = "";

if (
    isset($_SESSION['customer']) &&
    $_SESSION['customer'] === true
) {
    $customer_logged_in = true;

    $customer_name =
        $_SESSION['customer_name'] ?? "";

    $customer_email =
        $_SESSION['customer_email'] ?? "";
}


/* =====================================================
   DYNAMIC PARENT CATEGORIES
===================================================== */

$parent_categories = [];

$sql = "
    SELECT id, category_name, parent_id, status
    FROM category
    WHERE parent_id = 0
    AND status = 'Active'
    ORDER BY category_name ASC
";

$cat_result = mysqli_query($conn, $sql);

if ($cat_result) {

    while ($row = mysqli_fetch_assoc($cat_result)) {
        $parent_categories[] = $row;
    }
}


/* =====================================================
   GET CHILD CATEGORIES
===================================================== */

function getSubCategories($conn, $parent_id)
{
    $sub = [];

    $parent_id = intval($parent_id);

    $sql = "
        SELECT id, category_name, parent_id, status
        FROM category
        WHERE parent_id = $parent_id
        AND status = 'Active'
        ORDER BY category_name ASC
    ";

    $result = mysqli_query($conn, $sql);

    if ($result) {

        while ($row = mysqli_fetch_assoc($result)) {
            $sub[] = $row;
        }
    }

    return $sub;
}


/* =====================================================
   SELECTED CATEGORY
===================================================== */

$selected_category =
    intval($_GET['category'] ?? 0);

$result = false;


/* =====================================================
   PRODUCT QUERY
===================================================== */

if ($selected_category > 0) {

    $cat_stmt = mysqli_prepare(
        $conn,
        "SELECT id, parent_id, category_name
         FROM category
         WHERE id = ?
         AND status = 'Active'
         LIMIT 1"
    );

    mysqli_stmt_bind_param(
        $cat_stmt,
        "i",
        $selected_category
    );

    mysqli_stmt_execute($cat_stmt);

    $cat_result =
        mysqli_stmt_get_result($cat_stmt);

    $selected_cat =
        mysqli_fetch_assoc($cat_result);

    mysqli_stmt_close($cat_stmt);


    if ($selected_cat) {

        $category_name =
            trim($selected_cat['category_name']);

        $parent_id =
            intval($selected_cat['parent_id']);


        /* =================================================
           CHILD CATEGORY
        ================================================= */

        if ($parent_id > 0) {

            $stmt = mysqli_prepare(
                $conn,

                "SELECT
                    cake.*,
                    category.category_name AS category_display_name,
                    flavor.flavor_name AS flavor_display_name

                 FROM cake

                 LEFT JOIN category
                 ON cake.category_id = category.id

                 LEFT JOIN flavor
                 ON cake.flavor_id = flavor.id

                 WHERE
                    cake.category_id = ?

                    OR

                    LOWER(TRIM(cake.category))
                    =
                    LOWER(TRIM(?))

                 ORDER BY cake.id DESC"
            );

            mysqli_stmt_bind_param(
                $stmt,
                "is",
                $selected_category,
                $category_name
            );

            mysqli_stmt_execute($stmt);

            $result =
                mysqli_stmt_get_result($stmt);
        }


        /* =================================================
           PARENT CATEGORY
        ================================================= */

        else {

            $stmt = mysqli_prepare(
                $conn,

                "SELECT
                    cake.*,
                    category.category_name AS category_display_name,
                    flavor.flavor_name AS flavor_display_name

                 FROM cake

                 LEFT JOIN category
                 ON cake.category_id = category.id

                 LEFT JOIN flavor
                 ON cake.flavor_id = flavor.id

                 WHERE

                    cake.category_id = ?

                    OR

                    cake.category_id IN
                    (
                        SELECT id
                        FROM category
                        WHERE parent_id = ?
                        AND status = 'Active'
                    )

                    OR

                    LOWER(TRIM(cake.category))
                    =
                    LOWER(TRIM(?))

                    OR

                    LOWER(TRIM(cake.category)) IN
                    (
                        SELECT
                            LOWER(TRIM(c.category_name))
                        FROM category c
                        WHERE
                            (
                                c.id = ?
                                OR c.parent_id = ?
                            )
                            AND c.status = 'Active'
                    )

                 ORDER BY cake.id DESC"
            );

            mysqli_stmt_bind_param(
                $stmt,
                "iissi",
                $selected_category,
                $selected_category,
                $category_name,
                $selected_category,
                $selected_category
            );

            mysqli_stmt_execute($stmt);

            $result =
                mysqli_stmt_get_result($stmt);
        }
    }


    /* =================================================
       INVALID CATEGORY
    ================================================= */

    else {

        $result = mysqli_query(
            $conn,

            "SELECT
                cake.*,
                category.category_name AS category_display_name,
                flavor.flavor_name AS flavor_display_name

             FROM cake

             LEFT JOIN category
             ON cake.category_id = category.id

             LEFT JOIN flavor
             ON cake.flavor_id = flavor.id

             ORDER BY cake.id DESC"
        );
    }
}


/* =====================================================
   ALL PRODUCTS
===================================================== */

else {

    $result = mysqli_query(
        $conn,

        "SELECT
            cake.*,
            category.category_name AS category_display_name,
            flavor.flavor_name AS flavor_display_name

         FROM cake

         LEFT JOIN category
         ON cake.category_id = category.id

         LEFT JOIN flavor
         ON cake.flavor_id = flavor.id

         ORDER BY cake.id DESC"
    );
}


/* =====================================================
   DATABASE ERROR
===================================================== */

if (!$result) {

    die(
        "Cake Database Error: " .
        mysqli_error($conn)
    );
}

?>


<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="utf-8">

<title>
Products | SWIFFIN CAKE SHOP
</title>

<meta
    name="viewport"
    content="width=device-width, initial-scale=1.0"
>

<link
    href="img/favicon.ico"
    rel="icon"
>


<!-- =====================================================
     GOOGLE FONTS
===================================================== -->

<link
    rel="preconnect"
    href="https://fonts.googleapis.com"
>

<link
    rel="preconnect"
    href="https://fonts.gstatic.com"
    crossorigin
>

<link
    href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;500;600;700&family=Oswald:wght@500;600;700&family=Pacifico&display=swap"
    rel="stylesheet"
>


<!-- =====================================================
     FONT AWESOME
===================================================== -->

<link
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css"
    rel="stylesheet"
>


<!-- =====================================================
     BOOTSTRAP ICONS
===================================================== -->

<link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css"
    rel="stylesheet"
>


<!-- =====================================================
     BOOTSTRAP
===================================================== -->

<link
    href="css/bootstrap.min.css"
    rel="stylesheet"
>


<!-- =====================================================
     TEMPLATE CSS
===================================================== -->

<link
    href="css/style.css"
    rel="stylesheet"
>


<style>

/* =====================================================
   GENERAL
===================================================== */

* {
    box-sizing: border-box;
}

html {
    scroll-behavior: smooth;
}

body {

    margin: 0;
    padding: 0;



    color: #fff;

    font-family:
        "Open Sans",
        Arial,
        Helvetica,
        sans-serif;
}

:root {

    --orange: #E88F2A;

    --orange-dark: #d67d18;

    --black: #0b0b0b;

    --dark: #111111;

    --dark-card: #171717;

    --border: #292929;

    --light: #FAF3EB;
}


/* =====================================================
   TOPBAR
===================================================== */

.container-fluid {
    margin-top: 0 !important;
}

.topbar {
    background: #fff !important;
}

.topbar h5,
.topbar h6,
.topbar span {
    color: #222 !important;
}


/* =====================================================
   WELCOME USER
===================================================== */

.welcome-user {

    display: flex;

    align-items: center;

    gap: 10px;
}

.user-icon {

    width: 42px;

    height: 42px;

    border-radius: 50%;

    background: var(--orange);

    color: #fff;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 18px;

    box-shadow:
        0 4px 12px rgba(232,143,42,0.30);
}

.welcome-text {

    display: flex;

    flex-direction: column;

    line-height: 1.2;
}

.welcome-text small {

    color: #888;

    font-size: 10px;

    text-transform: uppercase;

    letter-spacing: 1px;
}

.welcome-text strong {

    color: #222;

    font-size: 14px;

    max-width: 140px;

    overflow: hidden;

    text-overflow: ellipsis;

    white-space: nowrap;
}


/* =====================================================
   GUEST
===================================================== */

.welcome-guest {

    display: flex;

    align-items: center;

    gap: 8px;

    color: #555;

    font-weight: 600;
}

.welcome-guest i {

    font-size: 28px;

    color: var(--orange);
}


/* =====================================================
   NAVBAR
===================================================== */

.navbar {

    background: #fff !important;

    min-height: 75px;

    border: none !important;
}

.navbar .nav-link {

    color: #222 !important;

    font-weight: 700 !important;

    transition: 0.3s;
}

.navbar .nav-link:hover {

    color: var(--orange) !important;
}

.navbar .nav-link.active {

    color: var(--orange) !important;
}

.navbar-nav .nav-link {

    font-weight: 700 !important;
}


/* =====================================================
   SIGN IN
===================================================== */

.signin-btn {

    display: inline-flex;

    align-items: center;

    gap: 7px;

    background: var(--orange) !important;

    color: #fff !important;

    border-radius: 25px !important;

    padding: 9px 18px !important;

    font-weight: 700;

    border: 1px solid var(--orange) !important;

    transition: 0.3s;
}

.signin-btn:hover {

    background: #fff !important;

    color: var(--orange) !important;
}


/* =====================================================
   REGISTER
===================================================== */

.register-btn {

    display: inline-flex;

    align-items: center;

    gap: 7px;

    border: 1px solid var(--orange) !important;

    color: var(--orange) !important;

    background: #fff !important;

    border-radius: 25px !important;

    padding: 9px 18px !important;

    font-weight: 700;

    transition: 0.3s;
}

.register-btn:hover {

    background: var(--orange) !important;

    color: #fff !important;
}


/* =====================================================
   USER DROPDOWN
===================================================== */

.user-dropdown-btn {

    border: none;

    background: transparent;

    display: flex;

    align-items: center;

    gap: 8px;

    color: #222;

    font-weight: 600;

    cursor: pointer;

    padding: 7px 12px;

    border-radius: 25px;

    transition: 0.3s;
}

.user-dropdown-btn:hover {

    background: var(--light);

    color: var(--orange);
}

.user-dropdown-btn i {

    color: var(--orange);

    font-size: 21px;
}

.user-menu {

    min-width: 230px;

    padding: 10px;

    border: none;

    border-radius: 13px;

    box-shadow:
        0 10px 30px rgba(0,0,0,0.16);
}

.user-menu .user-info {

    padding: 12px;

    border-bottom: 1px solid #eee;

    margin-bottom: 5px;
}

.user-menu .user-info strong {

    display: block;

    color: #222;

    font-size: 15px;
}

.user-menu .user-info small {

    color: #888;

    font-size: 11px;

    word-break: break-all;
}

.user-menu .dropdown-item {

    border-radius: 8px;

    padding: 10px 12px;
}

.user-menu .logout-item {

    color: #dc3545;
}

.user-menu .logout-item:hover {

    background: #fff0f0;

    color: #dc3545;
}


/* =====================================================
   CART
===================================================== */

.cart-link {

    display: inline-flex !important;

    align-items: center;

    gap: 6px;
}

.cart-link i {

    color: var(--orange);

    font-size: 18px;
}


/* =====================================================
   CATEGORY DROPDOWN
===================================================== */

.dropdown-submenu {

    position: relative;
}

.dropdown-submenu > .dropdown-menu {

    top: 0;

    left: 100%;

    margin-top: 0;

    display: none;
}

.dropdown-submenu:hover > .dropdown-menu {

    display: block;
}

.dropdown-menu {

    min-width: 220px;

    border: none;

    border-radius: 10px;

    box-shadow:
        0 10px 30px rgba(0,0,0,0.15);
}

.dropdown-item {

    padding: 10px 15px;

    font-size: 14px;

    font-weight: 600;
}

.dropdown-item:hover {

    color: var(--orange);

    background: var(--light);
}


/* =====================================================
   PRODUCT HERO
===================================================== */

.product-hero {

    position: relative;

    min-height: 390px;

    display: flex;

    align-items: center;

    justify-content: center;

    text-align: center;

    overflow: hidden;

    background:

        linear-gradient(
            rgba(0,0,0,.68),
            rgba(0,0,0,.82)
        ),

        url("img/ca.jpg");

    background-size: cover;

    background-position: center;
}

.product-hero::before {

    content: "";

    position: absolute;

    width: 250px;

    height: 250px;

    border-radius: 50%;

    background:
        rgba(232,143,42,.12);

    filter: blur(30px);

    top: -80px;

    left: 8%;
}

.product-hero-content {

    position: relative;

    z-index: 2;

    max-width: 800px;

    padding: 40px 20px;
}

.product-hero .small-title {

    color: var(--orange);

    font-size: 14px;

    font-weight: 700;

    letter-spacing: 4px;

    text-transform: uppercase;

    margin-bottom: 15px;
}

.product-hero h1 {

    font-family: "Oswald", sans-serif;

    font-size: clamp(42px, 6vw, 70px);

    font-weight: 700;

    line-height: 1.1;

    margin-bottom: 18px;

    color: #fff;
}

.product-hero h1 span {

    color: var(--orange);
}

.product-hero p {

    color: #d5d5d5;

    font-size: 17px;

    max-width: 620px;

    margin: 0 auto;

    line-height: 1.8;
}


/* =====================================================
   PRODUCT SECTION
===================================================== */

.products-section {

    background: #0b0b0b;

    padding: 75px 0 90px;
}

.section-heading {

    text-align: center;

    margin-bottom: 48px;
}

.section-heading .eyebrow {

    color: var(--orange);

    font-size: 13px;

    font-weight: 700;

    letter-spacing: 3px;

    text-transform: uppercase;

    margin-bottom: 8px;
}

.section-heading h2 {

    color: #fff;

    font-family: "Oswald", sans-serif;

    font-size: 42px;

    font-weight: 600;

    margin-bottom: 12px;
}

.section-heading p {

    color: #999;

    margin: 0;
}


/* =====================================================
   PRODUCT CARD
===================================================== */

.product-column {

    margin-bottom: 30px;
}

.product-card {

    position: relative;

    background: #151515;

    border: 1px solid #292929;

    border-radius: 18px;

    overflow: hidden;

    height: 100%;

    transition:
        transform .35s ease,
        box-shadow .35s ease,
        border-color .35s ease;
}

.product-card:hover {

    transform: translateY(-8px);

    border-color: rgba(232,143,42,.65);

    box-shadow:
        0 18px 45px rgba(0,0,0,.45),
        0 8px 25px rgba(232,143,42,.13);
}


/* =====================================================
   PRODUCT IMAGE
===================================================== */

.product-image-wrap {

    position: relative;

    overflow: hidden;

    background: #111;
}

.product-card img {

    width: 100%;

    height: 280px;

    object-fit: cover;

    display: block;

    transition:
        transform .55s ease;
}

.product-card:hover img {

    transform: scale(1.06);
}

.image-overlay {

    position: absolute;

    inset: 0;

    background:
        linear-gradient(
            to top,
            rgba(0,0,0,.45),
            transparent 45%
        );

    pointer-events: none;
}


/* =====================================================
   OFFER BADGE
===================================================== */

.offer-badge {

    position: absolute;

    top: 16px;

    left: 16px;

    z-index: 2;

    background: var(--orange);

    color: #fff;

    padding: 7px 13px;

    border-radius: 20px;

    font-size: 12px;

    font-weight: 700;

    letter-spacing: .5px;

    box-shadow:
        0 5px 15px rgba(0,0,0,.3);
}


/* =====================================================
   CARD BODY
===================================================== */

.product-card .card-body {

    padding: 24px 24px 25px;

    display: flex;

    flex-direction: column;
}

.product-card h4 {

    color: #fff;

    font-family: "Oswald", sans-serif;

    font-size: 25px;

    font-weight: 600;

    margin-bottom: 18px;

    min-height: 31px;
}


/* =====================================================
   PRODUCT DETAILS
===================================================== */

.product-details {

    border-top: 1px solid #282828;

    border-bottom: 1px solid #282828;

    padding: 14px 0;

    margin-bottom: 15px;
}

.detail-row {

    display: flex;

    justify-content: space-between;

    align-items: center;

    gap: 10px;

    margin-bottom: 9px;

    font-size: 13px;
}

.detail-row:last-child {

    margin-bottom: 0;
}

.detail-label {

    color: #888;
}

.detail-value {

    color: #ddd;

    font-weight: 600;

    text-align: right;
}


/* =====================================================
   DESCRIPTION
===================================================== */

.product-description {

    color: #999;

    font-size: 13px;

    line-height: 1.7;

    min-height: 45px;

    margin-bottom: 12px;
}


/* =====================================================
   STOCK
===================================================== */

.stock-status {

    font-size: 12px;

    font-weight: 700;

    margin-bottom: 8px;
}

.stock-status i {

    margin-right: 5px;
}


/* =====================================================
   PRICE
===================================================== */

.price-area {

    margin-top: auto;

    padding-top: 5px;

    margin-bottom: 15px;
}

.price {

    color: var(--orange);

    font-size: 27px;

    font-weight: 700;

    line-height: 1.2;
}

.old-price {

    color: #777;

    font-size: 16px;

    text-decoration: line-through;

    margin-right: 7px;

    font-weight: 500;
}

.save-text {

    display: block;

    color: #68c47a;

    font-size: 11px;

    font-weight: 600;

    margin-top: 5px;
}


/* =====================================================
   OFFER BOX
===================================================== */

.offer-box {

    background:
        linear-gradient(
            135deg,
            #241507,
            #17110b
        );

    border: 1px solid rgba(232,143,42,.35);

    border-radius: 11px;

    padding: 12px 13px;

    margin-bottom: 15px;
}

.offer-title {

    color: var(--orange);

    font-size: 14px;

    font-weight: 700;

    margin-bottom: 3px;
}

.offer-description {

    color: #bbb;

    font-size: 12px;

    line-height: 1.5;
}

.offer-date {

    color: #777;

    font-size: 10px;

    margin-top: 5px;
}


/* =====================================================
   BUTTONS
===================================================== */

.btn-order,
.btn-buy-now {

    width: 100%;

    border-radius: 30px;

    font-weight: 700;

    border: none;

    padding: 12px 15px;

    color: #fff;

    transition:
        transform .25s ease,
        background .25s ease,
        box-shadow .25s ease;
}

.btn-order {

    background: var(--orange);
}

.btn-buy-now {

    background: #252525;

    border: 1px solid #3b3b3b;
}

.btn-order:hover {

    background: var(--orange-dark);

    color: #fff;

    transform: translateY(-2px);

    box-shadow:
        0 8px 20px rgba(232,143,42,.25);
}

.btn-buy-now:hover {

    background: var(--orange);

    border-color: var(--orange);

    color: #fff;

    transform: translateY(-2px);
}


/* =====================================================
   OUT OF STOCK
===================================================== */

.out-stock {

    background: #292929;

    color: #777;

    width: 100%;

    border-radius: 30px;

    font-weight: 700;

    padding: 12px;

    border: 1px solid #3a3a3a;
}


/* =====================================================
   NO PRODUCTS
===================================================== */

.no-products {

    max-width: 600px;

    margin: 0 auto;

    text-align: center;

    padding: 70px 30px;

    background: #151515;

    border: 1px solid #292929;

    border-radius: 18px;
}

.no-products i {

    color: var(--orange);

    font-size: 45px;

    margin-bottom: 20px;
}

.no-products h3 {

    color: #fff;

    font-family: "Oswald", sans-serif;

    margin-bottom: 10px;
}

.no-products p {

    color: #888;

    margin: 0;
}


/* =====================================================
   MOBILE
===================================================== */

@media(max-width:991px) {

    .navbar .navbar-nav {

        align-items: stretch !important;

        padding-top: 10px;
    }

    .navbar .nav-link {

        padding: 10px 15px !important;
    }

    .navbar .signin-btn,
    .navbar .register-btn {

        display: inline-flex !important;

        width: auto !important;

        margin: 5px 10px !important;
    }

    .dropdown-submenu > .dropdown-menu {

        position: static !important;

        margin-left: 15px !important;

        box-shadow: none !important;
    }

    .product-hero {

        min-height: 350px;
    }

    .products-section {

        padding-top: 60px;
    }
}


@media(max-width:767px) {

    .topbar {

        padding-left: 15px !important;

        padding-right: 15px !important;
    }

    .product-hero h1 {

        font-size: 45px;
    }

    .product-hero p {

        font-size: 15px;
    }

    .section-heading h2 {

        font-size: 35px;
    }

    .product-card img {

        height: 250px;
    }
}


@media(max-width:576px) {

    .product-hero {

        min-height: 320px;
    }

    .product-hero h1 {

        font-size: 37px;
    }

    .product-hero .small-title {

        letter-spacing: 2px;
    }

    .products-section {

        padding: 50px 15px 65px;
    }

    .section-heading {

        margin-bottom: 35px;
    }

    .section-heading h2 {

        font-size: 31px;
    }

    .product-card img {

        height: 235px;
    }

    .product-card .card-body {

        padding: 21px;
    }
}

</style>

</head>


<body>


<!-- =====================================================
     TOPBAR
===================================================== -->

<div class="container-fluid px-5">

    <div class="row align-items-center">


        <!-- WELCOME -->

        <div class="col-lg-3">

            <?php if ($customer_logged_in) { ?>

                <div class="welcome-user">

                    <div class="user-icon">

                        <i class="fas fa-user"></i>

                    </div>

                    <div class="welcome-text">

                        <small>
                            Welcome
                        </small>

                        <strong>

                            <?php
                            echo htmlspecialchars(
                                $customer_name
                            );
                            ?>

                        </strong>

                    </div>

                </div>

            <?php } else { ?>

                <div class="welcome-guest">

                    <i class="fas fa-user-circle"></i>

                    <span>
                        Welcome Guest
                    </span>

                </div>

            <?php } ?>

        </div>


        <!-- LOGO -->

        <div class="col-lg-5 text-center py-2">

            <a
                href="index1.php"
                class="navbar-brand d-flex justify-content-center align-items-center"
            >

                <img
                    src="img/logo.png"
                    alt="SWIFFIN Logo"
                    width="60"
                    height="60"
                >

                <h1 class="m-0 ms-2 text-uppercase">

                    <span style="color:#ff9800;">
                        SWIFFIN
                    </span>

                </h1>

            </a>

        </div>


        <!-- CALL -->

        <div class="col-lg-4 text-end pe-4">

            <div class="d-inline-flex align-items-center">

                <i
                    class="bi bi-phone-vibrate fs-1 text-primary me-3"
                ></i>

                <div class="text-start">

                    <h6 class="text-uppercase mb-1">

                        CALL US

                    </h6>

                    <span
                        style="
                            color:#222 !important;
                            display:block !important;
                        "
                    >
                        +91 9876543210
                    </span>

                </div>

            </div>

        </div>

    </div>

</div>


<!-- =====================================================
     NAVBAR
===================================================== -->

<nav
    class="navbar navbar-expand-lg bg-white navbar-light shadow-sm sticky-top py-3"
>

    <div class="container">


        <!-- MOBILE -->

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbar"
            aria-controls="navbar"
            aria-expanded="false"
            aria-label="Toggle navigation"
        >

            <span class="navbar-toggler-icon"></span>

        </button>


        <div
            class="collapse navbar-collapse"
            id="navbar"
        >

            <ul class="navbar-nav ms-auto align-items-center">


                <!-- HOME -->

                <li class="nav-item">

                    <a
                        href="index1.php"
                        class="nav-link"
                    >
                        Home
                    </a>

                </li>


                <!-- ABOUT -->

                <li class="nav-item">

                    <a
                        href="about.php"
                        class="nav-link"
                    >
                        About
                    </a>

                </li>


                <!-- CAKES -->

                <li class="nav-item dropdown">

                    <div class="d-flex align-items-center">

                        <a
                            href="product.php"
                            class="nav-link active"
                            style="padding-right:2px;"
                        >
                            Cakes
                        </a>

                        <a
                            href="#"
                            class="nav-link dropdown-toggle"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                            style="padding-left:2px;"
                        ></a>


                        <ul class="dropdown-menu">

                            <?php foreach (
                                $parent_categories
                                as $parent
                            ) { ?>

                                <?php

                                $parent_id =
                                    intval(
                                        $parent['id']
                                    );

                                $sub_categories =
                                    getSubCategories(
                                        $conn,
                                        $parent_id
                                    );

                                ?>


                                <?php if (
                                    count(
                                        $sub_categories
                                    ) > 0
                                ) { ?>

                                    <li
                                        class="dropdown-submenu"
                                    >

                                        <a
                                            href="product.php?category=<?php echo $parent_id; ?>"
                                            class="dropdown-item dropdown-toggle"
                                        >

                                            <?php
                                            echo htmlspecialchars(
                                                $parent['category_name']
                                            );
                                            ?>

                                        </a>


                                        <ul class="dropdown-menu">

                                            <?php foreach (
                                                $sub_categories
                                                as $sub
                                            ) { ?>

                                                <li>

                                                    <a
                                                        href="product.php?category=<?php echo intval($sub['id']); ?>"
                                                        class="dropdown-item"
                                                    >

                                                        <?php
                                                        echo htmlspecialchars(
                                                            $sub['category_name']
                                                        );
                                                        ?>

                                                    </a>

                                                </li>

                                            <?php } ?>

                                        </ul>

                                    </li>

                                <?php } else { ?>

                                    <li>

                                        <a
                                            href="product.php?category=<?php echo $parent_id; ?>"
                                            class="dropdown-item"
                                        >

                                            <?php
                                            echo htmlspecialchars(
                                                $parent['category_name']
                                            );
                                            ?>

                                        </a>

                                    </li>

                                <?php } ?>

                            <?php } ?>

                        </ul>

                    </div>

                </li>
				
				<li class="nav-item">

                    <a
                        href="gallery.php"
                        class="nav-link fw-bold"
                    >
                        Gallery
                    </a>

                </li>


                <!-- CONTACT -->

                <li class="nav-item">

                    <a
                        href="contact.php"
                        class="nav-link"
                    >
                        Contact
                    </a>

                </li>
				  <!-- GALLERY -->

                


                <!-- CART -->

                <?php if ($customer_logged_in) { ?>

                    <li class="nav-item">

                        <a
                            href="carts.php"
                            class="nav-link cart-link"
                        >

                            <i class="fas fa-shopping-cart"></i>

                            Cart

                        </a>

                    </li>

                <?php } ?>


                <!-- LOGGED CUSTOMER -->

                <?php if ($customer_logged_in) { ?>

                    <li class="nav-item dropdown ms-2">

                        <button
                            class="user-dropdown-btn dropdown-toggle"
                            type="button"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                        >

                            <i class="fas fa-user-circle"></i>

                            <span>

                                <?php
                                echo htmlspecialchars(
                                    $customer_name
                                );
                                ?>

                            </span>

                        </button>


                        <ul
                            class="dropdown-menu dropdown-menu-end user-menu"
                        >

                            <li>

                                <div class="user-info">

                                    <strong>

                                        <?php
                                        echo htmlspecialchars(
                                            $customer_name
                                        );
                                        ?>

                                    </strong>

                                    <small>

                                        <?php
                                        echo htmlspecialchars(
                                            $customer_email
                                        );
                                        ?>

                                    </small>

                                </div>

                            </li>


                            <li>

                                <a
                                    href="#"
                                    class="dropdown-item"
                                >

                                    <i class="fas fa-user me-2"></i>

                                    My Profile

                                </a>

                            </li>


                            <li>

                                <a
                                    href="carts.php"
                                    class="dropdown-item"
                                >

                                    <i class="fas fa-shopping-cart me-2"></i>

                                    My Cart

                                </a>

                            </li>


                            <li>

                                <hr class="dropdown-divider">

                            </li>


                            <li>

                                <a
                                    href="logout.php"
                                    class="dropdown-item logout-item"
                                >

                                    <i class="fas fa-sign-out-alt me-2"></i>

                                    Sign Out

                                </a>

                            </li>

                        </ul>

                    </li>


                <?php } else { ?>


                    <!-- SIGN IN -->

                    <li class="nav-item ms-2">

                        <a
                            href="login.php"
                            class="btn signin-btn"
                        >

                            <i class="fas fa-sign-in-alt"></i>

                            Sign In

                        </a>

                    </li>


                    <!-- REGISTER -->

                    <li class="nav-item ms-2">

                        <a
                            href="register.php"
                            class="btn register-btn"
                        >

                            <i class="fas fa-user-plus"></i>

                            Register

                        </a>

                    </li>


                <?php } ?>


            </ul>

        </div>

    </div>

</nav>


<!-- =====================================================
     PRODUCT HERO
===================================================== -->

<section class="product-hero">

    <div class="product-hero-content">

        <div class="small-title">
            SWIFFIN CAKE SHOP
        </div>

        <h1>
            Fresh <span>&</span> Delicious Cakes
        </h1>

        <p>
            Discover our freshly baked cakes made with
            premium ingredients, beautiful designs and
            lots of love for every special celebration.
        </p>

    </div>

</section>


<!-- =====================================================
     PRODUCTS
===================================================== -->

<section class="products-section">

    <div class="container">


        <!-- SECTION TITLE -->

        <div class="section-heading">

            <div class="eyebrow">
                Our Collection
            </div>

            <h2>
                Choose Your Favourite Cake
            </h2>

            <p>
                Delicious moments start with the perfect cake.
            </p>

        </div>


        <div class="row">


<?php

/* =====================================================
   NO PRODUCTS
===================================================== */

if (mysqli_num_rows($result) == 0) {

?>

    <div class="col-12">

        <div class="no-products">

            <i class="fas fa-birthday-cake"></i>

            <h3>
                No Cakes Found
            </h3>

            <p>
                There are no cakes available in this category
                at the moment.
            </p>

        </div>

    </div>

<?php

}


/* =====================================================
   DISPLAY CAKES
===================================================== */

while (
    $row =
    mysqli_fetch_assoc($result)
) {

    $cake_id =
        intval(
            $row['id']
        );

    $cake_price =
        floatval(
            $row['price']
        );


    /* =================================================
       OFFER
    ================================================= */

    $offer = false;

    $today = date("Y-m-d");


    $offer_sql = "

        SELECT *

        FROM offers

        WHERE cake_id = '$cake_id'

        AND status = 'Active'

        AND start_date <= '$today'

        AND end_date >= '$today'

        ORDER BY id DESC

        LIMIT 1

    ";


    $offer_query =
        mysqli_query(
            $conn,
            $offer_sql
        );


    if (
        $offer_query &&
        mysqli_num_rows(
            $offer_query
        ) > 0
    ) {

        $offer =
            mysqli_fetch_assoc(
                $offer_query
            );
    }


    /* =================================================
       FINAL PRICE
    ================================================= */

    $final_price =
        $cake_price;

    $discount = 0;


    if ($offer) {

        $discount =
            intval(
                $offer['discount']
            );

        $discount_amount =
            (
                $cake_price *
                $discount
            ) / 100;

        $final_price =
            $cake_price -
            $discount_amount;
    }

?>


<!-- =================================================
     PRODUCT CARD
================================================== -->

<div class="col-xl-4 col-lg-4 col-md-6 product-column">

    <div class="product-card">


        <!-- IMAGE -->

        <div class="product-image-wrap">

            <?php if ($offer) { ?>

                <div class="offer-badge">

                    <i class="fas fa-tag"></i>

                    <?php
                    echo intval(
                        $offer['discount']
                    );
                    ?>% OFF

                </div>

            <?php } ?>


            <img
                src="img/<?php
                    echo htmlspecialchars(
                        $row['image']
                    );
                ?>"
                alt="<?php
                    echo htmlspecialchars(
                        $row['cake_name']
                    );
                ?>"
            >

            <div class="image-overlay"></div>

        </div>


        <!-- BODY -->

        <div class="card-body">


            <!-- CAKE NAME -->

            <h4>

                <?php
                echo htmlspecialchars(
                    $row['cake_name']
                );
                ?>

            </h4>


            <!-- DETAILS -->

            <div class="product-details">


                <!-- CATEGORY -->

                <div class="detail-row">

                    <span class="detail-label">
                        Category
                    </span>

                    <span class="detail-value">

                        <?php
                        echo htmlspecialchars(
                            $row[
                                'category_display_name'
                            ]
                            ??
                            $row[
                                'category'
                            ]
                            ??
                            'N/A'
                        );
                        ?>

                    </span>

                </div>


                <!-- FLAVOUR -->

                <div class="detail-row">

                    <span class="detail-label">
                        Flavour
                    </span>

                    <span class="detail-value">

                        <?php
                        echo htmlspecialchars(
                            $row[
                                'flavor_display_name'
                            ]
                            ??
                            $row[
                                'flavour'
                            ]
                            ??
                            'N/A'
                        );
                        ?>

                    </span>

                </div>


                <!-- WEIGHT -->

                <div class="detail-row">

                    <span class="detail-label">
                        Weight
                    </span>

                    <span class="detail-value">

                        <?php
                        echo htmlspecialchars(
                            $row['weight']
                        );
                        ?>

                    </span>

                </div>


            </div>


            <!-- DESCRIPTION -->

            <div class="product-description">

                <?php
                echo htmlspecialchars(
                    $row['description']
                );
                ?>

            </div>


            <!-- STOCK -->

            <div class="stock-status">

                <?php

                if (
                    intval(
                        $row['stock']
                    ) > 0
                ) {

                ?>

                    <span style="color:#68c47a;">

                        <i class="fas fa-check-circle"></i>

                        <?php
                        echo intval(
                            $row['stock']
                        );
                        ?>

                        Cakes Available

                    </span>

                <?php

                } else {

                ?>

                    <span style="color:#dc3545;">

                        <i class="fas fa-times-circle"></i>

                        Out of Stock

                    </span>

                <?php

                }

                ?>

            </div>


            <!-- OFFER -->

            <?php if ($offer) { ?>

                <div class="offer-box">

                    <div class="offer-title">

                        <?php
                        echo htmlspecialchars(
                            $offer['offer_title']
                        );
                        ?>

                    </div>

                    <div class="offer-description">

                        <?php
                        echo htmlspecialchars(
                            $offer['description']
                        );
                        ?>

                    </div>

                    <div class="offer-date">

                        Valid until

                        <?php
                        echo date(
                            "d M Y",
                            strtotime(
                                $offer['end_date']
                            )
                        );
                        ?>

                    </div>

                </div>

            <?php } ?>


            <!-- PRICE -->

            <div class="price-area">

                <?php if ($offer) { ?>

                    <div class="price">

                        <span class="old-price">

                            ₹<?php
                            echo number_format(
                                $cake_price,
                                2
                            );
                            ?>

                        </span>

                        ₹<?php
                        echo number_format(
                            $final_price,
                            2
                        );
                        ?>

                    </div>

                    <span class="save-text">

                        You save
                        ₹<?php
                        echo number_format(
                            $cake_price -
                            $final_price,
                            2
                        );
                        ?>

                    </span>

                <?php } else { ?>

                    <div class="price">

                        ₹<?php
                        echo number_format(
                            $cake_price,
                            2
                        );
                        ?>

                    </div>

                <?php } ?>

            </div>


            <!-- =================================================
                 CART / BUY NOW
            ================================================== -->

            <?php

            if (

                intval(
                    $row['stock']
                ) > 0

                &&

                $row['status']
                == "Available"

            ) {

            ?>


                <!-- ADD TO CART -->

                <form
                    method="POST"
                    action="cart_insert.php"
                >

                    <input
                        type="hidden"
                        name="cake_id"
                        value="<?php
                            echo $cake_id;
                        ?>"
                    >

                    <input
                        type="hidden"
                        name="quantity"
                        value="1"
                    >

                    <button
                        type="submit"
                        name="add_to_cart"
                        class="btn btn-order"
                    >

                        <i class="fas fa-shopping-cart me-2"></i>

                        Add To Cart

                    </button>

                </form>


                <!-- BUY NOW -->

                <form
                    method="POST"
                    action="buy_now.php"
                >

                    <input
                        type="hidden"
                        name="cake_id"
                        value="<?php
                            echo $cake_id;
                        ?>"
                    >

                    <input
                        type="hidden"
                        name="quantity"
                        value="1"
                    >

                    <button
                        type="submit"
                        name="buy_now"
                        class="btn btn-buy-now mt-2"
                    >

                        <i class="fas fa-bolt me-2"></i>

                        Buy Now

                    </button>

                </form>


            <?php } else { ?>


                <!-- OUT OF STOCK -->

                <button
                    class="out-stock"
                    disabled
                >

                    <i class="fas fa-times-circle me-2"></i>

                    Out of Stock

                </button>


            <?php } ?>


        </div>

    </div>

</div>


<?php

}

?>


        </div>

    </div>

</section>


<!-- =====================================================
     BOOTSTRAP JS
===================================================== -->

<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"
></script>


<!-- =====================================================
     SUBMENU HOVER
===================================================== -->

<script>

document.addEventListener(
    "DOMContentLoaded",
    function () {

        const submenuItems =
            document.querySelectorAll(
                ".dropdown-submenu"
            );


        submenuItems.forEach(
            function (item) {

                item.addEventListener(
                    "mouseenter",
                    function () {

                        const menu =
                            item.querySelector(
                                ":scope > .dropdown-menu"
                            );

                        if (menu) {

                            menu.style.display =
                                "block";
                        }

                    }
                );


                item.addEventListener(
                    "mouseleave",
                    function () {

                        const menu =
                            item.querySelector(
                                ":scope > .dropdown-menu"
                            );

                        if (menu) {

                            menu.style.display =
                                "";
                        }

                    }
                );

            }
        );

    }
);

</script>


</body>

</html>

