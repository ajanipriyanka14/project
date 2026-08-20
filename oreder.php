

<?php

session_start();

include "config.php";


/* =====================================================
   UPDATE ORDER STATUS
===================================================== */

if(isset($_POST['update_status']))
{
    $order_id = intval($_POST['order_id']);

    $status = mysqli_real_escape_string(
        $conn,
        $_POST['status']
    );

    $update_query = "
        UPDATE orders
        SET status='$status'
        WHERE id='$order_id'
    ";

    if(mysqli_query($conn, $update_query))
    {
        header("Location: order.php?success=1");
        exit();
    }
    else
    {
        die("Status Update Error : " . mysqli_error($conn));
    }
}


/* =====================================================
   SEARCH
===================================================== */

$search = "";

if(isset($_GET['search']))
{
    $search = trim($_GET['search']);
}


/* =====================================================
   FILTER
===================================================== */

$filter = "";

if(isset($_GET['filter']))
{
    $filter = trim($_GET['filter']);
}


/* =====================================================
   BUILD QUERY
===================================================== */

$where = [];


/* SEARCH */

if($search != "")
{
    $search_safe = mysqli_real_escape_string(
        $conn,
        $search
    );

    $where[] = "
        (
            id LIKE '%$search_safe%'
            OR customer_name LIKE '%$search_safe%'
            OR cake_name LIKE '%$search_safe%'
        )
    ";
}


/* FILTER */

if($filter != "")
{
    $filter_safe = mysqli_real_escape_string(
        $conn,
        $filter
    );

    $where[] = "
        status='$filter_safe'
    ";
}


/* FINAL QUERY */

$query = "
    SELECT *
    FROM orders
";


if(count($where) > 0)
{
    $query .= "
        WHERE " . implode(" AND ", $where);
}


$query .= "
    ORDER BY id DESC
";


$result = mysqli_query(
    $conn,
    $query
);


if(!$result)
{
    die(
        "Database Error : " .
        mysqli_error($conn)
    );
}


/* =====================================================
   STATISTICS
===================================================== */


/* TOTAL */

$total_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM orders"
);

$total_orders = mysqli_fetch_assoc(
    $total_query
)['total'];


/* PENDING */

$pending_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM orders
     WHERE status='Pending'"
);

$pending_orders = mysqli_fetch_assoc(
    $pending_query
)['total'];


/* PREPARING */

$preparing_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM orders
     WHERE status='Preparing'"
);

$preparing_orders = mysqli_fetch_assoc(
    $preparing_query
)['total'];


/* DELIVERED */

$delivered_query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total
     FROM orders
     WHERE status='Delivered'"
);

$delivered_orders = mysqli_fetch_assoc(
    $delivered_query
)['total'];

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
Orders | SWIFFIN Cake Shop
</title>


<!-- BOOTSTRAP -->

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet"
>


<!-- FONT AWESOME -->

<link
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css"
rel="stylesheet"
>


<style>

/* =====================================================
   BODY
===================================================== */

*{
    box-sizing:border-box;
}


body{

    margin:0;

    background:#000;

    color:#fff;

    font-family:
    Arial,
    Helvetica,
    sans-serif;

}


/* =====================================================
   MAIN
===================================================== */

.main-container{

    padding:35px;

    max-width:1600px;

    margin:auto;

}


/* =====================================================
   HEADER
===================================================== */

.page-header{

    display:flex;

    justify-content:space-between;

    align-items:center;

    margin-bottom:30px;

}


.page-header h1{

    margin:0;

    color:#E88F2A;

    font-size:32px;

    font-weight:bold;

}


.page-header p{

    margin-top:7px;

    color:#888;

    font-size:14px;

}


/* =====================================================
   DASHBOARD CARDS
===================================================== */

.stat-card{

    background:#171717;

    border:1px solid #303030;

    border-radius:18px;

    padding:22px;

    height:100%;

    transition:.3s;

    position:relative;

    overflow:hidden;

}


.stat-card:hover{

    transform:translateY(-5px);

    border-color:#E88F2A;

    box-shadow:
    0 10px 30px
    rgba(232,143,42,.18);

}


.stat-card::after{

    content:"";

    position:absolute;

    width:100px;

    height:100px;

    border-radius:50%;

    background:#E88F2A;

    opacity:.04;

    right:-30px;

    top:-30px;

}


.stat-icon{

    width:52px;

    height:52px;

    border-radius:14px;

    background:#2b1b0c;

    color:#E88F2A;

    display:flex;

    justify-content:center;

    align-items:center;

    font-size:21px;

    margin-bottom:15px;

}


.stat-card h2{

    margin:0;

    font-size:29px;

    font-weight:bold;

    color:#fff;

}


.stat-card p{

    margin:5px 0 0;

    color:#888;

    font-size:14px;

}


/* =====================================================
   ORDER PANEL
===================================================== */

.order-panel{

    background:#151515;

    border:1px solid #303030;

    border-radius:20px;

    margin-top:30px;

    padding:25px;

    box-shadow:
    0 0 25px
    rgba(232,143,42,.06);

}


/* =====================================================
   SEARCH
===================================================== */

.search-wrapper{

    position:relative;

}


.search-wrapper i{

    position:absolute;

    left:17px;

    top:14px;

    color:#777;

}


.search-input{

    width:100%;

    height:48px;

    padding:0 20px 0 45px;

    background:#080808;

    border:1px solid #444;

    border-radius:25px;

    color:#fff;

    outline:none;

}


.search-input::placeholder{

    color:#777;

}


.search-input:focus{

    border-color:#E88F2A;

    box-shadow:
    0 0 10px
    rgba(232,143,42,.20);

}


/* =====================================================
   SEARCH BUTTON
===================================================== */

.search-btn{

    height:48px;

    background:#E88F2A;

    border:none;

    color:#fff;

    padding:0 25px;

    border-radius:25px;

    font-weight:bold;

}


.search-btn:hover{

    background:#d67d18;

    color:#fff;

}


/* =====================================================
   FILTER
===================================================== */

.filter-select{

    height:48px;

    background:#080808;

    color:#fff;

    border:1px solid #444;

    border-radius:25px;

    padding:0 18px;

    width:100%;

    outline:none;

}


.filter-select:focus{

    border-color:#E88F2A;

}


/* =====================================================
   CLEAR BUTTON
===================================================== */

.clear-btn{

    height:48px;

    background:#292929;

    color:#fff;

    border:1px solid #444;

    border-radius:25px;

    padding:0 20px;

    text-decoration:none;

    display:flex;

    align-items:center;

    justify-content:center;

}


.clear-btn:hover{

    background:#E88F2A;

    color:#fff;

}


/* =====================================================
   TABLE
===================================================== */

.table-wrapper{

    margin-top:28px;

    overflow-x:auto;

}


.order-table{

    width:100%;

    min-width:950px;

    border-collapse:separate;

    border-spacing:0 10px;

}


.order-table thead th{

    color:#888;

    font-size:12px;

    text-transform:uppercase;

    font-weight:bold;

    padding:12px;

    border:none;

    white-space:nowrap;

}


.order-table tbody tr{

    background:#222;

}


.order-table tbody td{

    padding:17px 12px;

    color:#fff;

    border:none;

    vertical-align:middle;

}


.order-table tbody tr td:first-child{

    border-radius:12px 0 0 12px;

}


.order-table tbody tr td:last-child{

    border-radius:0 12px 12px 0;

}


.order-table tbody tr:hover{

    background:#292929;

}


/* =====================================================
   ORDER ID
===================================================== */

.order-id{

    color:#E88F2A;

    font-weight:bold;

    font-size:15px;

}


/* =====================================================
   CUSTOMER
===================================================== */

.customer-name{

    font-weight:bold;

    color:#fff;

}


.customer-label{

    color:#777;

    font-size:11px;

    margin-top:3px;

}


/* =====================================================
   CAKE
===================================================== */

.cake-name{

    color:#ddd;

    font-weight:bold;

}


/* =====================================================
   QUANTITY
===================================================== */

.quantity{

    background:#333;

    padding:5px 10px;

    border-radius:8px;

    font-weight:bold;

}


/* =====================================================
   AMOUNT
===================================================== */

.amount{

    color:#E88F2A;

    font-size:16px;

    font-weight:bold;

}


/* =====================================================
   STATUS BADGES
===================================================== */

.status-badge{

    display:inline-block;

    padding:7px 13px;

    border-radius:20px;

    font-size:11px;

    font-weight:bold;

    white-space:nowrap;

}


.status-pending{

    background:#3b3007;

    color:#ffc107;

}


.status-confirmed{

    background:#082e3d;

    color:#21d4fd;

}


.status-preparing{

    background:#102d45;

    color:#66b3ff;

}


.status-delivery{

    background:#29205a;

    color:#9a9cff;

}


.status-delivered{

    background:#07351c;

    color:#28df72;

}


.status-cancelled{

    background:#3b0c0c;

    color:#ff5b5b;

}


/* =====================================================
   ACTION BUTTON
===================================================== */

.view-btn{

    background:#292929;

    color:#E88F2A;

    border:1px solid #555;

    padding:7px 13px;

    border-radius:20px;

    cursor:pointer;

    font-size:12px;

    font-weight:bold;

}


.view-btn:hover{

    background:#E88F2A;

    color:#fff;

}


.update-btn{

    background:#E88F2A;

    color:#fff;

    border:none;

    padding:7px 12px;

    border-radius:20px;

    font-size:11px;

    font-weight:bold;

}


.update-btn:hover{

    background:#d67d18;

}


/* =====================================================
   STATUS SELECT
===================================================== */

.status-select{

    background:#080808;

    color:#fff;

    border:1px solid #444;

    border-radius:7px;

    padding:6px 8px;

    font-size:11px;

    margin-right:4px;

}


.status-select:focus{

    border-color:#E88F2A;

    outline:none;

}


/* =====================================================
   NO ORDERS
===================================================== */

.no-orders{

    text-align:center;

    padding:70px 20px;

    color:#777;

}


.no-orders i{

    font-size:55px;

    color:#E88F2A;

    margin-bottom:15px;

}


.no-orders h4{

    color:#aaa;

}


/* =====================================================
   MODAL
===================================================== */

.modal-content{

    background:#181818;

    color:#fff;

    border:1px solid #444;

    border-radius:18px;

}


.modal-header{

    border-bottom:1px solid #333;

}


.modal-title{

    color:#E88F2A;

    font-weight:bold;

}


.btn-close{

    filter:invert(1);

}


.detail-box{

    background:#222;

    border-radius:12px;

    padding:15px;

    margin-bottom:12px;

}


.detail-label{

    color:#888;

    font-size:12px;

    margin-bottom:5px;

}


.detail-value{

    color:#fff;

    font-size:16px;

    font-weight:bold;

}


.modal-footer{

    border-top:1px solid #333;

}


/* =====================================================
   MOBILE
===================================================== */

@media(max-width:768px){

    .main-container{

        padding:20px 12px;

    }


    .page-header h1{

        font-size:26px;

    }


    .order-panel{

        padding:15px;

    }


    .stat-card{

        margin-bottom:5px;

    }

}

</style>

</head>


<body>


<div class="main-container">


<!-- =====================================================
     HEADER
===================================================== -->

<div class="page-header">

<div>

<h1>

<i class="fa-solid fa-box-open"></i>

&nbsp;Manage Orders

</h1>

<p>

View, search and manage all customer orders

</p>

</div>

</div>


<!-- =====================================================
     TOP 4 CARDS
===================================================== -->

<div class="row g-4">


<!-- TOTAL -->

<div class="col-lg-3 col-md-6">

<div class="stat-card">

<div class="stat-icon">

<i class="fa-solid fa-cart-shopping"></i>

</div>

<h2>

<?php

echo $total_orders;

?>

</h2>

<p>Total Orders</p>

</div>

</div>


<!-- PENDING -->

<div class="col-lg-3 col-md-6">

<div class="stat-card">

<div class="stat-icon">

<i class="fa-solid fa-clock"></i>

</div>

<h2>

<?php

echo $pending_orders;

?>

</h2>

<p>Pending Orders</p>

</div>

</div>


<!-- PREPARING -->

<div class="col-lg-3 col-md-6">

<div class="stat-card">

<div class="stat-icon">

<i class="fa-solid fa-kitchen-set"></i>

</div>

<h2>

<?php

echo $preparing_orders;

?>

</h2>

<p>Preparing Orders</p>

</div>

</div>


<!-- DELIVERED -->

<div class="col-lg-3 col-md-6">

<div class="stat-card">

<div class="stat-icon">

<i class="fa-solid fa-circle-check"></i>

</div>

<h2>

<?php

echo $delivered_orders;

?>

</h2>

<p>Delivered Orders</p>

</div>

</div>

</div>


<!-- =====================================================
     ORDER PANEL
===================================================== -->

<div class="order-panel">


<!-- SEARCH + FILTER -->

<form
method="GET"
class="row g-3 align-items-center"
>


<!-- SEARCH -->

<div class="col-lg-5">

<div class="search-wrapper">

<i class="fa-solid fa-magnifying-glass"></i>

<input
type="text"
name="search"
class="search-input"
value="<?php echo htmlspecialchars($search); ?>"
placeholder="Search Order ID, Customer or Cake..."
>

</div>

</div>


<!-- FILTER -->

<div class="col-lg-3">

<select
name="filter"
class="filter-select"
>

<option value="">

All Order Status

</option>


<option
value="Pending"
<?php

if($filter=="Pending")
echo "selected";

?>
>

Pending

</option>


<option
value="Confirmed"
<?php

if($filter=="Confirmed")
echo "selected";

?>
>

Confirmed

</option>


<option
value="Preparing"
<?php

if($filter=="Preparing")
echo "selected";

?>
>

Preparing

</option>


<option
value="Out For Delivery"
<?php

if($filter=="Out For Delivery")
echo "selected";

?>
>

Out For Delivery

</option>


<option
value="Delivered"
<?php

if($filter=="Delivered")
echo "selected";

?>
>

Delivered

</option>


<option
value="Cancelled"
<?php

if($filter=="Cancelled")
echo "selected";

?>
>

Cancelled

</option>

</select>

</div>


<!-- SEARCH -->

<div class="col-lg-2">

<button
type="submit"
class="search-btn w-100"
>

<i class="fa-solid fa-search"></i>

&nbsp; Search

</button>

</div>


<!-- CLEAR -->

<div class="col-lg-2">

<a
href="order.php"
class="clear-btn"
>

<i class="fa-solid fa-rotate-left"></i>

&nbsp; Clear

</a>

</div>


</form>


<!-- =====================================================
     TABLE
===================================================== -->

<div class="table-wrapper">


<table class="order-table">


<thead>

<tr>

<th>Order</th>

<th>Customer</th>

<th>Cake</th>

<th>Qty</th>

<th>Amount</th>

<th>Status</th>

<th>Date</th>

<th>Action</th>

</tr>

</thead>


<tbody>


<?php


if(
    $result &&
    mysqli_num_rows($result) > 0
)
{


while(
    $row = mysqli_fetch_assoc($result)
)
{


$status = $row['status'];


/* STATUS CLASS */

$status_class = "status-pending";


if($status == "Confirmed")
{
    $status_class = "status-confirmed";
}


elseif($status == "Preparing")
{
    $status_class = "status-preparing";
}


elseif($status == "Out For Delivery")
{
    $status_class = "status-delivery";
}


elseif($status == "Delivered")
{
    $status_class = "status-delivered";
}


elseif($status == "Cancelled")
{
    $status_class = "status-cancelled";
}


?>


<tr>


<!-- ORDER ID -->

<td>

<span class="order-id">

#<?php

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
);

?>

</div>

<div class="customer-label">

Customer

</div>

</td>


<!-- CAKE -->

<td>

<span class="cake-name">

<?php

echo htmlspecialchars(
    $row['cake_name']
);

?>

</span>

</td>


<!-- QUANTITY -->

<td>

<span class="quantity">

<?php

echo intval(
    $row['quantity']
);

?>

</span>

</td>


<!-- AMOUNT -->

<td>

<span class="amount">

₹<?php

echo number_format(
    $row['amount'],
    2
);

?>

</span>

</td>


<!-- STATUS -->

<td>

<span class="status-badge <?php echo $status_class; ?>">

<?php

echo htmlspecialchars(
    $status
);

?>

</span>

</td>


<!-- DATE -->

<td>

<?php

if(!empty($row['order_date']))
{

echo date(
    "d M Y",
    strtotime(
        $row['order_date']
    )
);

}
else
{

echo "-";

}

?>

</td>


<!-- ACTION -->

<td>


<!-- VIEW DETAILS BUTTON -->

<button
type="button"
class="view-btn"
data-bs-toggle="modal"
data-bs-target="#orderModal<?php echo $row['id']; ?>"
>

<i class="fa-solid fa-eye"></i>

&nbsp; View

</button>


<!-- UPDATE STATUS -->

<form
method="POST"
style="margin-top:10px;"
>


<input
type="hidden"
name="order_id"
value="<?php

echo intval(
    $row['id']
);

?>"
>


<select
name="status"
class="status-select"
>

<option
value="Pending"
<?php

if($status=="Pending")
echo "selected";

?>
>

Pending

</option>


<option
value="Confirmed"
<?php

if($status=="Confirmed")
echo "selected";

?>
>

Confirmed

</option>


<option
value="Preparing"
<?php

if($status=="Preparing")
echo "selected";

?>
>

Preparing

</option>


<option
value="Out For Delivery"
<?php

if($status=="Out For Delivery")
echo "selected";

?>
>

Out For Delivery

</option>


<option
value="Delivered"
<?php

if($status=="Delivered")
echo "selected";

?>
>

Delivered

</option>


<option
value="Cancelled"
<?php

if($status=="Cancelled")
echo "selected";

?>
>

Cancelled

</option>

</select>


<button
type="submit"
name="update_status"
class="update-btn"
>

Update

</button>

</form>


</td>


</tr>


<!-- =====================================================
     ORDER DETAILS MODAL
===================================================== -->

<div
class="modal fade"
id="orderModal<?php echo $row['id']; ?>"
tabindex="-1"
>

<div
class="modal-dialog modal-dialog-centered"
>

<div class="modal-content">


<div class="modal-header">

<h5 class="modal-title">

<i class="fa-solid fa-receipt"></i>

&nbsp;

Order #<?php

echo intval(
    $row['id']
);

?>

</h5>


<button
type="button"
class="btn-close"
data-bs-dismiss="modal"
></button>

</div>


<div class="modal-body">


<div class="row">


<!-- CUSTOMER -->

<div class="col-md-6">

<div class="detail-box">

<div class="detail-label">

Customer Name

</div>

<div class="detail-value">

<?php

echo htmlspecialchars(
    $row['customer_name']
);

?>

</div>

</div>

</div>


<!-- CAKE -->

<div class="col-md-6">

<div class="detail-box">

<div class="detail-label">

Cake Name

</div>

<div class="detail-value">

<?php

echo htmlspecialchars(
    $row['cake_name']
);

?>

</div>

</div>

</div>


<!-- QUANTITY -->

<div class="col-md-6">

<div class="detail-box">

<div class="detail-label">

Quantity

</div>

<div class="detail-value">

<?php

echo intval(
    $row['quantity']
);

?>

</div>

</div>

</div>


<!-- AMOUNT -->

<div class="col-md-6">

<div class="detail-box">

<div class="detail-label">

Total Amount

</div>

<div
class="detail-value"
style="color:#E88F2A;font-size:21px;"
>

₹<?php

echo number_format(
    $row['amount'],
    2
);

?>

</div>

</div>

</div>


<!-- STATUS -->

<div class="col-md-6">

<div class="detail-box">

<div class="detail-label">

Order Status

</div>

<div>

<span
class="status-badge <?php echo $status_class; ?>"
>

<?php

echo htmlspecialchars(
    $status
);

?>

</span>

</div>

</div>

</div>


<!-- DATE -->

<div class="col-md-6">

<div class="detail-box">

<div class="detail-label">

Order Date

</div>

<div class="detail-value">

<?php

if(!empty($row['order_date']))
{

echo date(
    "d M Y",
    strtotime(
        $row['order_date']
    )
);

}
else
{

echo "-";

}

?>

</div>

</div>

</div>


</div>


</div>


<div class="modal-footer">

<button
type="button"
class="btn btn-secondary"
data-bs-dismiss="modal"
>

Close

</button>

</div>


</div>

</div>

</div>


<?php

}

}
else
{

?>


<tr>

<td colspan="8">

<div class="no-orders">

<i class="fa-solid fa-box-open"></i>

<h4>

No Orders Found

</h4>

<p>

Try another search or filter.

</p>

</div>

</td>

</tr>


<?php

}

?>


</tbody>

</table>

</div>


</div>


</div>


<!-- BOOTSTRAP JS -->

<script
src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


</body>

</html>

