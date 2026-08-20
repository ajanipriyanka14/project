<?php

session_start();
include "config.php";

/* =====================================================
   UPDATE DELIVERY
===================================================== */

if (isset($_POST['update_delivery'])) {

    $id = intval($_POST['id'] ?? 0);

    $delivery_person = trim($_POST['delivery_person'] ?? "");
    $delivery_status = trim($_POST['delivery_status'] ?? "Pending");

    $delivery_person = mysqli_real_escape_string($conn, $delivery_person);
    $delivery_status = mysqli_real_escape_string($conn, $delivery_status);

    if ($id <= 0) {
        $error = "Invalid delivery ID.";
    } else {

        /* ================= DELIVERY DATE ================= */

        if ($delivery_status == "Delivered") {

            $delivery_date = date("Y-m-d H:i:s");

            $sql = "UPDATE delivery SET
                    delivery_person='$delivery_person',
                    delivery_status='$delivery_status',
                    delivery_date='$delivery_date'
                    WHERE id='$id'";

        } else {

            $sql = "UPDATE delivery SET
                    delivery_person='$delivery_person',
                    delivery_status='$delivery_status'
                    WHERE id='$id'";
        }

        /* ================= UPDATE ================= */

        if (mysqli_query($conn, $sql)) {

            echo "<script>
                    alert('Delivery Updated Successfully!');
                    window.location.href='delivery.php';
                  </script>";
            exit();

        } else {

            $error = "Update Failed: " . mysqli_error($conn);
        }
    }
}


/* =====================================================
   GET DELIVERY DATA
===================================================== */

$query = mysqli_query(
    $conn,
    "SELECT * FROM delivery ORDER BY id DESC"
);

if (!$query) {
    die("Database Error: " . mysqli_error($conn));
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

<title>Delivery Management | SWIFFIN Cake Shop</title>


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

    background:#000;

    color:#fff;

    font-family:Arial, Helvetica, sans-serif;

    min-height:100vh;

    overflow-x:hidden;
}


/* =====================================================
   ORANGE BACKGROUND GLOW
===================================================== */

body::before{

    content:"";

    position:fixed;

    width:500px;

    height:500px;

    background:#E88F2A;

    border-radius:50%;

    filter:blur(180px);

    opacity:.07;

    top:-250px;

    left:-250px;

    pointer-events:none;

    z-index:-1;
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

    right:-220px;

    pointer-events:none;

    z-index:-1;
}


/* =====================================================
   MAIN CONTAINER
===================================================== */

.main{

    width:94%;

    max-width:1450px;

    margin:40px auto 50px;
}


/* =====================================================
   PAGE HEADER
===================================================== */

.page-header{

    display:flex;

    justify-content:space-between;

    align-items:center;

    background:#111;

    border:1px solid #292929;

    border-left:4px solid #E88F2A;

    border-radius:12px;

    padding:22px 25px;

    margin-bottom:25px;

    box-shadow:
        0 5px 25px rgba(0,0,0,.5);
}


/* =====================================================
   HEADER LEFT
===================================================== */

.header-left{

    display:flex;

    align-items:center;

    gap:15px;
}


.header-icon{

    width:52px;

    height:52px;

    background:rgba(232,143,42,.12);

    border:1px solid rgba(232,143,42,.35);

    border-radius:12px;

    display:flex;

    align-items:center;

    justify-content:center;

    color:#E88F2A;

    font-size:22px;
}


.page-title h2{

    margin:0;

    color:#fff;

    font-size:25px;

    font-weight:bold;
}


.page-title p{

    margin:5px 0 0;

    color:#888;

    font-size:13px;
}


/* =====================================================
   DASHBOARD BUTTON
===================================================== */

.dashboard-btn{

    display:inline-flex;

    align-items:center;

    gap:8px;

    background:#E88F2A;

    color:#fff;

    text-decoration:none;

    padding:10px 18px;

    border-radius:8px;

    font-size:14px;

    font-weight:bold;

    transition:.3s;
}


.dashboard-btn:hover{

    background:#fff;

    color:#000;

    transform:translateY(-2px);
}


/* =====================================================
   ERROR MESSAGE
===================================================== */

.error-message{

    background:#250000;

    border:1px solid #ff3333;

    color:#ff7777;

    padding:12px 15px;

    border-radius:8px;

    margin-bottom:20px;

    text-align:center;
}


/* =====================================================
   STAT CARDS
===================================================== */

.stats{

    display:grid;

    grid-template-columns:
        repeat(4,1fr);

    gap:15px;

    margin-bottom:25px;
}


.stat-card{

    background:#111;

    border:1px solid #292929;

    border-radius:12px;

    padding:18px;

    display:flex;

    align-items:center;

    gap:15px;

    transition:.3s;
}


.stat-card:hover{

    border-color:#E88F2A;

    transform:translateY(-2px);
}


.stat-icon{

    width:45px;

    height:45px;

    border-radius:10px;

    background:rgba(232,143,42,.12);

    color:#E88F2A;

    display:flex;

    align-items:center;

    justify-content:center;

    font-size:18px;
}


.stat-info span{

    display:block;

    color:#777;

    font-size:12px;

    margin-bottom:3px;
}


.stat-info strong{

    color:#fff;

    font-size:20px;
}


/* =====================================================
   DELIVERY BOX
===================================================== */

.delivery-box{

    background:#111;

    border:1px solid #292929;

    border-radius:14px;

    overflow:hidden;

    box-shadow:
        0 8px 30px rgba(0,0,0,.45);
}


/* =====================================================
   TABLE HEADER
===================================================== */

.table-header{

    padding:18px 20px;

    border-bottom:1px solid #292929;

    display:flex;

    align-items:center;

    justify-content:space-between;
}


.table-header h5{

    margin:0;

    color:#fff;

    font-size:16px;

    font-weight:bold;
}


.table-header h5 i{

    color:#E88F2A;

    margin-right:8px;
}


.table-header span{

    color:#777;

    font-size:12px;
}


/* =====================================================
   TABLE
===================================================== */

.table-responsive{

    width:100%;

    overflow-x:auto;
}


.table{

    margin:0 !important;

    min-width:1200px;

    border-color:#292929 !important;
}


/* =====================================================
   TABLE HEAD
===================================================== */

.table thead th{

    background:#1b1b1b !important;

    color:#E88F2A !important;

    border-color:#292929 !important;

    padding:15px 12px;

    text-align:center;

    vertical-align:middle;

    font-size:12px;

    font-weight:bold;

    text-transform:uppercase;

    letter-spacing:.4px;

    white-space:nowrap;
}


/* =====================================================
   TABLE BODY
===================================================== */

.table tbody td{

    background:#111 !important;

    color:#ddd !important;

    border-color:#292929 !important;

    padding:14px 10px;

    text-align:center;

    vertical-align:middle;

    font-size:13px;
}


/* =====================================================
   ROW HOVER
===================================================== */

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

    font-weight:bold;
}


/* =====================================================
   CUSTOMER
===================================================== */

.customer-name{

    color:#fff;

    font-weight:bold;
}


.mobile-number{

    color:#999;

    font-size:12px;
}


/* =====================================================
   ADDRESS
===================================================== */

.address{

    max-width:180px;

    white-space:normal;

    line-height:1.5;

    color:#aaa;
}


/* =====================================================
   DELIVERY INPUT
===================================================== */

.delivery-input{

    width:150px;

    height:38px;

    background:#080808 !important;

    color:#fff !important;

    border:1px solid #333 !important;

    border-radius:7px;

    padding:0 10px;

    font-size:12px;

    outline:none;
}


.delivery-input:focus{

    border-color:#E88F2A !important;

    box-shadow:
        0 0 8px rgba(232,143,42,.25);
}


/* =====================================================
   STATUS SELECT
===================================================== */

.status-select{

    width:145px;

    height:38px;

    background:#080808 !important;

    color:#fff !important;

    border:1px solid #333 !important;

    border-radius:7px;

    padding:0 8px;

    font-size:12px;

    outline:none;

    cursor:pointer;
}


.status-select:focus{

    border-color:#E88F2A !important;

    box-shadow:
        0 0 8px rgba(232,143,42,.25);
}


/* =====================================================
   STATUS BADGE
===================================================== */

.status-badge{

    display:inline-flex;

    align-items:center;

    gap:6px;

    padding:6px 10px;

    border-radius:20px;

    font-size:11px;

    font-weight:bold;
}


.status-pending{

    color:#ffc107;

    background:rgba(255,193,7,.10);

    border:1px solid rgba(255,193,7,.2);
}


.status-assigned{

    color:#0dcaf0;

    background:rgba(13,202,240,.10);

    border:1px solid rgba(13,202,240,.2);
}


.status-delivery{

    color:#0d6efd;

    background:rgba(13,110,253,.10);

    border:1px solid rgba(13,110,253,.2);
}


.status-delivered{

    color:#28a745;

    background:rgba(40,167,69,.10);

    border:1px solid rgba(40,167,69,.2);
}


.status-cancelled{

    color:#dc3545;

    background:rgba(220,53,69,.10);

    border:1px solid rgba(220,53,69,.2);
}


/* =====================================================
   DATE
===================================================== */

.delivery-date{

    color:#aaa;

    font-size:12px;

    white-space:nowrap;
}


/* =====================================================
   UPDATE BUTTON
===================================================== */

.update-btn{

    display:inline-flex;

    align-items:center;

    justify-content:center;

    gap:6px;

    background:#E88F2A;

    color:#fff;

    border:none;

    border-radius:7px;

    padding:9px 14px;

    font-size:12px;

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
   REVIEW BUTTON
===================================================== */

.review-btn{

    display:inline-flex;

    align-items:center;

    justify-content:center;

    gap:6px;

    background:#1d6f42;

    color:#fff;

    text-decoration:none;

    border:1px solid #288d55;

    border-radius:7px;

    padding:9px 12px;

    font-size:11px;

    font-weight:bold;

    white-space:nowrap;

    transition:.3s;
}


.review-btn:hover{

    background:#28a745;

    color:#fff;

    transform:translateY(-1px);
}


.not-available{

    color:#555;

    font-size:11px;
}


/* =====================================================
   EMPTY DATA
===================================================== */

.empty-data{

    padding:50px !important;

    color:#666 !important;

    text-align:center;

    font-size:14px;
}


.empty-data i{

    display:block;

    color:#333;

    font-size:35px;

    margin-bottom:10px;
}


/* =====================================================
   FOOTER
===================================================== */

.page-footer{

    text-align:center;

    color:#555;

    font-size:12px;

    margin-top:20px;
}


/* =====================================================
   MOBILE
===================================================== */

@media(max-width:900px){

    .main{

        width:96%;

        margin-top:20px;
    }


    .page-header{

        flex-direction:column;

        align-items:flex-start;

        gap:18px;
    }


    .dashboard-btn{

        width:100%;

        justify-content:center;
    }


    .stats{

        grid-template-columns:
            repeat(2,1fr);
    }
}


@media(max-width:500px){

    .stats{

        grid-template-columns:1fr;
    }


    .page-title h2{

        font-size:21px;
    }


    .page-header{

        padding:18px;
    }
}

</style>

</head>


<body>


<div class="main">


<!-- =====================================================
     PAGE HEADER
===================================================== -->

<div class="page-header">


    <div class="header-left">


        <div class="header-icon">

            <i class="fas fa-truck-fast"></i>

        </div>


        <div class="page-title">

            <h2>
                Delivery Management
            </h2>

            <p>
                Manage customer deliveries and delivery status
            </p>

        </div>


    </div>


    <a
        href="admin_dashboard.php"
        class="dashboard-btn"
    >

        <i class="fas fa-arrow-left"></i>

        Dashboard

    </a>


</div>


<!-- =====================================================
     ERROR
===================================================== -->

<?php if (!empty($error)) { ?>

<div class="error-message">

    <i class="fas fa-circle-exclamation"></i>

    <?php echo htmlspecialchars($error); ?>

</div>

<?php } ?>


<!-- =====================================================
     STATISTICS
===================================================== -->

<?php

$total = 0;
$pending = 0;
$assigned = 0;
$delivered = 0;

mysqli_data_seek($query, 0);

while ($stat = mysqli_fetch_assoc($query)) {

    $total++;

    if ($stat['delivery_status'] == "Pending") {
        $pending++;
    }

    if ($stat['delivery_status'] == "Assigned") {
        $assigned++;
    }

    if ($stat['delivery_status'] == "Delivered") {
        $delivered++;
    }
}

mysqli_data_seek($query, 0);

?>


<div class="stats">


    <div class="stat-card">

        <div class="stat-icon">

            <i class="fas fa-truck"></i>

        </div>

        <div class="stat-info">

            <span>Total Deliveries</span>

            <strong>
                <?php echo $total; ?>
            </strong>

        </div>

    </div>


    <div class="stat-card">

        <div class="stat-icon">

            <i class="fas fa-clock"></i>

        </div>

        <div class="stat-info">

            <span>Pending</span>

            <strong>
                <?php echo $pending; ?>
            </strong>

        </div>

    </div>


    <div class="stat-card">

        <div class="stat-icon">

            <i class="fas fa-user-check"></i>

        </div>

        <div class="stat-info">

            <span>Assigned</span>

            <strong>
                <?php echo $assigned; ?>
            </strong>

        </div>

    </div>


    <div class="stat-card">

        <div class="stat-icon">

            <i class="fas fa-circle-check"></i>

        </div>

        <div class="stat-info">

            <span>Delivered</span>

            <strong>
                <?php echo $delivered; ?>
            </strong>

        </div>

    </div>


</div>


<!-- =====================================================
     DELIVERY BOX
===================================================== -->

<div class="delivery-box">


    <div class="table-header">

        <h5>

            <i class="fas fa-list"></i>

            Delivery Records

        </h5>


        <span>

            <?php echo $total; ?> records

        </span>

    </div>


    <div class="table-responsive">


        <table class="table table-bordered">


            <thead>

            <tr>

                <th>ID</th>

                <th>Order ID</th>

                <th>Customer</th>

                <th>Mobile</th>

                <th>Address</th>

                <th>Delivery Person</th>

                <th>Status</th>

                <th>Delivery Date</th>

                <th>Action</th>

                <th>Review</th>

            </tr>

            </thead>


            <tbody>


            <?php

            if (mysqli_num_rows($query) > 0) {

                while ($row = mysqli_fetch_assoc($query)) {

                    $status = $row['delivery_status'] ?? "Pending";

            ?>


            <!-- =================================================
                 ROW
            ================================================= -->

            <tr>


                <!-- ID -->

                <td>

                    <?php echo intval($row['id']); ?>

                </td>


                <!-- ORDER ID -->

                <td>

                    <span class="order-id">

                        #<?php
                        echo htmlspecialchars($row['order_id']);
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

                </td>


                <!-- MOBILE -->

                <td>

                    <div class="mobile-number">

                        <i class="fas fa-phone"></i>

                        <?php
                        echo htmlspecialchars(
                            $row['mobile']
                        );
                        ?>

                    </div>

                </td>


                <!-- ADDRESS -->

                <td>

                    <div class="address">

                        <?php
                        echo htmlspecialchars(
                            $row['address']
                        );
                        ?>

                    </div>

                </td>


                <!-- =================================================
                     DELIVERY FORM
                ================================================= -->

                <td colspan="4">


                    <form
                        method="POST"
                        style="
                            display:flex;
                            align-items:center;
                            justify-content:center;
                            gap:8px;
                            flex-wrap:wrap;
                        "
                    >


                        <input
                            type="hidden"
                            name="id"
                            value="<?php
                                echo intval($row['id']);
                            ?>"
                        >


                        <!-- DELIVERY PERSON -->

                        <input
                            type="text"
                            name="delivery_person"
                            class="delivery-input"
                            value="<?php
                                echo htmlspecialchars(
                                    $row['delivery_person'] ?? ""
                                );
                            ?>"
                            placeholder="Delivery Person"
                            required
                        >


                        <!-- STATUS -->

                        <select
                            name="delivery_status"
                            class="status-select"
                        >


                            <option
                                value="Pending"
                                <?php
                                if ($status == "Pending") {
                                    echo "selected";
                                }
                                ?>
                            >
                                Pending
                            </option>


                            <option
                                value="Assigned"
                                <?php
                                if ($status == "Assigned") {
                                    echo "selected";
                                }
                                ?>
                            >
                                Assigned
                            </option>


                            <option
                                value="Out For Delivery"
                                <?php
                                if ($status == "Out For Delivery") {
                                    echo "selected";
                                }
                                ?>
                            >
                                Out For Delivery
                            </option>


                            <option
                                value="Delivered"
                                <?php
                                if ($status == "Delivered") {
                                    echo "selected";
                                }
                                ?>
                            >
                                Delivered
                            </option>


                            <option
                                value="Cancelled"
                                <?php
                                if ($status == "Cancelled") {
                                    echo "selected";
                                }
                                ?>
                            >
                                Cancelled
                            </option>


                        </select>


                    </form>


                </td>


            </tr>


            <!-- =================================================
                 SECOND ROW FOR ACTIONS
            ================================================= -->

            <tr class="action-row">


                <td colspan="7"></td>


                <!-- DATE -->

                <td>

                    <?php

                    if (!empty($row['delivery_date'])) {

                        ?>

                        <span class="delivery-date">

                            <i class="fas fa-calendar"></i>

                            <?php

                            echo date(
                                "d-m-Y h:i A",
                                strtotime(
                                    $row['delivery_date']
                                )
                            );

                            ?>

                        </span>

                        <?php

                    } else {

                        echo "-";

                    }

                    ?>

                </td>


                <!-- UPDATE -->

                <td>

                    <form method="POST">

                        <input
                            type="hidden"
                            name="id"
                            value="<?php
                                echo intval($row['id']);
                            ?>"
                        >


                        <input
                            type="hidden"
                            name="delivery_person"
                            value="<?php
                                echo htmlspecialchars(
                                    $row['delivery_person'] ?? ""
                                );
                            ?>"
                        >


                        <input
                            type="hidden"
                            name="delivery_status"
                            value="<?php
                                echo htmlspecialchars($status);
                            ?>"
                        >


                        <button
                            type="submit"
                            name="update_delivery"
                            class="update-btn"
                        >

                            <i class="fas fa-save"></i>

                            Update

                        </button>

                    </form>

                </td>


            <?php if ($row['delivery_status'] == "Delivered") { ?>

				<a
					href="feedback.php?order_id=<?php echo (int)$row['order_id']; ?>"
					class="review-btn"
				>
					<i class="fas fa-star"></i>
					Rate & Review
				</a>

				<?php } else { ?>

				<span class="not-available">
					Available after delivery
				</span>

				<?php } ?>


            </tbody>

        </table>


    </div>

</div>


<!-- =====================================================
     FOOTER
===================================================== -->

<div class="page-footer">

    SWIFFIN Cake Shop &nbsp;•&nbsp;
    Delivery Management

</div>


</div>


</body>

</html>
