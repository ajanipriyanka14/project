
<?php

session_start();

include "config.php";


/* =====================================================
   ADMIN CHECK
===================================================== */

if (!isset($_SESSION['admin_id'])) {

    header("Location: login.php");

    exit();

}


/* =====================================================
   DELETE CUSTOMER
===================================================== */

if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    $delete_query = mysqli_query(
        $conn,
        "DELETE FROM customer WHERE id='$id'"
    );

    if ($delete_query) {

        header("Location: customer.php?deleted=1");

        exit();

    } else {

        die(
            "Delete Error : " .
            mysqli_error($conn)
        );

    }

}


/* =====================================================
   UPDATE CUSTOMER
===================================================== */

if (isset($_POST['update_customer'])) {

    $id = intval($_POST['customer_id']);

    $name = mysqli_real_escape_string(
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


    $update_query = "

        UPDATE customer

        SET

            customer_name='$name',

            email='$email',

            mobile='$mobile',

            address='$address'

        WHERE id='$id'

    ";


    if (mysqli_query($conn, $update_query)) {

        header("Location: customer.php?updated=1");

        exit();

    } else {

        die(
            "Update Error : " .
            mysqli_error($conn)
        );

    }

}


/* =====================================================
   SEARCH
===================================================== */

$search = "";

if (isset($_GET['search'])) {

    $search = trim($_GET['search']);

}


/* =====================================================
   CUSTOMER QUERY
===================================================== */

if ($search != "") {

    $safe_search = mysqli_real_escape_string(
        $conn,
        $search
    );


    $customer_query = mysqli_query(
        $conn,

        "
        SELECT *
        FROM customer

        WHERE

        customer_name LIKE '%$safe_search%'

        OR email LIKE '%$safe_search%'

        OR mobile LIKE '%$safe_search%'

        ORDER BY id DESC
        "
    );

} else {

    $customer_query = mysqli_query(
        $conn,

        "
        SELECT *
        FROM customer

        ORDER BY id DESC
        "
    );

}


/* =====================================================
   TOTAL CUSTOMERS
===================================================== */

$total_customer_query = mysqli_query(
    $conn,

    "
    SELECT COUNT(*) AS total
    FROM customer
    "
);


$total_customers = mysqli_fetch_assoc(
    $total_customer_query
)['total'];


/* =====================================================
   TOTAL ORDERS
===================================================== */

$total_order_query = mysqli_query(
    $conn,

    "
    SELECT COUNT(*) AS total
    FROM orders
    "
);


$total_orders = 0;


if ($total_order_query) {

    $total_orders = mysqli_fetch_assoc(
        $total_order_query
    )['total'];

}


/* =====================================================
   TOTAL SALES
===================================================== */

$total_sales_query = mysqli_query(
    $conn,

    "
    SELECT SUM(amount) AS total
    FROM orders
    "
);


$total_sales = 0;


if ($total_sales_query) {

    $sales_data = mysqli_fetch_assoc(
        $total_sales_query
    );

    $total_sales =
        $sales_data['total'] ?? 0;

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
Customers | SWIFFIN Cake Shop
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
   GLOBAL
===================================================== */

* {

    box-sizing:border-box;

}


body {

    margin:0;

    background:#000;

    color:#fff;

    font-family:Arial,sans-serif;

}


/* =====================================================
   MAIN
===================================================== */

.customer-container {

    width:95%;

    max-width:1600px;

    margin:35px auto;

}


/* =====================================================
   HEADER
===================================================== */

.page-header {

    display:flex;

    justify-content:space-between;

    align-items:center;

    margin-bottom:30px;

}


.page-title {

    color:#E88F2A;

    font-size:32px;

    font-weight:bold;

    margin:0;

}


.subtitle {

    color:#888;

    margin-top:6px;

}


/* =====================================================
   STAT CARDS
===================================================== */

.stat-card {

    background:#171717;

    border:1px solid #333;

    border-radius:18px;

    padding:22px;

    transition:.3s;

    height:100%;

}


.stat-card:hover {

    transform:translateY(-5px);

    border-color:#E88F2A;

    box-shadow:
    0 10px 30px
    rgba(232,143,42,.18);

}


.stat-icon {

    width:52px;

    height:52px;

    border-radius:14px;

    display:flex;

    justify-content:center;

    align-items:center;

    background:#2b1b0c;

    color:#E88F2A;

    font-size:21px;

    margin-bottom:15px;

}


.stat-card h2 {

    margin:0;

    color:#fff;

    font-size:28px;

    font-weight:bold;

}


.stat-card p {

    color:#888;

    margin:5px 0 0;

}


/* =====================================================
   MAIN PANEL
===================================================== */

.table-box {

    background:#111;

    padding:25px;

    border-radius:20px;

    border:1px solid #333;

    box-shadow:
    0 0 25px
    rgba(232,143,42,.10);

    margin-top:30px;

}


/* =====================================================
   SEARCH
===================================================== */

.search-wrapper {

    position:relative;

}


.search-wrapper i {

    position:absolute;

    left:18px;

    top:15px;

    color:#777;

}


.search-input {

    width:100%;

    height:48px;

    padding-left:45px;

    padding-right:20px;

    background:#080808;

    color:#fff;

    border:1px solid #444;

    border-radius:25px;

    outline:none;

}


.search-input::placeholder {

    color:#777;

}


.search-input:focus {

    border-color:#E88F2A;

    box-shadow:
    0 0 10px
    rgba(232,143,42,.20);

}


.search-btn {

    height:48px;

    width:100%;

    background:#E88F2A;

    color:#fff;

    border:none;

    border-radius:25px;

    font-weight:bold;

}


.search-btn:hover {

    background:#d67d18;

}


.clear-btn {

    height:48px;

    width:100%;

    display:flex;

    align-items:center;

    justify-content:center;

    background:#292929;

    border:1px solid #444;

    color:#fff;

    border-radius:25px;

    text-decoration:none;

}


.clear-btn:hover {

    background:#E88F2A;

    color:#fff;

}


/* =====================================================
   TABLE
===================================================== */

.table-responsive {

    margin-top:28px;

}


.customer-table {

    width:100%;

    min-width:1050px;

    border-collapse:separate;

    border-spacing:0 10px;

}


.customer-table thead th {

    color:#888;

    font-size:12px;

    text-transform:uppercase;

    padding:12px;

    white-space:nowrap;

    border:none;

}


.customer-table tbody tr {

    background:#202020;

}


.customer-table tbody td {

    padding:16px 12px;

    color:#ddd;

    border:none;

    vertical-align:middle;

}


.customer-table tbody td:first-child {

    border-radius:12px 0 0 12px;

}


.customer-table tbody td:last-child {

    border-radius:0 12px 12px 0;

}


.customer-table tbody tr:hover {

    background:#292929;

}


/* =====================================================
   CUSTOMER
===================================================== */

.customer-info {

    display:flex;

    align-items:center;

    gap:12px;

}


.avatar {

    width:42px;

    height:42px;

    min-width:42px;

    border-radius:50%;

    background:#2b1b0c;

    color:#E88F2A;

    display:flex;

    align-items:center;

    justify-content:center;

    font-weight:bold;

    font-size:17px;

}


.customer-name {

    color:#fff;

    font-weight:bold;

}


.customer-email {

    color:#777;

    font-size:12px;

    margin-top:3px;

}


.customer-id {

    color:#E88F2A;

    font-weight:bold;

}


/* =====================================================
   ORDER COUNT
===================================================== */

.order-count {

    display:inline-block;

    background:#2b1b0c;

    color:#E88F2A;

    padding:7px 13px;

    border-radius:20px;

    font-weight:bold;

}


/* =====================================================
   TOTAL SPENT
===================================================== */

.spent {

    color:#E88F2A;

    font-weight:bold;

}


/* =====================================================
   ACTION BUTTONS
===================================================== */

.action-btn {

    width:36px;

    height:36px;

    display:inline-flex;

    align-items:center;

    justify-content:center;

    background:#292929;

    border:1px solid #444;

    color:#fff;

    border-radius:9px;

    margin-right:5px;

    text-decoration:none;

    cursor:pointer;

}


.action-btn:hover {

    background:#E88F2A;

    border-color:#E88F2A;

    color:#fff;

}


.delete-btn:hover {

    background:#dc3545;

    border-color:#dc3545;

}


/* =====================================================
   MODAL
===================================================== */

.modal-content {

    background:#181818;

    color:#fff;

    border:1px solid #444;

    border-radius:18px;

}


.modal-header {

    border-bottom:1px solid #333;

}


.modal-title {

    color:#E88F2A;

    font-weight:bold;

}


.btn-close {

    filter:invert(1);

}


.form-label {

    color:#fff;

    font-weight:bold;

}


.form-control {

    background:#080808;

    color:#fff;

    border:1px solid #444;

}


.form-control:focus {

    background:#080808;

    color:#fff;

    border-color:#E88F2A;

    box-shadow:none;

}


.form-control::placeholder {

    color:#777;

}


.save-btn {

    background:#E88F2A;

    color:#fff;

    border:none;

    border-radius:25px;

    padding:10px 22px;

    font-weight:bold;

}


.save-btn:hover {

    background:#d67d18;

}


/* =====================================================
   EMPTY
===================================================== */

.no-customer {

    text-align:center;

    padding:60px;

    color:#777;

}


.no-customer i {

    color:#E88F2A;

    font-size:50px;

    margin-bottom:15px;

}


/* =====================================================
   BACK
===================================================== */

.back-btn {

    display:inline-block;

    margin-top:25px;

    background:#E88F2A;

    color:#fff;

    text-decoration:none;

    padding:11px 25px;

    border-radius:25px;

    font-weight:bold;

}


.back-btn:hover {

    background:#fff;

    color:#000;

}


/* =====================================================
   MOBILE
===================================================== */

@media(max-width:768px) {

    .customer-container {

        width:98%;

        margin:20px auto;

    }


    .page-title {

        font-size:25px;

    }


    .table-box {

        padding:15px;

    }

}

</style>

</head>


<body>


<div class="customer-container">


<!-- =====================================================
     HEADER
===================================================== -->

<div class="page-header">

<div>

<h1 class="page-title">

<i class="fa-solid fa-users"></i>

&nbsp;Customers

</h1>


<div class="subtitle">

Manage all registered customers

</div>

</div>

</div>


<!-- =====================================================
     STATISTICS
===================================================== -->

<div class="row g-4">


<!-- TOTAL CUSTOMERS -->

<div class="col-lg-4 col-md-6">

<div class="stat-card">

<div class="stat-icon">

<i class="fa-solid fa-users"></i>

</div>


<h2>

<?php

echo $total_customers;

?>

</h2>


<p>

Total Customers

</p>

</div>

</div>


<!-- TOTAL ORDERS -->

<div class="col-lg-4 col-md-6">

<div class="stat-card">

<div class="stat-icon">

<i class="fa-solid fa-cart-shopping"></i>

</div>


<h2>

<?php

echo $total_orders;

?>

</h2>


<p>

Total Orders

</p>

</div>

</div>


<!-- TOTAL SALES -->

<div class="col-lg-4 col-md-6">

<div class="stat-card">

<div class="stat-icon">

<i class="fa-solid fa-indian-rupee-sign"></i>

</div>


<h2>

₹<?php

echo number_format(
    $total_sales,
    0
);

?>

</h2>


<p>

Total Order Sales

</p>

</div>

</div>

</div>


<!-- =====================================================
     CUSTOMER TABLE BOX
===================================================== -->

<div class="table-box">


<!-- SEARCH -->

<form
method="GET"
class="row g-3"
>


<div class="col-lg-7">

<div class="search-wrapper">

<i class="fa-solid fa-magnifying-glass"></i>


<input
type="text"
name="search"
class="search-input"
value="<?php

echo htmlspecialchars(
    $search
);

?>"
placeholder="Search customer by name, email or mobile..."
>

</div>

</div>


<div class="col-lg-2">

<button
type="submit"
class="search-btn"
>

<i class="fa-solid fa-search"></i>

&nbsp; Search

</button>

</div>


<div class="col-lg-2">

<a
href="customer.php"
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

<div class="table-responsive">


<table class="customer-table">


<thead>

<tr>

<th>ID</th>

<th>Customer</th>

<th>Mobile</th>

<th>Address</th>

<th>Orders</th>

<th>Total Spent</th>

<th>Action</th>

</tr>

</thead>


<tbody>


<?php


if (
    $customer_query &&
    mysqli_num_rows($customer_query) > 0
) {


while (
    $customer =
    mysqli_fetch_assoc(
        $customer_query
    )
) {


$customer_id =
intval(
    $customer['id']
);


/* =====================================================
   CUSTOMER ORDER DATA
===================================================== */

$customer_name_safe =
mysqli_real_escape_string(
    $conn,
    $customer['customer_name']
);


$order_info_query =
mysqli_query(

    $conn,

    "
    SELECT

        COUNT(*) AS order_count,

        COALESCE(
            SUM(amount),
            0
        ) AS total_spent

    FROM orders

    WHERE
        customer_name='$customer_name_safe'
    "

);


$order_count = 0;

$total_spent = 0;


if ($order_info_query) {

    $order_info =
        mysqli_fetch_assoc(
            $order_info_query
        );


    $order_count =
        intval(
            $order_info['order_count']
        );


    $total_spent =
        floatval(
            $order_info['total_spent']
        );

}


/* =====================================================
   AVATAR
===================================================== */

$first_letter =
strtoupper(
    substr(
        $customer['customer_name'],
        0,
        1
    )
);

?>


<tr>


<!-- ID -->

<td>

<span class="customer-id">

#<?php

echo $customer_id;

?>

</span>

</td>


<!-- CUSTOMER -->

<td>

<div class="customer-info">


<div class="avatar">

<?php

echo htmlspecialchars(
    $first_letter
);

?>

</div>


<div>

<div class="customer-name">

<?php

echo htmlspecialchars(
    $customer['customer_name']
);

?>

</div>


<div class="customer-email">

<?php

echo htmlspecialchars(
    $customer['email']
);

?>

</div>

</div>


</div>

</td>


<!-- MOBILE -->

<td>

<?php

echo htmlspecialchars(
    $customer['mobile']
);

?>

</td>


<!-- ADDRESS -->

<td>

<?php

$address =
trim(
    $customer['address']
);


if (
    strlen($address) > 35
) {

    echo htmlspecialchars(
        substr(
            $address,
            0,
            35
        )
    );

    echo "...";

} else {

    echo htmlspecialchars(
        $address
    );

}

?>

</td>


<!-- ORDERS -->

<td>

<span class="order-count">

<?php

echo $order_count;

?>

</span>

</td>


<!-- TOTAL SPENT -->

<td>

<span class="spent">

₹<?php

echo number_format(
    $total_spent,
    2
);

?>

</span>

</td>


<!-- ACTION -->

<td>


<!-- VIEW -->

<button
type="button"
class="action-btn"
title="View Customer"
data-bs-toggle="modal"
data-bs-target="#view<?php echo $customer_id; ?>"
>

<i class="fa-solid fa-eye"></i>

</button>


<!-- EDIT -->

<button
type="button"
class="action-btn"
title="Edit Customer"
data-bs-toggle="modal"
data-bs-target="#edit<?php echo $customer_id; ?>"
>

<i class="fa-solid fa-pen"></i>

</button>


<!-- DELETE -->

<a
href="customer.php?delete=<?php echo $customer_id; ?>"
class="action-btn delete-btn"
title="Delete Customer"
onclick="return confirm('Are you sure you want to delete this customer?');"
>

<i class="fa-solid fa-trash"></i>

</a>


</td>


</tr>


<!-- =====================================================
     VIEW MODAL
===================================================== -->

<div
class="modal fade"
id="view<?php echo $customer_id; ?>"
tabindex="-1"
>

<div class="modal-dialog modal-dialog-centered">

<div class="modal-content">


<div class="modal-header">

<h5 class="modal-title">

<i class="fa-solid fa-user"></i>

&nbsp;

Customer Details

</h5>


<button
type="button"
class="btn-close"
data-bs-dismiss="modal"
></button>

</div>


<div class="modal-body">


<div class="mb-3">

<label class="form-label">

Customer Name

</label>


<input
type="text"
class="form-control"
value="<?php

echo htmlspecialchars(
    $customer['customer_name']
);

?>"
readonly
>

</div>


<div class="mb-3">

<label class="form-label">

Email

</label>


<input
type="text"
class="form-control"
value="<?php

echo htmlspecialchars(
    $customer['email']
);

?>"
readonly
>

</div>


<div class="mb-3">

<label class="form-label">

Mobile

</label>


<input
type="text"
class="form-control"
value="<?php

echo htmlspecialchars(
    $customer['mobile']
);

?>"
readonly
>

</div>


<div class="mb-3">

<label class="form-label">

Address

</label>


<textarea
class="form-control"
rows="3"
readonly
><?php

echo htmlspecialchars(
    $customer['address']
);

?></textarea>

</div>


<div class="row">


<div class="col-md-6">

<label class="form-label">

Total Orders

</label>


<input
type="text"
class="form-control"
value="<?php

echo $order_count;

?>"
readonly
>

</div>


<div class="col-md-6">

<label class="form-label">

Total Spent

</label>


<input
type="text"
class="form-control"
value="₹<?php

echo number_format(
    $total_spent,
    2
);

?>"
readonly
>

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


<!-- =====================================================
     EDIT MODAL
===================================================== -->

<div
class="modal fade"
id="edit<?php echo $customer_id; ?>"
tabindex="-1"
>

<div class="modal-dialog modal-dialog-centered">

<div class="modal-content">


<form method="POST">


<div class="modal-header">

<h5 class="modal-title">

<i class="fa-solid fa-user-pen"></i>

&nbsp;

Edit Customer

</h5>


<button
type="button"
class="btn-close"
data-bs-dismiss="modal"
></button>

</div>


<div class="modal-body">


<input
type="hidden"
name="customer_id"
value="<?php

echo $customer_id;

?>"
>


<div class="mb-3">

<label class="form-label">

Customer Name

</label>


<input
type="text"
name="customer_name"
class="form-control"
value="<?php

echo htmlspecialchars(
    $customer['customer_name']
);

?>"
required
>

</div>


<div class="mb-3">

<label class="form-label">

Email

</label>


<input
type="email"
name="email"
class="form-control"
value="<?php

echo htmlspecialchars(
    $customer['email']
);

?>"
required
>

</div>


<div class="mb-3">

<label class="form-label">

Mobile

</label>


<input
type="text"
name="mobile"
class="form-control"
maxlength="15"
value="<?php

echo htmlspecialchars(
    $customer['mobile']
);

?>"
required
>

</div>


<div class="mb-3">

<label class="form-label">

Address

</label>


<textarea
name="address"
class="form-control"
rows="3"
required
><?php

echo htmlspecialchars(
    $customer['address']
);

?></textarea>

</div>


</div>


<div class="modal-footer">


<button
type="button"
class="btn btn-secondary"
data-bs-dismiss="modal"
>

Cancel

</button>


<button
type="submit"
name="update_customer"
class="save-btn"
>

<i class="fa-solid fa-save"></i>

&nbsp;

Save Changes

</button>


</div>


</form>


</div>

</div>

</div>


<?php

}

} else {

?>


<tr>

<td
colspan="7"
>

<div class="no-customer">

<i class="fa-solid fa-users-slash"></i>

<br>

No Customers Found

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


<!-- BACK -->

<div class="text-center">

<a
href="admin_dashboard.php"
class="back-btn"
>

<i class="fa-solid fa-arrow-left"></i>

&nbsp;

Back to Dashboard

</a>

</div>


</div>


<!-- BOOTSTRAP JS -->

<script
src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>


</body>

</html>
