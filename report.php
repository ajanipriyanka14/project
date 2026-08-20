<?php

include "config.php";

/* =====================================================
   ORDER REPORT
===================================================== */

$total_orders = 0;
$pending_orders = 0;
$delivered_orders = 0;
$cancelled_orders = 0;
$total_sales = 0;


/* GET ALL ORDERS */

$order_query = mysqli_query(
    $conn,
    "SELECT * FROM orders ORDER BY id DESC"
);

if (!$order_query) {
    die("Order Database Error: " . mysqli_error($conn));
}


/* CALCULATE ORDER REPORT */

while ($row = mysqli_fetch_assoc($order_query)) {

    $total_orders++;

    $status = strtolower(
        trim($row['status'] ?? '')
    );

    if ($status == "pending") {
        $pending_orders++;
    }

    elseif ($status == "delivered") {
        $delivered_orders++;
    }

    elseif ($status == "cancelled") {
        $cancelled_orders++;
    }

    $total_sales += floatval(
        $row['amount'] ?? 0
    );
}


/* =====================================================
   FEEDBACK REPORT
===================================================== */

$total_reviews = 0;
$total_rating = 0;
$five_star_reviews = 0;


$feedback_query = mysqli_query(
    $conn,
    "SELECT rating FROM feedback"
);

if (!$feedback_query) {
    die("Feedback Database Error: " . mysqli_error($conn));
}


while ($feedback = mysqli_fetch_assoc($feedback_query)) {

    $total_reviews++;

    $rating = intval(
        $feedback['rating'] ?? 0
    );

    $total_rating += $rating;

    if ($rating == 5) {
        $five_star_reviews++;
    }
}


/* =====================================================
   AVERAGE RATING
===================================================== */

$average_rating = 0;

if ($total_reviews > 0) {

    $average_rating =
        $total_rating / $total_reviews;
}


/* =====================================================
   RECENT ORDERS
===================================================== */

$recent_orders = mysqli_query(
    $conn,
    "SELECT *
     FROM orders
     ORDER BY id DESC
     LIMIT 10"
);

if (!$recent_orders) {
    die("Recent Orders Error: " . mysqli_error($conn));
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
Reports | SWIFFIN Cake Shop
</title>


<!-- =====================================================
     BOOTSTRAP
===================================================== -->

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet"
>


<!-- =====================================================
     FONT AWESOME
===================================================== -->

<link
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
rel="stylesheet"
>


<!-- =====================================================
     GOOGLE FONT
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
href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600;700&display=swap"
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

    min-height:100vh;

    background:#000;

    color:#fff;

    font-family:
    'Poppins',
    Arial,
    sans-serif;

    padding:35px 15px;

}


/* =====================================================
   BACKGROUND GLOW
===================================================== */

body::before{

    content:"";

    position:fixed;

    width:500px;
    height:500px;

    background:#E88F2A;

    border-radius:50%;

    filter:blur(180px);

    opacity:.08;

    top:-280px;
    left:-220px;

    pointer-events:none;

}


body::after{

    content:"";

    position:fixed;

    width:450px;
    height:450px;

    background:#E88F2A;

    border-radius:50%;

    filter:blur(180px);

    opacity:.06;

    bottom:-250px;
    right:-200px;

    pointer-events:none;

}


/* =====================================================
   MAIN
===================================================== */

.main{

    width:100%;

    max-width:1350px;

    margin:auto;

    position:relative;

    z-index:2;

}


/* =====================================================
   HEADER
===================================================== */

.header{

    text-align:center;

    margin-bottom:35px;

}


.logo{

    color:#E88F2A;

    font-size:15px;

    font-weight:700;

    letter-spacing:5px;

    margin-bottom:8px;

}


.title{

    color:#fff;

    font-size:32px;

    font-weight:700;

    margin-bottom:8px;

}


.title i{

    color:#E88F2A;

}


.subtitle{

    color:#777;

    font-size:14px;

}


.orange-line{

    width:65px;

    height:3px;

    background:#E88F2A;

    border-radius:20px;

    margin:15px auto 0;

}


/* =====================================================
   REPORT CARD
===================================================== */

.report-card{

    background:#111;

    border:1px solid #292929;

    border-radius:18px;

    padding:24px;

    margin-bottom:25px;

    position:relative;

    overflow:hidden;

    transition:.3s;

}


.report-card::before{

    content:"";

    position:absolute;

    width:80px;

    height:80px;

    background:#E88F2A;

    filter:blur(55px);

    opacity:.10;

    right:-25px;

    top:-25px;

}


.report-card:hover{

    border-color:#E88F2A;

    transform:translateY(-4px);

    box-shadow:
    0 10px 30px
    rgba(232,143,42,.12);

}


/* =====================================================
   REPORT ICON
===================================================== */

.report-icon{

    width:55px;

    height:55px;

    border-radius:14px;

    display:flex;

    align-items:center;

    justify-content:center;

    background:
    rgba(232,143,42,.10);

    border:1px solid
    rgba(232,143,42,.20);

    color:#E88F2A;

    font-size:21px;

    margin-bottom:15px;

}


/* =====================================================
   NUMBER
===================================================== */

.report-number{

    color:#fff;

    font-size:28px;

    font-weight:700;

    margin-bottom:4px;

}


/* =====================================================
   LABEL
===================================================== */

.report-label{

    color:#777;

    font-size:13px;

}


/* =====================================================
   CARD TOP LINE
===================================================== */

.card-top{

    display:flex;

    align-items:center;

    justify-content:space-between;

}


/* =====================================================
   PERCENTAGE / SMALL TEXT
===================================================== */

.small-info{

    color:#666;

    font-size:11px;

}


/* =====================================================
   SECTION
===================================================== */

.section-title{

    display:flex;

    align-items:center;

    gap:10px;

    color:#fff;

    font-size:20px;

    font-weight:600;

    margin:30px 0 15px;

}


.section-title i{

    color:#E88F2A;

}


/* =====================================================
   TABLE CARD
===================================================== */

.table-box{

    background:#111;

    border:1px solid #292929;

    border-radius:18px;

    overflow:hidden;

    box-shadow:
    0 10px 35px
    rgba(0,0,0,.35);

}


.table-header{

    padding:20px 24px;

    border-bottom:1px solid #292929;

    display:flex;

    justify-content:space-between;

    align-items:center;

}


.table-header h3{

    color:#fff;

    font-size:16px;

    margin:0;

}


.table-count{

    color:#E88F2A;

    background:
    rgba(232,143,42,.10);

    border:1px solid
    rgba(232,143,42,.20);

    padding:6px 12px;

    border-radius:20px;

    font-size:11px;

}


/* =====================================================
   TABLE
===================================================== */

.table-responsive{

    overflow-x:auto;

}


.table{

    margin:0 !important;

    min-width:900px;

}


.table thead th{

    background:#191919 !important;

    color:#888 !important;

    border-bottom:1px solid #333 !important;

    border-top:none !important;

    padding:16px 14px;

    text-align:center;

    font-size:11px;

    text-transform:uppercase;

    letter-spacing:.5px;

    white-space:nowrap;

}


.table tbody td{

    background:#111 !important;

    color:#ddd !important;

    border-color:#252525 !important;

    padding:16px 14px;

    text-align:center;

    vertical-align:middle;

    font-size:12px;

}


.table tbody tr{

    transition:.2s;

}


.table tbody tr:hover td{

    background:#171717 !important;

}


/* =====================================================
   ORDER ID
===================================================== */

.order-id{

    color:#E88F2A;

    font-weight:600;

}


/* =====================================================
   CUSTOMER
===================================================== */

.customer-name{

    color:#fff;

    font-weight:500;

}


/* =====================================================
   CAKE
===================================================== */

.cake-name{

    color:#ccc;

}


/* =====================================================
   AMOUNT
===================================================== */

.amount{

    color:#5ee28a;

    font-weight:600;

}


/* =====================================================
   STATUS
===================================================== */

.status{

    display:inline-flex;

    align-items:center;

    gap:6px;

    padding:6px 12px;

    border-radius:20px;

    font-size:11px;

    font-weight:600;

}


.status-pending{

    color:#ffc107;

    background:
    rgba(255,193,7,.10);

    border:1px solid
    rgba(255,193,7,.20);

}


.status-delivered{

    color:#5ee28a;

    background:
    rgba(25,135,84,.10);

    border:1px solid
    rgba(25,135,84,.20);

}


.status-cancelled{

    color:#ff7070;

    background:
    rgba(220,53,69,.10);

    border:1px solid
    rgba(220,53,69,.20);

}


.status-other{

    color:#0dcaf0;

    background:
    rgba(13,202,240,.10);

    border:1px solid
    rgba(13,202,240,.20);

}


/* =====================================================
   DATE
===================================================== */

.order-date{

    color:#777;

    white-space:nowrap;

}


.order-date i{

    color:#E88F2A;

    margin-right:5px;

}


/* =====================================================
   EMPTY
===================================================== */

.empty{

    padding:50px 20px !important;

    text-align:center !important;

}


.empty i{

    color:#444;

    font-size:35px;

    margin-bottom:12px;

}


.empty h4{

    color:#777;

    font-size:15px;

}


.empty p{

    color:#555;

    font-size:12px;

}


/* =====================================================
   BACK BUTTON
===================================================== */

.footer-action{

    text-align:center;

    margin-top:30px;

}


.back-btn{

    display:inline-flex;

    align-items:center;

    gap:8px;

    padding:12px 25px;

    background:#E88F2A;

    color:#fff;

    text-decoration:none;

    border-radius:10px;

    font-size:13px;

    font-weight:600;

    transition:.3s;

}


.back-btn:hover{

    background:#fff;

    color:#000;

    transform:translateY(-2px);

    box-shadow:
    0 5px 20px
    rgba(232,143,42,.20);

}


/* =====================================================
   MOBILE
===================================================== */

@media(max-width:768px){

    body{

        padding:25px 10px;

    }


    .title{

        font-size:25px;

    }


    .stats-row{

        row-gap:0;

    }


    .report-card{

        padding:20px;

    }


    .table-header{

        padding:17px;

    }

}

</style>

</head>


<body>


<div class="main">


<!-- =====================================================
     HEADER
===================================================== -->

<div class="header">

<div class="logo">

SWIFFIN CAKE SHOP

</div>


<div class="title">

<i class="fa-solid fa-chart-column"></i>

&nbsp; Sales & Order Reports

</div>


<div class="subtitle">

Complete overview of orders, sales and customer feedback

</div>


<div class="orange-line"></div>

</div>



<!-- =====================================================
     ORDER SUMMARY
===================================================== -->

<div class="row stats-row">


<!-- TOTAL ORDERS -->

<div class="col-lg-3 col-md-6">

<div class="report-card">

<div class="card-top">

<div class="report-icon">

<i class="fa-solid fa-bag-shopping"></i>

</div>

</div>


<div class="report-number">

<?php

echo $total_orders;

?>

</div>


<div class="report-label">

Total Orders

</div>

</div>

</div>



<!-- PENDING -->

<div class="col-lg-3 col-md-6">

<div class="report-card">

<div class="card-top">

<div class="report-icon">

<i class="fa-solid fa-clock"></i>

</div>

</div>


<div class="report-number">

<?php

echo $pending_orders;

?>

</div>


<div class="report-label">

Pending Orders

</div>

</div>

</div>



<!-- DELIVERED -->

<div class="col-lg-3 col-md-6">

<div class="report-card">

<div class="card-top">

<div class="report-icon">

<i class="fa-solid fa-truck"></i>

</div>

</div>


<div class="report-number">

<?php

echo $delivered_orders;

?>

</div>


<div class="report-label">

Delivered Orders

</div>

</div>

</div>



<!-- CANCELLED -->

<div class="col-lg-3 col-md-6">

<div class="report-card">

<div class="card-top">

<div class="report-icon">

<i class="fa-solid fa-ban"></i>

</div>

</div>


<div class="report-number">

<?php

echo $cancelled_orders;

?>

</div>


<div class="report-label">

Cancelled Orders

</div>

</div>

</div>


</div>



<!-- =====================================================
     SALES + FEEDBACK
===================================================== -->

<div class="row">


<!-- SALES -->

<div class="col-lg-4 col-md-6">

<div class="report-card">

<div class="report-icon">

<i class="fa-solid fa-indian-rupee-sign"></i>

</div>


<div class="report-number">

₹<?php

echo number_format(
    $total_sales,
    2
);

?>

</div>


<div class="report-label">

Total Sales

</div>

</div>

</div>



<!-- TOTAL REVIEWS -->

<div class="col-lg-4 col-md-6">

<div class="report-card">

<div class="report-icon">

<i class="fa-solid fa-comments"></i>

</div>


<div class="report-number">

<?php

echo $total_reviews;

?>

</div>


<div class="report-label">

Total Customer Reviews

</div>

</div>

</div>



<!-- AVERAGE -->

<div class="col-lg-4 col-md-12">

<div class="report-card">

<div class="report-icon">

<i class="fa-solid fa-star"></i>

</div>


<div class="report-number">

<?php

echo number_format(
    $average_rating,
    1
);

?>

 / 5

</div>


<div class="report-label">

Average Customer Rating

</div>

</div>

</div>


</div>



<!-- =====================================================
     RECENT ORDERS
===================================================== -->

<div class="section-title">

<i class="fa-solid fa-clock-rotate-left"></i>

Recent Orders

</div>


<div class="table-box">


<!-- TABLE HEADER -->

<div class="table-header">

<h3>

<i
class="fa-solid fa-receipt"
style="color:#E88F2A;margin-right:7px;"
></i>

Latest Order Activity

</h3>


<div class="table-count">

Last 10 Orders

</div>

</div>



<!-- TABLE -->

<div class="table-responsive">

<table class="table">


<thead>

<tr>

<th>
Order ID
</th>

<th>
Customer
</th>

<th>
Cake
</th>

<th>
Quantity
</th>

<th>
Amount
</th>

<th>
Payment
</th>

<th>
Status
</th>

<th>
Date
</th>

</tr>

</thead>


<tbody>


<?php

if (
    mysqli_num_rows(
        $recent_orders
    ) > 0
) {

while (
    $row =
    mysqli_fetch_assoc(
        $recent_orders
    )
) {

$status =
strtolower(
    trim(
        $row['status'] ?? ''
    )
);

?>


<tr>


<!-- ORDER ID -->

<td>

<span class="order-id">

#

<?php

echo intval(
    $row['id']
);

?>

</span>

</td>



<!-- CUSTOMER -->

<td>

<div class="customer-name">

<?php

echo htmlspecialchars(
    $row['customer_name']
    ?? 'Customer'
);

?>

</div>

</td>



<!-- CAKE -->

<td>

<div class="cake-name">

<i
class="fa-solid fa-cake-candles"
style="color:#E88F2A;margin-right:5px;"
></i>

<?php

echo htmlspecialchars(
    $row['cake_name']
    ?? 'Cake'
);

?>

</div>

</td>



<!-- QUANTITY -->

<td>

<?php

echo intval(
    $row['quantity']
    ?? 0
);

?>

</td>



<!-- AMOUNT -->

<td>

<span class="amount">

₹<?php

echo number_format(
    floatval(
        $row['amount']
        ?? 0
    ),
    2
);

?>

</span>

</td>



<!-- PAYMENT -->

<td>

<?php

$payment =
$row['payment_method']
?? '-';

echo htmlspecialchars(
    $payment
);

?>

</td>



<!-- STATUS -->

<td>


<?php

if ($status == "pending") {

?>

<span class="status status-pending">

<i class="fa-solid fa-clock"></i>

Pending

</span>

<?php

}

elseif (
    $status == "delivered"
) {

?>

<span class="status status-delivered">

<i class="fa-solid fa-circle-check"></i>

Delivered

</span>

<?php

}

elseif (
    $status == "cancelled"
) {

?>

<span class="status status-cancelled">

<i class="fa-solid fa-circle-xmark"></i>

Cancelled

</span>

<?php

}

else {

?>

<span class="status status-other">

<i class="fa-solid fa-circle-info"></i>

<?php

echo htmlspecialchars(
    $row['status']
    ?? 'Unknown'
);

?>

</span>

<?php

}

?>

</td>



<!-- DATE -->

<td>

<div class="order-date">

<?php

if (
    !empty(
        $row['order_date']
    )
) {

?>

<i class="fa-regular fa-calendar"></i>

<?php

echo date(
    "d M Y",
    strtotime(
        $row['order_date']
    )
);

?>

<br>

<span
style="color:#555;"
>

<?php

echo date(
    "h:i A",
    strtotime(
        $row['order_date']
    )
);

?>

</span>

<?php

}

else {

echo "-";

}

?>

</div>

</td>


</tr>


<?php

}

}

else {

?>


<tr>

<td
colspan="8"
class="empty"
>

<i class="fa-regular fa-folder-open"></i>

<h4>

No Orders Found

</h4>

<p>

Orders will appear here when customers place orders.

</p>

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

<div class="footer-action">

<a
href="admin_dashboard.php"
class="back-btn"
>

<i class="fa-solid fa-arrow-left"></i>

Back To Dashboard

</a>

</div>


</div>


</body>

</html>
