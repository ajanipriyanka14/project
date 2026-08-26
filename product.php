<?php
session_start();
include("config.php");


/* =====================================================
   DYNAMIC PARENT CATEGORIES
===================================================== */

$parent_categories = [];

$sql = "SELECT *
        FROM category
        WHERE parent_id = 0
        AND status = 'Active'
        ORDER BY category_name ASC";

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

    $sql = "SELECT *
            FROM category
            WHERE parent_id = $parent_id
            AND status = 'Active'
            ORDER BY category_name ASC";

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

$selected_category = intval($_GET['category'] ?? 0);


/* =====================================================
   DYNAMIC PRODUCT FILTER
===================================================== */

if ($selected_category > 0) {


    /* =================================================
       GET SELECTED CATEGORY
    ================================================= */

    $cat_stmt = mysqli_prepare(
        $conn,

        "SELECT
            id,
            parent_id,
            category_name

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
        mysqli_stmt_get_result(
            $cat_stmt
        );

    $selected_cat =
        mysqli_fetch_assoc(
            $cat_result
        );

    mysqli_stmt_close($cat_stmt);


    /* =================================================
       CATEGORY FOUND
    ================================================= */

    if ($selected_cat) {


        $category_name =
            trim(
                $selected_cat['category_name']
            );


        $parent_id =
            intval(
                $selected_cat['parent_id']
            );


        /* =============================================
           CHILD CATEGORY
        ============================================= */

        if ($parent_id > 0) {


            $stmt = mysqli_prepare(
                $conn,

                "SELECT

                    cake.*,

                    category.category_name
                    AS category_display_name,

                    flavor.flavor_name
                    AS flavor_display_name

                 FROM cake

                 LEFT JOIN category
                 ON cake.category_id = category.id

                 LEFT JOIN flavor
                 ON cake.flavor_id = flavor.id

                 WHERE

                    cake.category_id = ?

                    OR

                    LOWER(TRIM(cake.category)) =
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
                mysqli_stmt_get_result(
                    $stmt
                );
        }


        /* =============================================
           PARENT CATEGORY

           Parent + All Child Products

           Supports old records where
           category_id is NULL
        ============================================= */

        else {


            $stmt = mysqli_prepare(
                $conn,

                "SELECT

                    cake.*,

                    category.category_name
                    AS category_display_name,

                    flavor.flavor_name
                    AS flavor_display_name

                 FROM cake

                 LEFT JOIN category
                 ON cake.category_id = category.id

                 LEFT JOIN flavor
                 ON cake.flavor_id = flavor.id

                 WHERE

                    /* Parent category ID */

                    cake.category_id = ?

                    OR

                    /* Child category IDs */

                    cake.category_id IN
                    (
                        SELECT id
                        FROM category
                        WHERE parent_id = ?
                    )

                    OR

                    /* Old parent category name */

                    LOWER(TRIM(cake.category)) =
                    LOWER(TRIM(?))

                    OR

                    /* Old child category names */

                    LOWER(TRIM(cake.category)) IN
                    (
                        SELECT
                            LOWER(
                                TRIM(
                                    c.category_name
                                )
                            )

                        FROM category c

                        WHERE c.parent_id = ?
                    )

                 ORDER BY cake.id DESC"
            );


            mysqli_stmt_bind_param(
                $stmt,
                "iisi",
                $selected_category,
                $selected_category,
                $category_name,
                $selected_category
            );


            mysqli_stmt_execute($stmt);


            $result =
                mysqli_stmt_get_result(
                    $stmt
                );
        }
    }


    /* =============================================
       CATEGORY NOT FOUND
    ============================================= */

    else {


        $result = mysqli_query(
            $conn,

            "SELECT

                cake.*,

                category.category_name
                AS category_display_name,

                flavor.flavor_name
                AS flavor_display_name

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

            category.category_name
            AS category_display_name,

            flavor.flavor_name
            AS flavor_display_name

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


<!-- GOOGLE FONTS -->

<link
    rel="preconnect"
    href="https://fonts.googleapis.com"
>

<link
    rel="preconnect"
    href="https://fonts.gstatic.com"
>

<link
    href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Oswald:wght@500;600;700&family=Pacifico&display=swap"
    rel="stylesheet"
>


<!-- FONT AWESOME -->

<link
    href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css"
    rel="stylesheet"
>


<!-- BOOTSTRAP ICONS -->

<link
    href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css"
    rel="stylesheet"
>


<!-- BOOTSTRAP -->

<link
    href="css/bootstrap.min.css"
    rel="stylesheet"
>


<!-- TEMPLATE CSS -->

<link
    href="css/style.css"
    rel="stylesheet"
>


<style>

/* =====================================================
   BASIC
===================================================== */

* {
    box-sizing: border-box;
}

body {

    margin: 0;
    padding: 0;

    background: #000;

    color: #fff;

    font-family:
        Arial,
        Helvetica,
        sans-serif;
}


/* =====================================================
   TOPBAR
===================================================== */

.container-fluid {

    margin-top: 0 !important;

    padding: 0 !important;
}


/* =====================================================
   NAVBAR
===================================================== */

.navbar {

    background: #fff !important;
}

.navbar .nav-link {

    font-weight: 700 !important;
}

.navbar .btn {

    font-weight: 700 !important;
}

.navbar .btn-primary {

    background: #E88F2A !important;

    border-color: #E88F2A !important;

    color: #fff !important;
}

.navbar .btn-primary:hover {

    background: #d67d18 !important;

    border-color: #d67d18 !important;
}


/* =====================================================
   CAKES ARROW
===================================================== */

.cakes-link {

    padding-right: 2px !important;
}

.cakes-arrow {

    padding-left: 1px !important;

    padding-right: 4px !important;
}


/* =====================================================
   DROPDOWN
===================================================== */

.dropdown-menu {

    margin-top: 0;
}


/* =====================================================
   CHILD DROPDOWN
===================================================== */

.dropdown-submenu {

    position: relative;
}

.dropdown-submenu > .dropdown-menu {

    top: 0;

    left: 100%;

    margin-top: -1px;
}


/* =====================================================
   DESKTOP CHILD MENU
===================================================== */

@media (min-width: 992px) {

    .dropdown-submenu:hover > .dropdown-menu {

        display: block;
    }
}


/* =====================================================
   HERO
===================================================== */

.hero {

    background: #111;

    padding: 90px 0;
}

.hero h5 {

    color: #E88F2A !important;
}


/* =====================================================
   PRODUCT CARD
===================================================== */

.product-card {

    background: #1b1b1b;

    border-radius: 20px;

    overflow: hidden;

    transition: .4s;

    color: #fff;

    border: 1px solid #333 !important;
}

.product-card:hover {

    transform: translateY(-10px);

    box-shadow:
        0 15px 35px
        rgba(232,143,42,.35);
}


/* =====================================================
   IMAGE
===================================================== */

.product-card img {

    width: 100%;

    height: 260px;

    object-fit: cover;
}


/* =====================================================
   CARD BODY
===================================================== */

.product-card .card-body {

    padding: 25px;
}

.product-card h4 {

    color: #E88F2A;

    font-weight: bold;
}

.product-card p {

    color: #ddd;
}


/* =====================================================
   PRICE
===================================================== */

.price {

    color: #E88F2A;

    font-size: 28px;

    font-weight: bold;
}

.old-price {

    color: #888;

    font-size: 19px;

    text-decoration: line-through;

    margin-right: 8px;
}


/* =====================================================
   OFFER BADGE
===================================================== */

.offer-badge {

    display: inline-block;

    background: #dc3545;

    color: #fff;

    padding: 7px 14px;

    border-radius: 20px;

    font-size: 14px;

    font-weight: bold;

    margin-bottom: 10px;
}


/* =====================================================
   OFFER BOX
===================================================== */

.offer-box {

    background: #351b08;

    border: 1px solid #E88F2A;

    border-radius: 12px;

    padding: 12px;

    margin-top: 12px;

    margin-bottom: 12px;
}

.offer-title {

    color: #E88F2A;

    font-size: 17px;

    font-weight: bold;
}

.offer-description {

    color: #ddd;

    font-size: 14px;

    margin-top: 5px;
}

.offer-date {

    color: #aaa;

    font-size: 12px;

    margin-top: 5px;
}


/* =====================================================
   ADD TO CART
===================================================== */

.btn-order {

    background: #E88F2A;

    color: #fff;

    width: 100%;

    border-radius: 30px;

    font-weight: bold;

    border: none;

    padding: 12px;
}

.btn-order:hover {

    background: #d67d18;

    color: #fff;
}


/* =====================================================
   BUY NOW
===================================================== */

.btn-buy-now {

    background: #E88F2A;

    color: #fff;

    width: 100%;

    border-radius: 30px;

    font-weight: bold;

    border: none;

    padding: 12px;
}

.btn-buy-now:hover {

    background: #d67d18;

    color: #fff;

    transform: translateY(-2px);

    box-shadow:
        0 5px 20px
        rgba(232,143,42,.35);
}


/* =====================================================
   OUT OF STOCK
===================================================== */

.out-stock {

    background: #dc3545;

    color: #fff;

    width: 100%;

    border-radius: 30px;

    font-weight: bold;

    padding: 12px;

    border: none;
}


/* =====================================================
   MOBILE
===================================================== */

@media(max-width:991px) {

    .dropdown-submenu > .dropdown-menu {

        position: static;

        margin-left: 15px;

        box-shadow: none;
    }
}


@media(max-width:600px) {

    .hero {

        padding: 60px 0;
    }

    .hero h1 {

        font-size: 32px;
    }

    .product-card img {

        height: 230px;
    }
}

</style>

</head>


<body>


<!-- =====================================================
     TOPBAR
===================================================== -->

<div class="container-fluid bg-white px-5">

    <div class="row align-items-center">


        <!-- WELCOME -->

        <div class="col-lg-2">

            <h5 class="m-1 fw-bold">

                <?php

                if (isset($_SESSION['name'])) {

                    echo
                        "Welcome " .
                        htmlspecialchars(
                            $_SESSION['name']
                        );

                } else {

                    echo "Welcome";
                }

                ?>

            </h5>

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


        <!-- CALL US -->

        <div class="col-lg-4 text-end">

            <div class="d-inline-flex align-items-center">

                <i
                    class="bi bi-phone-vibrate fs-1 me-3"
                    style="color:#E88F2A;"
                ></i>

                <div class="text-start">

                    <h6 class="text-uppercase mb-1">
                        CALL US
                    </h6>

                    <span>
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
    class="navbar navbar-expand-lg navbar-light shadow-sm sticky-top py-3"
>

    <div class="container">


        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbar"
        >

            <span class="navbar-toggler-icon"></span>

        </button>


        <div
            class="collapse navbar-collapse"
            id="navbar"
        >

            <ul class="navbar-nav ms-auto">


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


                        <!-- CAKES -->

                        <a
                            href="product.php"
                            class="nav-link cakes-link active"
                        >
                            Cakes
                        </a>


                        <!-- ARROW -->

                        <a
                            href="#"
                            class="nav-link dropdown-toggle cakes-arrow"
                            data-bs-toggle="dropdown"
                            aria-expanded="false"
                        ></a>


                        <!-- CATEGORY MENU -->

                        <ul class="dropdown-menu">


                            <?php

                            foreach (
                                $parent_categories
                                as $parent
                            ) {

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


                                <?php

                                if (
                                    count(
                                        $sub_categories
                                    ) > 0
                                ) {

                                ?>


                                    <!-- PARENT WITH CHILD -->

                                    <li class="dropdown-submenu">

                                        <a
                                            href="product.php?category=<?php echo $parent_id; ?>"
                                            class="dropdown-item dropdown-toggle"
                                        >

                                            <?php

                                            echo
                                                htmlspecialchars(
                                                    $parent[
                                                        'category_name'
                                                    ]
                                                );

                                            ?>

                                        </a>


                                        <!-- CHILD -->

                                        <ul class="dropdown-menu">

                                            <?php

                                            foreach (
                                                $sub_categories
                                                as $sub
                                            ) {

                                            ?>

                                                <li>

                                                    <a
                                                        href="product.php?category=<?php echo intval($sub['id']); ?>"
                                                        class="dropdown-item"
                                                    >

                                                        <?php

                                                        echo
                                                            htmlspecialchars(
                                                                $sub[
                                                                    'category_name'
                                                                ]
                                                            );

                                                        ?>

                                                    </a>

                                                </li>

                                            <?php

                                            }

                                            ?>

                                        </ul>

                                    </li>


                                <?php

                                } else {

                                ?>


                                    <!-- PARENT WITHOUT CHILD -->

                                    <li>

                                        <a
                                            href="product.php?category=<?php echo $parent_id; ?>"
                                            class="dropdown-item"
                                        >

                                            <?php

                                            echo
                                                htmlspecialchars(
                                                    $parent[
                                                        'category_name'
                                                    ]
                                                );

                                            ?>

                                        </a>

                                    </li>


                                <?php

                                }

                            }

                            ?>


                        </ul>

                    </div>

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


                <!-- LOGIN -->

                <li class="nav-item">

                    <a
                        href="login.php"
                        class="btn btn-primary rounded-circle ms-3 px-4"
                    >
                        Login
                    </a>

                </li>


                <!-- REGISTER -->

                <li class="nav-item">

                    <a
                        href="register.php"
                        class="btn btn-primary rounded-circle ms-3 px-4"
                    >
                        Register
                    </a>

                </li>


                <!-- LOGOUT -->

                <li class="nav-item">

                    <a
                        href="logout.php"
                        class="btn btn-primary rounded-circle ms-3 px-4"
                    >
                        Logout
                    </a>

                </li>


            </ul>

        </div>

    </div>

</nav>


<!-- =====================================================
     HERO
===================================================== -->

<section class="hero">

    <div class="container text-center">

        <h5 class="fw-bold">
            OUR PRODUCTS
        </h5>

        <h1 class="display-4 text-white fw-bold">
            Fresh & Delicious Cakes
        </h1>

        <p class="text-light">
            Choose your favourite cake for every celebration.
        </p>

    </div>

</section>


<!-- =====================================================
     PRODUCTS
===================================================== -->

<section class="py-5">

    <div class="container">

        <div class="row">


<?php

if (mysqli_num_rows($result) == 0) {

?>

    <div class="col-12 text-center py-5">

        <h3 style="color:#E88F2A;">
            No Cakes Found
        </h3>

        <p class="text-light">
            There are no cakes available in this category.
        </p>

    </div>

<?php

}


/* =====================================================
   DISPLAY CAKES
===================================================== */

while ($row = mysqli_fetch_assoc($result)) {


    /* =================================================
       CAKE DATA
    ================================================= */

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

    $today =
        date("Y-m-d");


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


            <!-- PRODUCT CARD -->

            <div class="col-lg-4 col-md-6 mb-4">

                <div class="card product-card h-100">


                    <!-- IMAGE -->

                    <img
                        src="img/<?php echo htmlspecialchars($row['image']); ?>"
                        class="card-img-top"
                        alt="<?php echo htmlspecialchars($row['cake_name']); ?>"
                    >


                    <div class="card-body text-center">


                        <!-- CAKE NAME -->

                        <h4>

                            <?php

                            echo
                                htmlspecialchars(
                                    $row['cake_name']
                                );

                            ?>

                        </h4>


                        <!-- CATEGORY -->

                        <p>

                            <b>Category :</b>

                            <?php

                            echo
                                htmlspecialchars(
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

                        </p>


                        <!-- FLAVOUR -->

                        <p>

                            <b>Flavour :</b>

                            <?php

                            /*
                             * New FK-based value
                             *
                             * cake.flavor_id
                             *       ↓
                             * flavor.id
                             *       ↓
                             * flavor.flavor_name
                             *
                             * Old flavour field is kept
                             * as fallback.
                             */

                            echo
                                htmlspecialchars(
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

                        </p>


                        <!-- WEIGHT -->

                        <p>

                            <b>Weight :</b>

                            <?php

                            echo
                                htmlspecialchars(
                                    $row['weight']
                                );

                            ?>

                        </p>


                        <!-- DESCRIPTION -->

                        <p>

                            <?php

                            echo
                                htmlspecialchars(
                                    $row['description']
                                );

                            ?>

                        </p>


                        <!-- STOCK -->

                        <p>

                            <b>Stock :</b>

                            <span
                                class="text-warning fw-bold"
                            >

                                <?php

                                echo
                                    intval(
                                        $row['stock']
                                    );

                                ?>

                            </span>

                        </p>


                        <!-- STATUS -->

                        <p>

                            <b>Status :</b>

                            <?php

                            if (

                                $row['status']
                                ==
                                "Available"

                                &&

                                intval(
                                    $row['stock']
                                ) > 0

                            ) {

                                echo "

                                <span
                                    class='text-success fw-bold'
                                >
                                    Available
                                </span>

                                ";

                            } else {

                                echo "

                                <span
                                    class='text-danger fw-bold'
                                >
                                    Out of Stock
                                </span>

                                ";
                            }

                            ?>

                        </p>


                        <!-- OFFER -->

                        <?php

                        if ($offer) {

                        ?>


                            <div class="offer-badge">

                                🔥

                                <?php

                                echo
                                    intval(
                                        $offer[
                                            'discount'
                                        ]
                                    );

                                ?>% OFF

                            </div>


                            <div class="offer-box">


                                <!-- OFFER TITLE -->

                                <div class="offer-title">

                                    <?php

                                    echo
                                        htmlspecialchars(
                                            $offer[
                                                'offer_title'
                                            ]
                                        );

                                    ?>

                                </div>


                                <!-- DESCRIPTION -->

                                <div class="offer-description">

                                    <?php

                                    echo
                                        htmlspecialchars(
                                            $offer[
                                                'description'
                                            ]
                                        );

                                    ?>

                                </div>


                                <!-- OFFER DATE -->

                                <div class="offer-date">

                                    Valid:

                                    <?php

                                    echo
                                        date(
                                            "d-m-Y",
                                            strtotime(
                                                $offer[
                                                    'start_date'
                                                ]
                                            )
                                        );

                                    ?>

                                    &nbsp; to &nbsp;

                                    <?php

                                    echo
                                        date(
                                            "d-m-Y",
                                            strtotime(
                                                $offer[
                                                    'end_date'
                                                ]
                                            )
                                        );

                                    ?>

                                </div>

                            </div>


                            <!-- DISCOUNT PRICE -->

                            <div class="price">

                                <span class="old-price">

                                    ₹<?php

                                    echo
                                        number_format(
                                            $cake_price,
                                            2
                                        );

                                    ?>

                                </span>


                                ₹<?php

                                echo
                                    number_format(
                                        $final_price,
                                        2
                                    );

                                ?>

                            </div>


                        <?php

                        } else {

                        ?>


                            <!-- NORMAL PRICE -->

                            <div class="price">

                                ₹<?php

                                echo
                                    number_format(
                                        $cake_price,
                                        2
                                    );

                                ?>

                            </div>


                        <?php

                        }

                        ?>


                        <!-- =================================================
                             CART / BUY NOW
                        ================================================= -->

                        <?php

                        if (

                            intval(
                                $row['stock']
                            ) > 0

                            &&

                            $row['status']
                            ==
                            "Available"

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
                                    value="<?php echo $cake_id; ?>"
                                >

                                <input
                                    type="hidden"
                                    name="quantity"
                                    value="1"
                                >

                                <button
                                    type="submit"
                                    name="add_to_cart"
                                    class="btn btn-order mt-3"
                                >

                                    <i
                                        class="fas fa-shopping-cart"
                                    ></i>

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
                                    value="<?php echo $cake_id; ?>"
                                >

                                <input
                                    type="hidden"
                                    name="quantity"
                                    value="1"
                                >

                                <button
                                    type="submit"
                                    name="buy_now"
                                    class="btn btn-buy-now mt-3"
                                >

                                    <i
                                        class="fas fa-bolt"
                                    ></i>

                                    Buy Now

                                </button>

                            </form>


                        <?php

                        } else {

                        ?>


                            <!-- OUT OF STOCK -->

                            <button
                                class="out-stock mt-3"
                                disabled
                            >

                                Out of Stock

                            </button>


                        <?php

                        }

                        ?>


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
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


</body>

</html>
