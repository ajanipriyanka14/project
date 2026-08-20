
<?php

include "config.php";

/* =====================================================
   GET FEEDBACK
===================================================== */

$query = mysqli_query(
    $conn,
    "SELECT * FROM feedback ORDER BY id DESC"
);

if (!$query) {
    die("Database Error: " . mysqli_error($conn));
}


/* =====================================================
   FEEDBACK STATISTICS
===================================================== */

$total_feedback = mysqli_num_rows($query);

$average_rating = 0;

$rating_query = mysqli_query(
    $conn,
    "SELECT AVG(rating) AS avg_rating FROM feedback"
);

if ($rating_query) {

    $rating_data = mysqli_fetch_assoc($rating_query);

    $average_rating = round(
        floatval($rating_data['avg_rating']),
        1
    );
}


/* =====================================================
   FIVE STAR COUNT
===================================================== */

$five_star = 0;

$five_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM feedback WHERE rating=5"
);

if ($five_query) {

    $five_data = mysqli_fetch_assoc($five_query);

    $five_star = intval($five_data['total']);
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
Customer Feedback | SWIFFIN Cake Shop
</title>


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


<!-- =====================================================
     FONT AWESOME
===================================================== -->

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

    min-height:100vh;

    background:#000;

    color:#fff;

    font-family:
    'Poppins',
    Arial,
    sans-serif;

    padding:35px 15px;

    overflow-x:hidden;

}


/* =====================================================
   BACKGROUND GLOW
===================================================== */

body::before{

    content:"";

    position:fixed;

    width:450px;

    height:450px;

    background:#E88F2A;

    border-radius:50%;

    filter:blur(180px);

    opacity:.08;

    top:-250px;

    left:-200px;

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

.container{

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

    font-size:16px;

    font-weight:700;

    letter-spacing:5px;

    margin-bottom:8px;

}


.header h1{

    color:#fff;

    font-size:32px;

    font-weight:700;

    margin-bottom:8px;

}


.header h1 i{

    color:#E88F2A;

}


.header p{

    color:#888;

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
   STATISTICS
===================================================== */

.stats{

    display:grid;

    grid-template-columns:
    repeat(3,1fr);

    gap:20px;

    margin-bottom:30px;

}


.stat-card{

    background:#111;

    border:1px solid #292929;

    border-radius:16px;

    padding:22px;

    display:flex;

    align-items:center;

    gap:17px;

    transition:.3s;

}


.stat-card:hover{

    border-color:#E88F2A;

    transform:translateY(-3px);

    box-shadow:
    0 8px 25px rgba(232,143,42,.12);

}


.stat-icon{

    width:55px;

    height:55px;

    border-radius:13px;

    background:
    rgba(232,143,42,.12);

    border:1px solid
    rgba(232,143,42,.25);

    display:flex;

    align-items:center;

    justify-content:center;

    color:#E88F2A;

    font-size:22px;

}


.stat-info span{

    display:block;

    color:#777;

    font-size:12px;

    margin-bottom:3px;

}


.stat-info strong{

    display:block;

    color:#fff;

    font-size:23px;

}


/* =====================================================
   MAIN CARD
===================================================== */

.feedback-card{

    background:#111;

    border:1px solid #292929;

    border-radius:20px;

    overflow:hidden;

    box-shadow:
    0 10px 40px rgba(0,0,0,.5);

}


/* =====================================================
   CARD HEADER
===================================================== */

.card-header{

    padding:22px 25px;

    border-bottom:1px solid #292929;

    display:flex;

    justify-content:space-between;

    align-items:center;

    gap:15px;

}


.card-title{

    display:flex;

    align-items:center;

    gap:10px;

}


.card-title i{

    color:#E88F2A;

    font-size:19px;

}


.card-title h2{

    color:#fff;

    font-size:18px;

    font-weight:600;

}


.feedback-count{

    background:
    rgba(232,143,42,.12);

    color:#E88F2A;

    border:1px solid
    rgba(232,143,42,.25);

    padding:6px 12px;

    border-radius:20px;

    font-size:12px;

    font-weight:600;

}


/* =====================================================
   TABLE WRAPPER
===================================================== */

.table-wrapper{

    width:100%;

    overflow-x:auto;

}


table{

    width:100%;

    min-width:1050px;

    border-collapse:collapse;

}


/* =====================================================
   TABLE HEADER
===================================================== */

thead th{

    background:#191919;

    color:#999;

    padding:16px 14px;

    text-align:center;

    font-size:12px;

    font-weight:600;

    text-transform:uppercase;

    letter-spacing:.5px;

    border-bottom:1px solid #333;

    white-space:nowrap;

}


/* =====================================================
   TABLE BODY
===================================================== */

tbody td{

    background:#111;

    color:#ddd;

    padding:17px 14px;

    text-align:center;

    font-size:13px;

    border-bottom:1px solid #242424;

    vertical-align:middle;

}


tbody tr{

    transition:.25s;

}


tbody tr:hover td{

    background:#171717;

}


/* =====================================================
   ID
===================================================== */

.id-badge{

    display:inline-flex;

    align-items:center;

    justify-content:center;

    min-width:34px;

    height:30px;

    padding:0 8px;

    background:#1d1d1d;

    border:1px solid #333;

    border-radius:8px;

    color:#aaa;

    font-size:12px;

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

.customer{

    display:flex;

    align-items:center;

    gap:10px;

    text-align:left;

    min-width:150px;

}


.customer-icon{

    width:34px;

    height:34px;

    border-radius:50%;

    background:
    rgba(232,143,42,.12);

    color:#E88F2A;

    display:flex;

    align-items:center;

    justify-content:center;

    font-size:13px;

    flex-shrink:0;

}


.customer-name{

    color:#fff;

    font-weight:500;

}


/* =====================================================
   EMAIL
===================================================== */

.email{

    color:#aaa;

    font-size:12px;

    white-space:nowrap;

}


/* =====================================================
   CAKE
===================================================== */

.cake-name{

    color:#fff;

    font-weight:500;

}


/* =====================================================
   RATING
===================================================== */

.rating-box{

    display:flex;

    flex-direction:column;

    align-items:center;

    gap:4px;

}


.stars{

    color:#ffc107;

    font-size:16px;

    letter-spacing:1px;

    white-space:nowrap;

}


.rating-number{

    color:#777;

    font-size:11px;

}


/* =====================================================
   REVIEW
===================================================== */

.review{

    max-width:330px;

    min-width:250px;

    text-align:left;

    color:#aaa;

    font-size:12px;

    line-height:1.6;

}


.review i{

    color:#555;

    margin-right:5px;

}


/* =====================================================
   DATE
===================================================== */

.date{

    color:#888;

    font-size:12px;

    white-space:nowrap;

}


.date i{

    color:#E88F2A;

    margin-right:5px;

}


/* =====================================================
   EMPTY
===================================================== */

.empty{

    padding:60px 20px !important;

    text-align:center !important;

}


.empty-icon{

    width:70px;

    height:70px;

    margin:0 auto 15px;

    border-radius:50%;

    background:#1b1b1b;

    border:1px solid #333;

    display:flex;

    align-items:center;

    justify-content:center;

    color:#555;

    font-size:28px;

}


.empty h3{

    color:#aaa;

    font-size:17px;

    margin-bottom:5px;

}


.empty p{

    color:#666;

    font-size:12px;

}


/* =====================================================
   FOOTER ACTION
===================================================== */

.footer-action{

    text-align:center;

    margin-top:25px;

}


.back-btn{

    display:inline-flex;

    align-items:center;

    gap:8px;

    padding:11px 24px;

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
    0 5px 20px rgba(232,143,42,.2);

}


/* =====================================================
   MOBILE
===================================================== */

@media(max-width:800px){

    body{

        padding:25px 10px;

    }


    .header h1{

        font-size:25px;

    }


    .stats{

        grid-template-columns:1fr;

        gap:12px;

    }


    .stat-card{

        padding:17px;

    }


    .card-header{

        padding:18px;

    }


    .feedback-card{

        border-radius:15px;

    }

}


</style>

</head>


<body>


<div class="container">


<!-- =====================================================
     HEADER
===================================================== -->

<div class="header">

<div class="logo">

SWIFFIN CAKE SHOP

</div>


<h1>

<i class="fa-solid fa-comments"></i>

Customer Feedback

</h1>


<p>

View customer ratings, reviews and experiences

</p>


<div class="orange-line"></div>

</div>


<!-- =====================================================
     STATISTICS
===================================================== -->

<div class="stats">


<!-- TOTAL -->

<div class="stat-card">

<div class="stat-icon">

<i class="fa-solid fa-comments"></i>

</div>

<div class="stat-info">

<span>Total Reviews</span>

<strong>

<?php echo $total_feedback; ?>

</strong>

</div>

</div>


<!-- AVERAGE -->

<div class="stat-card">

<div class="stat-icon">

<i class="fa-solid fa-star"></i>

</div>

<div class="stat-info">

<span>Average Rating</span>

<strong>

<?php echo number_format($average_rating,1); ?>


</strong>

</div>

</div>


<!-- FIVE STAR -->

<div class="stat-card">

<div class="stat-icon">

<i class="fa-solid fa-heart"></i>

</div>

<div class="stat-info">

<span>5 Star Reviews</span>

<strong>

<?php echo $five_star; ?>

</strong>

</div>

</div>


</div>


<!-- =====================================================
     FEEDBACK CARD
===================================================== -->

<div class="feedback-card">


<!-- CARD HEADER -->

<div class="card-header">


<div class="card-title">

<i class="fa-solid fa-message"></i>

<h2>

Customer Reviews

</h2>

</div>


<div class="feedback-count">

<?php echo $total_feedback; ?>

Reviews

</div>


</div>


<!-- =====================================================
     TABLE
===================================================== -->

<div class="table-wrapper">


<table>


<thead>

<tr>

<th>ID</th>

<th>Order</th>

<th>Customer</th>

<th>Email</th>

<th>Cake</th>

<th>Rating</th>

<th>Review</th>

<th>Date</th>

</tr>

</thead>


<tbody>


<?php

if ($total_feedback > 0) {


while ($row = mysqli_fetch_assoc($query)) {

?>


<tr>


<!-- ID -->

<td>

<span class="id-badge">

#

<?php

echo intval($row['id']);

?>

</span>

</td>


<!-- ORDER -->

<td>

<span class="order-id">

#

<?php

echo intval($row['order_id']);

?>

</span>

</td>


<!-- CUSTOMER -->

<td>

<div class="customer">

<div class="customer-icon">

<i class="fa-solid fa-user"></i>

</div>

<div class="customer-name">

<?php

echo htmlspecialchars(
    $row['customer_name']
);

?>

</div>

</div>

</td>


<!-- EMAIL -->

<td>

<div class="email">

<i class="fa-solid fa-envelope"></i>

<?php

echo htmlspecialchars(
    $row['email']
);

?>

</div>

</td>


<!-- CAKE -->

<td>

<div class="cake-name">

<i
class="fa-solid fa-cake-candles"
style="color:#E88F2A;"
></i>

<?php

echo htmlspecialchars(
    $row['cake_name']
);

?>

</div>

</td>


<!-- RATING -->

<td>

<div class="rating-box">

<div class="stars">

<?php

$rating =
intval($row['rating']);

for (
    $i = 1;
    $i <= 5;
    $i++
) {

    if ($i <= $rating) {

        echo "★";

    } else {

        echo "☆";

    }

}

?>

</div>


<div class="rating-number">

<?php

echo $rating;

?> / 5

</div>

</div>

</td>


<!-- REVIEW -->

<td>

<div class="review">

<i class="fa-solid fa-quote-left"></i>

<?php

echo nl2br(
    htmlspecialchars(
        $row['message']
    )
);
?>

</div>

</td>


<!-- DATE -->

<td>

<div class="date">

<?php

if (
    !empty(
        $row['feedback_date']
    )
) {

?>

<i class="fa-regular fa-calendar"></i>

<?php

echo date(
    "d M Y",
    strtotime(
        $row['feedback_date']
    )
);

?>

<br>

<span style="color:#555;">

<?php

echo date(
    "h:i A",
    strtotime(
        $row['feedback_date']
    )
);

?>

</span>

<?php

} else {

echo "-";

}

?>

</div>

</td>


</tr>


<?php

}

} else {

?>


<tr>

<td
colspan="8"
class="empty"
>


<div class="empty-icon">

<i class="fa-regular fa-comment-dots"></i>

</div>


<h3>

No Customer Feedback

</h3>


<p>

Customer reviews will appear here.

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

Back to Dashboard

</a>

</div>


</div>


</body>

</html>
