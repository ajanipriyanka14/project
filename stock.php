<?php

session_start();
include "config.php";


/* =====================================================
   UPDATE STOCK
===================================================== */

if (isset($_POST['update_stock'])) {

    $id = intval($_POST['id'] ?? 0);
    $stock = intval($_POST['stock'] ?? 0);

    if ($stock < 0) {
        $stock = 0;
    }

    if ($stock > 0) {
        $status = "Available";
    } else {
        $status = "Out of Stock";
    }

    $stmt = mysqli_prepare(
        $conn,
        "UPDATE cake SET stock = ?, status = ? WHERE id = ?"
    );

    mysqli_stmt_bind_param(
        $stmt,
        "isi",
        $stock,
        $status,
        $id
    );

    if (mysqli_stmt_execute($stmt)) {

        mysqli_stmt_close($stmt);

        header("Location: stock.php?success=1");
        exit();

    } else {

        $error = "Stock Update Failed: " . mysqli_error($conn);

        mysqli_stmt_close($stmt);
    }
}


/* =====================================================
   SUCCESS MESSAGE
===================================================== */

$success = "";

if (isset($_GET['success'])) {

    $success = "Stock Updated Successfully!";
}


/* =====================================================
   GET CAKES
===================================================== */

$result = mysqli_query(
    $conn,
    "SELECT * FROM cake ORDER BY id DESC"
);

if (!$result) {

    die(
        "Database Error: " .
        mysqli_error($conn)
    );
}


/* =====================================================
   STOCK SUMMARY
===================================================== */

$total_cakes = 0;
$available_cakes = 0;
$out_of_stock = 0;
$total_stock = 0;

$summary_query = mysqli_query(
    $conn,
    "SELECT
        COUNT(*) AS total_cakes,
        SUM(CASE WHEN stock > 0 THEN 1 ELSE 0 END) AS available_cakes,
        SUM(CASE WHEN stock <= 0 THEN 1 ELSE 0 END) AS out_of_stock,
        COALESCE(SUM(stock),0) AS total_stock
     FROM cake"
);

if ($summary_query) {

    $summary = mysqli_fetch_assoc($summary_query);

    $total_cakes =
        intval($summary['total_cakes']);

    $available_cakes =
        intval($summary['available_cakes']);

    $out_of_stock =
        intval($summary['out_of_stock']);

    $total_stock =
        intval($summary['total_stock']);
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
    Stock Management | Swiffin Cake Shop
</title>


<!-- ================= BOOTSTRAP ================= -->

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet"
>


<!-- ================= FONT AWESOME ================= -->

<link
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
rel="stylesheet"
>


<style>


/* =====================================================
   RESET
===================================================== */

*{

    margin:0;

    padding:0;

    box-sizing:border-box;

}


/* =====================================================
   BODY
===================================================== */

body{

    margin:0;

    padding:0;

    background:#000;

    color:#fff;

    font-family:Arial,sans-serif;

    min-height:100vh;

}


/* =====================================================
   MAIN
===================================================== */

.main{

    width:95%;

    max-width:1250px;

    margin:40px auto;

}


/* =====================================================
   PAGE TITLE
===================================================== */

.page-title{

    text-align:center;

    margin-bottom:30px;

}


.page-title h2{

    color:#E88F2A;

    font-weight:bold;

    margin-bottom:8px;

}


.page-title p{

    color:#aaa;

    margin:0;

    font-size:14px;

}


/* =====================================================
   ORANGE LINE
===================================================== */

.orange-line{

    width:65px;

    height:3px;

    background:#E88F2A;

    margin:12px auto 0;

    border-radius:10px;

}


/* =====================================================
   SUCCESS MESSAGE
===================================================== */

.success-message{

    background:#102b18;

    color:#4ade80;

    border:1px solid #278342;

    padding:12px 15px;

    border-radius:8px;

    text-align:center;

    margin-bottom:20px;

    font-size:14px;

}


/* =====================================================
   ERROR MESSAGE
===================================================== */

.error-message{

    background:#300909;

    color:#ff6666;

    border:1px solid #ff3333;

    padding:12px 15px;

    border-radius:8px;

    text-align:center;

    margin-bottom:20px;

    font-size:14px;

}


/* =====================================================
   SUMMARY CARDS
===================================================== */

.summary-row{

    display:grid;

    grid-template-columns:
    repeat(4,1fr);

    gap:15px;

    margin-bottom:25px;

}


.summary-card{

    background:#111;

    border:1px solid #333;

    border-radius:12px;

    padding:18px;

    display:flex;

    align-items:center;

    gap:15px;

    transition:.3s;

}


.summary-card:hover{

    border-color:#E88F2A;

    transform:translateY(-2px);

    box-shadow:
    0 0 15px rgba(232,143,42,.15);

}


.summary-icon{

    width:48px;

    height:48px;

    background:#21170e;

    border:1px solid #5b3a19;

    color:#E88F2A;

    border-radius:10px;

    display:flex;

    align-items:center;

    justify-content:center;

    font-size:20px;

}


.summary-text small{

    display:block;

    color:#888;

    font-size:12px;

    margin-bottom:3px;

}


.summary-text strong{

    color:#fff;

    font-size:20px;

}


/* =====================================================
   STOCK BOX
===================================================== */

.stock-box{

    background:#111;

    border:1px solid #E88F2A;

    border-radius:18px;

    padding:25px;

    box-shadow:
        0 0 20px rgba(232,143,42,.25),

        0 0 45px rgba(232,143,42,.10);

}


/* =====================================================
   TABLE TOP
===================================================== */

.table-top{

    display:flex;

    justify-content:space-between;

    align-items:center;

    margin-bottom:20px;

    gap:15px;

}


.table-title h4{

    color:#fff;

    margin:0 0 5px;

    font-weight:bold;

}


.table-title p{

    color:#888;

    margin:0;

    font-size:13px;

}


/* =====================================================
   SEARCH
===================================================== */

.search-box{

    position:relative;

}


.search-box i{

    position:absolute;

    left:12px;

    top:12px;

    color:#777;

}


.search-box input{

    width:220px;

    height:38px;

    background:#050505;

    color:#fff;

    border:1px solid #444;

    border-radius:7px;

    padding:0 12px 0 35px;

    outline:none;

}


.search-box input::placeholder{

    color:#777;

}


.search-box input:focus{

    border-color:#E88F2A;

    box-shadow:
    0 0 7px rgba(232,143,42,.25);

}


/* =====================================================
   TABLE
===================================================== */

.table{

    margin:0;

}


.table thead th{

    background:#E88F2A !important;

    color:#fff !important;

    text-align:center;

    vertical-align:middle;

    border-color:#000;

    font-size:13px;

    padding:13px 8px;

    white-space:nowrap;

}


.table tbody td{

    background:#1b1b1b !important;

    color:#fff !important;

    text-align:center;

    vertical-align:middle;

    border-color:#333;

    padding:13px 8px;

    font-size:13px;

}


.table tbody tr:hover td{

    background:#242424 !important;

}


/* =====================================================
   CAKE IMAGE
===================================================== */

.cake-img{

    width:65px;

    height:65px;

    object-fit:cover;

    border-radius:10px;

    border:2px solid #E88F2A;

    transition:.3s;

}


.cake-img:hover{

    transform:scale(1.05);

}


/* =====================================================
   NO IMAGE
===================================================== */

.no-image{

    width:65px;

    height:65px;

    background:#050505;

    border:1px solid #444;

    border-radius:10px;

    display:flex;

    align-items:center;

    justify-content:center;

    margin:auto;

    color:#777;

    font-size:11px;

}


/* =====================================================
   CAKE NAME
===================================================== */

.cake-name{

    color:#fff;

    font-weight:bold;

}


.category{

    color:#aaa;

}


.price{

    color:#E88F2A;

    font-weight:bold;

}


/* =====================================================
   STOCK NUMBER
===================================================== */

.stock-number{

    font-size:16px;

    font-weight:bold;

}


/* =====================================================
   STATUS
===================================================== */

.available{

    color:#28a745;

    font-weight:bold;

}


.out-stock{

    color:#dc3545;

    font-weight:bold;

}


/* =====================================================
   STATUS BADGE
===================================================== */

.status-badge{

    display:inline-block;

    padding:6px 10px;

    border-radius:20px;

    font-size:11px;

}


.status-available{

    background:#102b18;

    border:1px solid #278342;

}


.status-out{

    background:#300909;

    border:1px solid #8d2929;

}


/* =====================================================
   UPDATE FORM
===================================================== */

.update-form{

    display:flex;

    justify-content:center;

    align-items:center;

    gap:7px;

}


.stock-input{

    width:80px;

    height:37px;

    background:#000;

    color:#fff;

    border:1px solid #E88F2A;

    border-radius:7px;

    padding:5px;

    text-align:center;

    outline:none;

    font-weight:bold;

}


.stock-input:focus{

    border-color:#fff;

    box-shadow:
    0 0 7px rgba(232,143,42,.3);

}


/* =====================================================
   UPDATE BUTTON
===================================================== */

.update-btn{

    height:37px;

    background:#E88F2A;

    color:#fff;

    border:none;

    border-radius:7px;

    padding:0 14px;

    font-weight:bold;

    cursor:pointer;

    transition:.3s;

}


.update-btn:hover{

    background:#fff;

    color:#000;

    transform:translateY(-1px);

}


/* =====================================================
   BACK BUTTON
===================================================== */

.back-btn{

    display:inline-block;

    margin-top:25px;

    background:#E88F2A;

    color:#fff;

    text-decoration:none;

    padding:10px 25px;

    border-radius:25px;

    font-weight:bold;

    transition:.3s;

}


.back-btn:hover{

    background:#fff;

    color:#000;

    transform:translateY(-2px);

}


/* =====================================================
   FOOTER
===================================================== */

.footer{

    text-align:center;

    color:#666;

    font-size:12px;

    margin-top:25px;

}


/* =====================================================
   MOBILE
===================================================== */

@media(max-width:900px){

    .summary-row{

        grid-template-columns:
        repeat(2,1fr);

    }

}


@media(max-width:600px){

    .main{

        width:96%;

        margin:25px auto;

    }


    .stock-box{

        padding:12px;

    }


    .summary-row{

        grid-template-columns:1fr;

    }


    .table-top{

        display:block;

    }


    .search-box{

        margin-top:15px;

    }


    .search-box input{

        width:100%;

    }


    .table{

        font-size:12px;

    }


    .stock-input{

        width:60px;

    }


    .update-btn{

        padding:0 10px;

    }

}

</style>

</head>


<body>


<div class="main">


<!-- =====================================================
     PAGE TITLE
===================================================== -->

<div class="page-title">

    <h2>

        <i class="fas fa-boxes-stacked"></i>

        Stock Management

    </h2>


    <p>

        Manage cake stock and availability

    </p>


    <div class="orange-line"></div>

</div>



<!-- =====================================================
     SUCCESS
===================================================== -->

<?php if ($success != "") { ?>

<div class="success-message">

    <i class="fas fa-circle-check"></i>

    <?php echo htmlspecialchars($success); ?>

</div>

<?php } ?>



<!-- =====================================================
     ERROR
===================================================== -->

<?php if (isset($error) && $error != "") { ?>

<div class="error-message">

    <i class="fas fa-circle-xmark"></i>

    <?php echo htmlspecialchars($error); ?>

</div>

<?php } ?>



<!-- =====================================================
     SUMMARY
===================================================== -->

<div class="summary-row">


<!-- TOTAL CAKES -->

<div class="summary-card">

    <div class="summary-icon">

        <i class="fas fa-cake-candles"></i>

    </div>

    <div class="summary-text">

        <small>Total Cakes</small>

        <strong>
            <?php echo $total_cakes; ?>
        </strong>

    </div>

</div>



<!-- TOTAL STOCK -->

<div class="summary-card">

    <div class="summary-icon">

        <i class="fas fa-boxes-stacked"></i>

    </div>

    <div class="summary-text">

        <small>Total Stock</small>

        <strong>
            <?php echo $total_stock; ?>
        </strong>

    </div>

</div>



<!-- AVAILABLE -->

<div class="summary-card">

    <div class="summary-icon">

        <i class="fas fa-circle-check"></i>

    </div>

    <div class="summary-text">

        <small>Available</small>

        <strong>
            <?php echo $available_cakes; ?>
        </strong>

    </div>

</div>



<!-- OUT OF STOCK -->

<div class="summary-card">

    <div class="summary-icon">

        <i class="fas fa-circle-xmark"></i>

    </div>

    <div class="summary-text">

        <small>Out of Stock</small>

        <strong>
            <?php echo $out_of_stock; ?>
        </strong>

    </div>

</div>


</div>



<!-- =====================================================
     STOCK BOX
===================================================== -->

<div class="stock-box">


<!-- TABLE TOP -->

<div class="table-top">


<div class="table-title">

    <h4>

        <i class="fas fa-box-open"></i>

        Cake Inventory

    </h4>

    <p>

        Update and manage available cake stock

    </p>

</div>



<!-- SEARCH -->

<div class="search-box">

    <i class="fas fa-search"></i>

    <input
        type="text"
        id="searchInput"
        placeholder="Search cake..."
    >

</div>


</div>



<!-- =====================================================
     TABLE
===================================================== -->

<div class="table-responsive">

<table
    class="table table-bordered table-hover"
    id="stockTable"
>


<thead>

<tr>

<th>ID</th>

<th>Image</th>

<th>Cake Name</th>

<th>Category</th>

<th>Price</th>

<th>Stock</th>

<th>Status</th>

<th>Action</th>

</tr>

</thead>



<tbody>


<?php

if (mysqli_num_rows($result) > 0) {

    while ($row = mysqli_fetch_assoc($result)) {

        $current_stock =
            intval($row['stock'] ?? 0);

?>

<tr>


<!-- ID -->

<td>

    #<?php echo intval($row['id']); ?>

</td>



<!-- IMAGE -->

<td>

<?php

if (!empty($row['image'])) {

?>

<img
    src="img/<?php
        echo htmlspecialchars($row['image']);
    ?>"
    class="cake-img"
    alt="Cake"
>

<?php

} else {

?>

<div class="no-image">

    No Image

</div>

<?php

}

?>

</td>



<!-- CAKE NAME -->

<td>

<span class="cake-name">

<?php

echo htmlspecialchars(
    $row['cake_name'] ?? ''
);

?>

</span>

</td>



<!-- CATEGORY -->

<td>

<span class="category">

<?php

echo htmlspecialchars(
    $row['category'] ?? ''
);

?>

</span>

</td>



<!-- PRICE -->

<td>

<span class="price">

₹<?php

echo number_format(
    floatval($row['price'] ?? 0),
    2
);

?>

</span>

</td>



<!-- STOCK -->

<td>

<span class="stock-number">

<?php

echo $current_stock;

?>

</span>

</td>



<!-- STATUS -->

<td>

<?php

if ($current_stock > 0) {

?>

<span class="status-badge status-available">

<span class="available">

<i class="fas fa-circle-check"></i>

Available

</span>

</span>

<?php

} else {

?>

<span class="status-badge status-out">

<span class="out-stock">

<i class="fas fa-circle-xmark"></i>

Out of Stock

</span>

</span>

<?php

}

?>

</td>



<!-- UPDATE -->

<td>

<form
    method="POST"
    class="update-form"
>


<input
    type="hidden"
    name="id"
    value="<?php
        echo intval($row['id']);
    ?>"
>


<input
    type="number"
    name="stock"
    class="stock-input"
    min="0"
    value="<?php
        echo $current_stock;
    ?>"
    required
>


<button
    type="submit"
    name="update_stock"
    class="update-btn"
    title="Update Stock"
>

<i class="fas fa-save"></i>

Update

</button>


</form>

</td>


</tr>


<?php

    }

} else {

?>


<tr>

<td
    colspan="8"
    style="padding:40px;color:#888;"
>

<i
    class="fas fa-box-open"
    style="font-size:35px;color:#E88F2A;"
></i>

<br><br>

No Cake Found

</td>

</tr>


<?php

}

?>


</tbody>

</table>

</div>


</div>



<!-- =====================================================
     BACK BUTTON
===================================================== -->

<div class="text-center">

<a
    href="admin_dashboard.php"
    class="back-btn"
>

<i class="fas fa-arrow-left"></i>

Back to Dashboard

</a>

</div>



<!-- =====================================================
     FOOTER
===================================================== -->

<div class="footer">

    © <?php echo date("Y"); ?>

    SWIFFIN Cake Shop

</div>


</div>



<!-- =====================================================
     SEARCH
===================================================== -->

<script>

document
.getElementById("searchInput")
.addEventListener("keyup", function () {

    let search =
        this.value.toLowerCase();

    let rows =
        document.querySelectorAll(
            "#stockTable tbody tr"
        );


    rows.forEach(function (row) {

        let text =
            row.innerText.toLowerCase();


        if (text.includes(search)) {

            row.style.display = "";

        } else {

            row.style.display = "none";

        }

    });

});

</script>


</body>

</html>
