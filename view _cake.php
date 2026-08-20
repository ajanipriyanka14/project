<?php
session_start();
include "config.php";

/* ================= ADMIN CHECK ================= */

if (
    !isset($_SESSION['admin_id']) &&
    !isset($_SESSION['admin_name'])
) {
    header("Location: admin.php");
    exit();
}


/* ================= SEARCH & FILTER ================= */

$search = isset($_GET['search']) ? trim($_GET['search']) : "";
$category = isset($_GET['category']) ? trim($_GET['category']) : "";


/* ================= CATEGORY LIST ================= */

$category_query = mysqli_query(
    $conn,
    "SELECT DISTINCT category FROM cake
     WHERE category IS NOT NULL
     AND category != ''
     ORDER BY category ASC"
);


/* ================= CAKE QUERY ================= */

$sql = "SELECT * FROM cake WHERE 1=1";


if ($search != "") {

    $safe_search = mysqli_real_escape_string($conn, $search);

    $sql .= " AND (
        cake_name LIKE '%$safe_search%'
        OR category LIKE '%$safe_search%'
        OR flavour LIKE '%$safe_search%'
    )";
}


if ($category != "") {

    $safe_category = mysqli_real_escape_string($conn, $category);

    $sql .= " AND category='$safe_category'";
}


$sql .= " ORDER BY id DESC";


$result = mysqli_query($conn, $sql);


/* ================= TOTAL CAKES ================= */

$total_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM cake"
);

$total_row = mysqli_fetch_assoc($total_query);

$total_cakes = $total_row['total'];


/* ================= AVAILABLE ================= */

$available_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM cake
     WHERE status='Available'"
);

$available_row = mysqli_fetch_assoc($available_query);

$available_cakes = $available_row['total'];


/* ================= OUT OF STOCK ================= */

$out_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM cake
     WHERE status='Out of Stock'"
);

$out_row = mysqli_fetch_assoc($out_query);

$out_cakes = $out_row['total'];

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Manage Cakes | SWIFFIN Admin</title>


<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">


<link
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
rel="stylesheet">


<style>

/* ================= BODY ================= */

body{

    margin:0;

    background:#000;

    color:#fff;

    font-family:Arial,sans-serif;

}


/* ================= HEADER ================= */

.admin-header{

    background:#111;

    border-bottom:1px solid #333;

    padding:17px 35px;

    display:flex;

    justify-content:space-between;

    align-items:center;

    position:sticky;

    top:0;

    z-index:1000;

}


.logo{

    color:#E88F2A;

    font-size:25px;

    font-weight:bold;

    letter-spacing:2px;

}


.admin-info{

    color:#aaa;

    font-size:14px;

}


/* ================= MAIN ================= */

.main-container{

    width:94%;

    max-width:1400px;

    margin:35px auto;

}


/* ================= PAGE HEADER ================= */

.page-header{

    display:flex;

    justify-content:space-between;

    align-items:center;

    margin-bottom:30px;

}


.page-title h1{

    margin:0;

    font-size:32px;

    font-weight:bold;

}


.page-title h1 i{

    color:#E88F2A;

}


.page-title p{

    color:#888;

    margin:8px 0 0;

}


/* ================= ADD BUTTON ================= */

.add-btn{

    background:#E88F2A;

    color:#fff;

    text-decoration:none;

    padding:12px 22px;

    border-radius:25px;

    font-weight:bold;

    transition:.3s;

}


.add-btn:hover{

    background:#fff;

    color:#000;

    transform:translateY(-2px);

}


/* ================= STAT CARDS ================= */

.stats{

    display:grid;

    grid-template-columns:
        repeat(3,1fr);

    gap:18px;

    margin-bottom:28px;

}


.stat-card{

    background:#111;

    border:1px solid #333;

    border-radius:15px;

    padding:20px;

    display:flex;

    align-items:center;

    gap:16px;

    transition:.3s;

}


.stat-card:hover{

    border-color:#E88F2A;

    transform:translateY(-3px);

}


.stat-icon{

    width:55px;

    height:55px;

    border-radius:12px;

    background:#24180d;

    display:flex;

    align-items:center;

    justify-content:center;

    color:#E88F2A;

    font-size:25px;

}


.stat-card h3{

    margin:0;

    font-size:25px;

}


.stat-card p{

    margin:4px 0 0;

    color:#888;

}


/* ================= FILTER BOX ================= */

.filter-box{

    background:#111;

    border:1px solid #333;

    border-radius:16px;

    padding:20px;

    margin-bottom:25px;

}


.search-input{

    background:#1b1b1b;

    border:1px solid #444;

    color:#fff;

    height:46px;

}


.search-input:focus{

    background:#1b1b1b;

    color:#fff;

    border-color:#E88F2A;

    box-shadow:none;

}


.search-input::placeholder{

    color:#777;

}


.category-select{

    background:#1b1b1b;

    border:1px solid #444;

    color:#fff;

    height:46px;

}


.category-select:focus{

    background:#1b1b1b;

    color:#fff;

    border-color:#E88F2A;

    box-shadow:none;

}


.category-select option{

    background:#1b1b1b;

    color:#fff;

}


.search-btn{

    background:#E88F2A;

    color:#fff;

    border:none;

    height:46px;

    padding:0 25px;

    border-radius:8px;

    font-weight:bold;

}


.search-btn:hover{

    background:#fff;

    color:#000;

}


.clear-btn{

    height:46px;

    padding:0 20px;

    border:1px solid #444;

    color:#ccc;

    background:#181818;

    text-decoration:none;

    display:flex;

    align-items:center;

    justify-content:center;

    border-radius:8px;

}


.clear-btn:hover{

    border-color:#E88F2A;

    color:#E88F2A;

}


/* ================= RESULT ================= */

.result-text{

    color:#888;

    margin-bottom:18px;

}


.result-text span{

    color:#E88F2A;

    font-weight:bold;

}


/* ================= CAKE GRID ================= */

.cake-grid{

    display:grid;

    grid-template-columns:
        repeat(4,1fr);

    gap:22px;

}


/* ================= CAKE CARD ================= */

.cake-card{

    background:#111;

    border:1px solid #333;

    border-radius:18px;

    overflow:hidden;

    transition:.3s;

}


.cake-card:hover{

    transform:translateY(-6px);

    border-color:#E88F2A;

    box-shadow:
        0 10px 30px rgba(232,143,42,.15);

}


/* ================= IMAGE ================= */

.cake-image{

    height:230px;

    background:#1b1b1b;

    position:relative;

    overflow:hidden;

}


.cake-image img{

    width:100%;

    height:100%;

    object-fit:cover;

    transition:.4s;

}


.cake-card:hover .cake-image img{

    transform:scale(1.06);

}


/* ================= STATUS ================= */

.status-badge{

    position:absolute;

    top:12px;

    right:12px;

    padding:6px 11px;

    border-radius:20px;

    font-size:12px;

    font-weight:bold;

}


.available{

    background:#153d24;

    color:#4ade80;

    border:1px solid #286b3d;

}


.out-stock{

    background:#3d1515;

    color:#ff6b6b;

    border:1px solid #7b2929;

}


/* ================= CARD CONTENT ================= */

.cake-content{

    padding:18px;

}


.cake-category{

    color:#E88F2A;

    font-size:12px;

    font-weight:bold;

    text-transform:uppercase;

    letter-spacing:1px;

}


.cake-name{

    font-size:19px;

    font-weight:bold;

    margin:7px 0;

    white-space:nowrap;

    overflow:hidden;

    text-overflow:ellipsis;

}


.cake-info{

    color:#999;

    font-size:13px;

    margin-bottom:12px;

}


.price{

    color:#fff;

    font-size:21px;

    font-weight:bold;

}


.stock{

    color:#999;

    font-size:13px;

}


/* ================= ACTIONS ================= */

.action-row{

    display:flex;

    gap:8px;

    margin-top:15px;

}


.edit-btn,

.delete-btn{

    flex:1;

    text-align:center;

    text-decoration:none;

    padding:9px;

    border-radius:8px;

    font-size:13px;

    font-weight:bold;

    transition:.3s;

}


.edit-btn{

    background:#172b42;

    color:#5caeff;

    border:1px solid #214a72;

}


.edit-btn:hover{

    background:#5caeff;

    color:#000;

}


.delete-btn{

    background:#351717;

    color:#ff6b6b;

    border:1px solid #6d2727;

}


.delete-btn:hover{

    background:#ff6b6b;

    color:#000;

}


/* ================= EMPTY ================= */

.empty-box{

    background:#111;

    border:1px solid #333;

    border-radius:18px;

    padding:70px 20px;

    text-align:center;

}


.empty-box i{

    font-size:55px;

    color:#E88F2A;

}


.empty-box h3{

    margin-top:15px;

}


.empty-box p{

    color:#888;

}


/* ================= BOTTOM BUTTON ================= */

.bottom-buttons{

    display:flex;

    justify-content:center;

    gap:12px;

    margin-top:35px;

}


.bottom-btn{

    text-decoration:none;

    padding:11px 22px;

    border-radius:25px;

    border:1px solid #444;

    color:#ccc;

    font-weight:bold;

}


.bottom-btn:hover{

    color:#E88F2A;

    border-color:#E88F2A;

}


/* ================= MOBILE ================= */

@media(max-width:1100px){

    .cake-grid{

        grid-template-columns:
            repeat(3,1fr);

    }

}


@media(max-width:800px){

    .cake-grid{

        grid-template-columns:
            repeat(2,1fr);

    }

    .stats{

        grid-template-columns:
            1fr;

    }

    .page-header{

        flex-direction:column;

        align-items:flex-start;

        gap:18px;

    }

}


@media(max-width:550px){

    .main-container{

        width:94%;

        margin:25px auto;

    }

    .cake-grid{

        grid-template-columns:1fr;

    }

    .admin-header{

        padding:15px 20px;

    }

    .logo{

        font-size:21px;

    }

    .admin-info{

        display:none;

    }

}

</style>

</head>


<body>


<!-- ================= HEADER ================= -->

<header class="admin-header">

    <div class="logo">

        <i class="bi bi-cake2-fill"></i>

        SWIFFIN

    </div>


    <div class="admin-info">

        <i class="bi bi-shield-lock-fill"></i>

        Admin Panel

    </div>

</header>



<!-- ================= MAIN ================= -->

<div class="main-container">


    <!-- PAGE HEADER -->

    <div class="page-header">


        <div class="page-title">

            <h1>

                <i class="bi bi-grid-3x3-gap-fill"></i>

                Manage Cakes

            </h1>

            <p>

                View, search and manage all cake products

            </p>

        </div>


        <a
            href="add_cake.php"
            class="add-btn"
        >

            <i class="bi bi-plus-lg"></i>

            Add New Cake

        </a>


    </div>



    <!-- ================= STATS ================= -->

    <div class="stats">


        <div class="stat-card">

            <div class="stat-icon">

                <i class="bi bi-cake2-fill"></i>

            </div>

            <div>

                <h3>
                    <?php echo $total_cakes; ?>
                </h3>

                <p>Total Cakes</p>

            </div>

        </div>


        <div class="stat-card">

            <div class="stat-icon">

                <i class="bi bi-check-circle-fill"></i>

            </div>

            <div>

                <h3>
                    <?php echo $available_cakes; ?>
                </h3>

                <p>Available</p>

            </div>

        </div>


        <div class="stat-card">

            <div class="stat-icon">

                <i class="bi bi-box-seam-fill"></i>

            </div>

            <div>

                <h3>
                    <?php echo $out_cakes; ?>
                </h3>

                <p>Out of Stock</p>

            </div>

        </div>


    </div>



    <!-- ================= SEARCH ================= -->

    <div class="filter-box">


        <form method="GET">


            <div class="row g-2">


                <div class="col-lg-5">

                    <input
                        type="text"
                        name="search"
                        class="form-control search-input"
                        placeholder="Search cake, category or flavour..."
                        value="<?php echo htmlspecialchars($search); ?>"
                    >

                </div>


                <div class="col-lg-3">

                    <select
                        name="category"
                        class="form-select category-select"
                    >

                        <option value="">

                            All Categories

                        </option>


                        <?php

                        if(
                            $category_query &&
                            mysqli_num_rows($category_query) > 0
                        ){

                            while(
                                $cat = mysqli_fetch_assoc(
                                    $category_query
                                )
                            ){

                        ?>

                        <option
                            value="<?php echo htmlspecialchars($cat['category']); ?>"
                            <?php
                            if(
                                $category ==
                                $cat['category']
                            ){
                                echo "selected";
                            }
                            ?>
                        >

                            <?php
                            echo htmlspecialchars(
                                $cat['category']
                            );
                            ?>

                        </option>

                        <?php

                            }

                        }

                        ?>

                    </select>

                </div>


                <div class="col-lg-2">

                    <button
                        type="submit"
                        class="search-btn w-100"
                    >

                        <i class="bi bi-search"></i>

                        Search

                    </button>

                </div>


                <div class="col-lg-2">

                    <a
                        href="view_cake.php"
                        class="clear-btn"
                    >

                        <i class="bi bi-arrow-clockwise"></i>

                        &nbsp; Reset

                    </a>

                </div>


            </div>


        </form>


    </div>



    <!-- RESULT -->

    <div class="result-text">

        Showing

        <span>
            <?php

            if($result){

                echo mysqli_num_rows($result);

            }else{

                echo "0";

            }

            ?>
        </span>

        cake products

    </div>



    <!-- ================= CAKE GRID ================= -->

    <?php

    if(
        $result &&
        mysqli_num_rows($result) > 0
    ){

    ?>

    <div class="cake-grid">


    <?php

    while(
        $row = mysqli_fetch_assoc($result)
    ){

        $image_path =
            "img/" .
            $row['image'];


        if(
            empty($row['image']) ||
            !file_exists($image_path)
        ){

            $image_path =
                "img/default-cake.jpg";

        }

    ?>


        <!-- ================= CARD ================= -->

        <div class="cake-card">


            <!-- IMAGE -->

            <div class="cake-image">


                <img
                    src="<?php echo htmlspecialchars($image_path); ?>"
                    alt="<?php echo htmlspecialchars($row['cake_name']); ?>"
                    onerror="this.src='img/default-cake.jpg';"
                >


                <?php

                if(
                    $row['status'] ==
                    "Available"
                ){

                ?>

                    <span
                        class="status-badge available"
                    >

                        <i class="bi bi-check-circle-fill"></i>

                        Available

                    </span>

                <?php

                }else{

                ?>

                    <span
                        class="status-badge out-stock"
                    >

                        <i class="bi bi-x-circle-fill"></i>

                        Out of Stock

                    </span>

                <?php

                }

                ?>


            </div>



            <!-- CONTENT -->

            <div class="cake-content">


                <div class="cake-category">

                    <?php
                    echo htmlspecialchars(
                        $row['category']
                    );
                    ?>

                </div>


                <div class="cake-name">

                    <?php
                    echo htmlspecialchars(
                        $row['cake_name']
                    );
                    ?>

                </div>


                <div class="cake-info">

                    <i class="bi bi-stars"></i>

                    <?php
                    echo htmlspecialchars(
                        $row['flavour']
                    );
                    ?>

                    &nbsp; • &nbsp;

                    <?php
                    echo htmlspecialchars(
                        $row['weight']
                    );
                    ?>

                </div>


                <div
                    class="d-flex justify-content-between align-items-end"
                >


                    <div>

                        <div class="price">

                            ₹<?php
                            echo number_format(
                                $row['price'],
                                2
                            );
                            ?>

                        </div>


                        <div class="stock">

                            <i class="bi bi-box-seam"></i>

                            Stock:
                            <?php
                            echo $row['stock'];
                            ?>

                        </div>

                    </div>


                </div>



                <!-- ACTIONS -->

                <div class="action-row">


                    <a
                        href="edit_cake.php?id=<?php echo $row['id']; ?>"
                        class="edit-btn"
                    >

                        <i class="bi bi-pencil-fill"></i>

                        Edit

                    </a>


                    <a
                        href="delete_cake.php?id=<?php echo $row['id']; ?>"
                        class="delete-btn"
                        onclick="return confirm('Are you sure you want to delete this cake?');"
                    >

                        <i class="bi bi-trash-fill"></i>

                        Delete

                    </a>


                </div>


            </div>


        </div>


    <?php

    }

    ?>


    </div>


    <?php

    }else{

    ?>


        <!-- EMPTY -->

        <div class="empty-box">


            <i class="bi bi-cake2"></i>


            <h3>

                No Cakes Found

            </h3>


            <p>

                Try another search or add a new cake product.

            </p>


            <a
                href="add_cake.php"
                class="add-btn"
            >

                <i class="bi bi-plus-lg"></i>

                Add Cake

            </a>


        </div>


    <?php

    }

    ?>



    <!-- ================= BOTTOM ================= -->

    <div class="bottom-buttons">


        <a
            href="admin_dashboard.php"
            class="bottom-btn"
        >

            <i class="bi bi-arrow-left"></i>

            Dashboard

        </a>


        <a
            href="add_cake.php"
            class="bottom-btn"
        >

            <i class="bi bi-plus-circle"></i>

            Add New Cake

        </a>


    </div>


</div>


</body>

</html>
