

<?php

session_start();

include("config.php");


/* =====================================================
   ADMIN LOGIN CHECK
===================================================== */

if (
    !isset($_SESSION['admin_id']) &&
    !isset($_SESSION['admin']) &&
    !isset($_SESSION['admin_name'])
) {
    header("Location: admin.php");
    exit();
}


/* =====================================================
   ADMIN NAME
===================================================== */

$admin_name = $_SESSION['admin_name']
           ?? $_SESSION['admin']
           ?? "Admin";


/* =====================================================
   TOTAL CAKES
===================================================== */

$total_cakes = 0;

$query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM cake"
);

if ($query) {

    $row = mysqli_fetch_assoc($query);

    $total_cakes = intval($row['total']);

}


/* =====================================================
   TOTAL CUSTOMERS
===================================================== */

$total_customers = 0;

$query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM customer"
);

if ($query) {

    $row = mysqli_fetch_assoc($query);

    $total_customers = intval($row['total']);

}


/* =====================================================
   TOTAL ORDERS
===================================================== */

$total_orders = 0;

$query = mysqli_query(
    $conn,
    "SELECT COUNT(*) AS total FROM orders"
);

if ($query) {

    $row = mysqli_fetch_assoc($query);

    $total_orders = intval($row['total']);

}


/* =====================================================
   TOTAL REVENUE
   Only Delivered Orders
===================================================== */

$total_revenue = 0;

$query = mysqli_query(
    $conn,
    "SELECT SUM(amount) AS total
     FROM orders
     WHERE status = 'Delivered'"
);

if ($query) {

    $row = mysqli_fetch_assoc($query);

    $total_revenue = floatval(
        $row['total'] ?? 0
    );

}


/* =====================================================
   RECENT ORDERS
===================================================== */

$recent_orders = false;

$query = mysqli_query(
    $conn,
    "SELECT *
     FROM orders
     ORDER BY id DESC
     LIMIT 5"
);

if ($query) {

    $recent_orders = $query;

}

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>
    Admin Dashboard | Swiffin Cake Shop
</title>


<style>

/* ================= RESET ================= */

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}


body {

    font-family: Arial, sans-serif;

    background: #000;

    color: #fff;

}


/* ================= SIDEBAR ================= */

.sidebar {

    position: fixed;

    left: 0;
    top: 0;

    width: 250px;

    height: 100vh;

    background: #111;

    border-right: 1px solid #333;

    overflow-y: auto;

    z-index: 1000;

}


/* ================= LOGO ================= */

.logo {

    height: 75px;

    display: flex;

    align-items: center;

    justify-content: center;

    color: #E88F2A;

    font-size: 28px;

    font-weight: bold;

    letter-spacing: 3px;

    border-bottom: 1px solid #333;

}


/* ================= ADMIN BOX ================= */

.admin-box {

    text-align: center;

    padding: 20px;

    border-bottom: 1px solid #333;

}


.admin-icon {

    width: 48px;

    height: 48px;

    margin: auto;

    margin-bottom: 10px;

    border-radius: 50%;

    background: #E88F2A;

    color: #000;

    display: flex;

    align-items: center;

    justify-content: center;

    font-size: 23px;

}


.admin-box h4 {

    color: #fff;

    margin-bottom: 5px;

}


.admin-box p {

    color: #777;

    font-size: 12px;

}


/* ================= MENU TITLE ================= */

.menu-title {

    color: #666;

    font-size: 11px;

    font-weight: bold;

    padding: 18px 20px 8px;

    text-transform: uppercase;

    letter-spacing: 1px;

}


/* ================= SIDEBAR LINK ================= */

.sidebar a {

    display: flex;

    align-items: center;

    gap: 12px;

    padding: 12px 20px;

    color: #bbb;

    text-decoration: none;

    font-size: 14px;

    transition: 0.3s;

}


.sidebar a:hover {

    background: #1b1b1b;

    color: #E88F2A;

    border-left: 3px solid #E88F2A;

    padding-left: 17px;

}


.sidebar a.active {

    background: rgba(232,143,42,0.12);

    color: #E88F2A;

    border-left: 3px solid #E88F2A;

    padding-left: 17px;

}


.icon {

    width: 22px;

    text-align: center;

    font-size: 17px;

}


/* ================= MAIN ================= */

.main {

    margin-left: 250px;

    min-height: 100vh;

}


/* ================= TOPBAR ================= */

.topbar {

    height: 75px;

    background: #111;

    border-bottom: 1px solid #333;

    display: flex;

    align-items: center;

    justify-content: space-between;

    padding: 0 30px;

    position: sticky;

    top: 0;

    z-index: 500;

}


.topbar h2 {

    font-size: 22px;

    color: #fff;

}


.topbar p {

    color: #777;

    font-size: 12px;

    margin-top: 5px;

}


.admin-name {

    color: #aaa;

    font-size: 14px;

}


.admin-name span {

    color: #E88F2A;

    font-weight: bold;

}


.logout-btn {

    text-decoration: none;

    background: #E88F2A;

    color: #fff;

    padding: 9px 18px;

    border-radius: 5px;

    font-size: 13px;

    font-weight: bold;

    margin-left: 18px;

    transition: 0.3s;

}


.logout-btn:hover {

    background: #fff;

    color: #000;

}


/* ================= CONTENT ================= */

.content {

    padding: 30px;

}


/* ================= WELCOME ================= */

.welcome {

    background: #111;

    border: 1px solid #333;

    border-left: 5px solid #E88F2A;

    padding: 25px;

    border-radius: 8px;

    margin-bottom: 30px;

}


.welcome h1 {

    color: #E88F2A;

    font-size: 25px;

    margin-bottom: 8px;

}


.welcome p {

    color: #999;

    font-size: 14px;

}


/* ================= SECTION TITLE ================= */

.section-title {

    color: #fff;

    font-size: 18px;

    margin-bottom: 18px;

}


/* ================= STAT CARDS ================= */

.stats {

    display: grid;

    grid-template-columns: repeat(4, 1fr);

    gap: 18px;

    margin-bottom: 35px;

}


.stat-card {

    background: #111;

    border: 1px solid #333;

    border-radius: 8px;

    padding: 22px;

    transition: 0.3s;

}


.stat-card:hover {

    border-color: #E88F2A;

    transform: translateY(-5px);

    box-shadow:
        0 8px 25px rgba(232,143,42,0.15);

}


.stat-icon {

    font-size: 28px;

    margin-bottom: 12px;

}


.stat-card p {

    color: #777;

    font-size: 12px;

    text-transform: uppercase;

}


.stat-card h2 {

    color: #E88F2A;

    font-size: 28px;

    margin-top: 7px;

}


/* ================= QUICK ACTIONS ================= */

.quick-actions {

    display: grid;

    grid-template-columns: repeat(4, 1fr);

    gap: 15px;

    margin-bottom: 35px;

}


.quick {

    background: #111;

    border: 1px solid #333;

    border-radius: 8px;

    padding: 20px;

    text-align: center;

    text-decoration: none;

    color: #fff;

    transition: 0.3s;

}


.quick:hover {

    border-color: #E88F2A;

    transform: translateY(-4px);

    color: #E88F2A;

}


.quick-icon {

    font-size: 28px;

    margin-bottom: 10px;

}


.quick span {

    font-size: 14px;

    font-weight: bold;

}


/* ================= MANAGEMENT ================= */

.management {

    display: grid;

    grid-template-columns: repeat(3, 1fr);

    gap: 18px;

    margin-bottom: 35px;

}


.manage-card {

    background: #111;

    border: 1px solid #333;

    border-radius: 8px;

    padding: 22px;

    text-decoration: none;

    transition: 0.3s;

}


.manage-card:hover {

    border-color: #E88F2A;

    transform: translateY(-4px);

}


.manage-icon {

    font-size: 30px;

    margin-bottom: 12px;

}


.manage-card h3 {

    color: #fff;

    font-size: 16px;

    margin-bottom: 7px;

}


.manage-card p {

    color: #777;

    font-size: 12px;

    line-height: 1.5;

}


.manage-card:hover h3 {

    color: #E88F2A;

}


/* ================= ORDERS ================= */

.order-box {

    background: #111;

    border: 1px solid #333;

    border-radius: 8px;

    overflow-x: auto;

    margin-bottom: 35px;

}


.order-header {

    padding: 18px 20px;

    border-bottom: 1px solid #333;

    display: flex;

    justify-content: space-between;

}


.order-header h3 {

    font-size: 16px;

}


.view-all {

    color: #E88F2A;

    text-decoration: none;

    font-size: 12px;

}


table {

    width: 100%;

    border-collapse: collapse;

    min-width: 650px;

}


th {

    color: #777;

    font-size: 11px;

    text-align: left;

    padding: 14px 18px;

    border-bottom: 1px solid #222;

}


td {

    color: #bbb;

    font-size: 13px;

    padding: 15px 18px;

    border-bottom: 1px solid #1d1d1d;

}


tr:hover {

    background: #151515;

}


.status {

    padding: 5px 9px;

    border-radius: 20px;

    font-size: 10px;

    font-weight: bold;

}


.pending {

    color: #E88F2A;

    background: rgba(232,143,42,0.12);

}


.completed {

    color: #55cc77;

    background: rgba(85,204,119,0.10);

}


/* ================= FOOTER ================= */

.footer {

    text-align: center;

    padding: 20px;

    border-top: 1px solid #222;

    color: #555;

    font-size: 12px;

}


.footer span {

    color: #E88F2A;

}


/* ================= RESPONSIVE ================= */

@media(max-width:1100px) {

    .stats {

        grid-template-columns: repeat(2,1fr);

    }

    .quick-actions {

        grid-template-columns: repeat(2,1fr);

    }

    .management {

        grid-template-columns: repeat(2,1fr);

    }

}


@media(max-width:750px) {

    .sidebar {

        width: 70px;

    }


    .logo {

        font-size: 0;

    }


    .logo::after {

        content: "S";

        font-size: 28px;

        color: #E88F2A;

    }


    .admin-box h4,
    .admin-box p,
    .menu-title,
    .sidebar a span:not(.icon) {

        display: none;

    }


    .sidebar a {

        justify-content: center;

        padding: 15px 5px;

    }


    .sidebar a:hover,
    .sidebar a.active {

        padding-left: 5px;

    }


    .main {

        margin-left: 70px;

    }


    .admin-name {

        display: none;

    }


    .content {

        padding: 20px;

    }

}


@media(max-width:500px) {

    .stats {

        grid-template-columns: 1fr;

    }


    .quick-actions {

        grid-template-columns: 1fr;

    }


    .management {

        grid-template-columns: 1fr;

    }


    .topbar {

        padding: 0 15px;

    }


    .topbar h2 {

        font-size: 18px;

    }

}

</style>

</head>


<body>


<!-- ==================================================
     SIDEBAR
================================================== -->

<div class="sidebar">


    <div class="logo">
        SWIFFIN
    </div>


    <div class="admin-box">

        <div class="admin-icon">
            👤
        </div>

        <h4>
            <?php echo htmlspecialchars($admin_name); ?>
        </h4>

        <p>
            Administrator
        </p>

    </div>


    <div class="menu-title">
        Main Menu
    </div>


    <a href="admin_dashboard.php" class="active">

        <span class="icon">🏠</span>

        <span>Dashboard</span>

    </a>


    <div class="menu-title">
        Cake Management
    </div>


    <a href="add_cake.php">

        <span class="icon">🍰</span>

        <span>Add Cake</span>

    </a>


    <a href="view_cake.php">

        <span class="icon">👁️</span>

        <span>View Cakes</span>

    </a>


    <a href="category.php">

        <span class="icon">📂</span>

        <span>Categories</span>

    </a>


    <a href="flavor.php">

        <span class="icon">🍓</span>

        <span>Flavors</span>

    </a>


    <div class="menu-title">
        Sales Management
    </div>


    <a href="order.php">

        <span class="icon">📦</span>

        <span>Orders</span>

    </a>


    <a href="customer.php">

        <span class="icon">👥</span>

        <span>Customers</span>

    </a>


    <div class="menu-title">
        Other Management
    </div>


    <a href="stock.php">

        <span class="icon">📊</span>

        <span>Stock / Inventory</span>

    </a>


    <a href="delivery.php">

        <span class="icon">🚚</span>

        <span>Delivery</span>

    </a>


    <a href="view_feedback.php">

        <span class="icon">⭐</span>

        <span>View Feedback</span>

    </a>


    <a href="report.php">

        <span class="icon">📈</span>

        <span>Reports</span>

    </a>


    <a href="offers.php">

        <span class="icon">🎁</span>

        <span>Offers / Coupons</span>

    </a>


    <a href="staff.php">

        <span class="icon">👨‍💼</span>

        <span>Staff</span>

    </a>


    <a href="enquiry.php">

        <span class="icon">📩</span>

        <span>Enquiries</span>

    </a>


    <a href="setting.php">

        <span class="icon">⚙️</span>

        <span>Settings</span>

    </a>


    <a href="logout.php">

        <span class="icon">🚪</span>

        <span>Logout</span>

    </a>


</div>


<!-- ==================================================
     MAIN
================================================== -->

<div class="main">


    <!-- TOPBAR -->

    <div class="topbar">

        <div>

            <h2>
                Admin Dashboard
            </h2>

            <p>
                Swiffin Cake Shop Management System
            </p>

        </div>


        <div>

            <span class="admin-name">

                Welcome,

                <span>
                    <?php
                    echo htmlspecialchars($admin_name);
                    ?>
                </span>

            </span>


            <a href="logout.php"
               class="logout-btn">

                Logout

            </a>

        </div>

    </div>


    <!-- CONTENT -->

    <div class="content">


        <!-- WELCOME -->

        <div class="welcome">

            <h1>
                Welcome Admin 👋
            </h1>

            <p>
                Manage your Swiffin Cake Shop from the admin dashboard.
            </p>

        </div>


        <!-- ================= OVERVIEW ================= -->

        <h2 class="section-title">
            Overview
        </h2>


        <div class="stats">


            <!-- TOTAL CAKES -->

            <div class="stat-card">

                <div class="stat-icon">
                    🍰
                </div>

                <p>
                    Total Cakes
                </p>

                <h2>
                    <?php
                    echo $total_cakes;
                    ?>
                </h2>

            </div>


            <!-- TOTAL CUSTOMERS -->

            <div class="stat-card">

                <div class="stat-icon">
                    👥
                </div>

                <p>
                    Total Customers
                </p>

                <h2>
                    <?php
                    echo $total_customers;
                    ?>
                </h2>

            </div>


            <!-- TOTAL ORDERS -->

            <div class="stat-card">

                <div class="stat-icon">
                    📦
                </div>

                <p>
                    Total Orders
                </p>

                <h2>
                    <?php
                    echo $total_orders;
                    ?>
                </h2>

            </div>


            <!-- TOTAL REVENUE -->

            <div class="stat-card">

                <div class="stat-icon">
                    💰
                </div>

                <p>
                    Total Revenue
                </p>

                <h2>
                    ₹<?php
                    echo number_format(
                        $total_revenue,
                        2
                    );
                    ?>
                </h2>

            </div>


        </div>


        <!-- ================= QUICK ACTIONS ================= -->

        <h2 class="section-title">
            Quick Actions
        </h2>


        <div class="quick-actions">


            <a href="add_cake.php"
               class="quick">

                <div class="quick-icon">
                    🍰
                </div>

                <span>
                    Add New Cake
                </span>

            </a>


            <a href="view_cake.php"
               class="quick">

                <div class="quick-icon">
                    👁️
                </div>

                <span>
                    View Cakes
                </span>

            </a>


            <a href="order.php"
               class="quick">

                <div class="quick-icon">
                    📦
                </div>

                <span>
                    Manage Orders
                </span>

            </a>


            <a href="customer.php"
               class="quick">

                <div class="quick-icon">
                    👥
                </div>

                <span>
                    Customers
                </span>

            </a>


        </div>


        <!-- ================= MANAGEMENT ================= -->

        <h2 class="section-title">
            Management
        </h2>


        <div class="management">


            <a href="category.php"
               class="manage-card">

                <div class="manage-icon">
                    📂
                </div>

                <h3>
                    Categories
                </h3>

                <p>
                    Add, edit and manage cake categories.
                </p>

            </a>


            <a href="flavor.php"
               class="manage-card">

                <div class="manage-icon">
                    🍓
                </div>

                <h3>
                    Flavors
                </h3>

                <p>
                    Add, edit and manage cake flavors.
                </p>

            </a>


            <a href="order.php"
               class="manage-card">

                <div class="manage-icon">
                    📦
                </div>

                <h3>
                    Orders
                </h3>

                <p>
                    Manage customer orders and order status.
                </p>

            </a>


            <a href="customer.php"
               class="manage-card">

                <div class="manage-icon">
                    👥
                </div>

                <h3>
                    Customers
                </h3>

                <p>
                    View and manage registered customers.
                </p>

            </a>


            <a href="stock.php"
               class="manage-card">

                <div class="manage-icon">
                    📊
                </div>

                <h3>
                    Stock / Inventory
                </h3>

                <p>
                    Manage available cake stock.
                </p>

            </a>


            <a href="delivery.php"
               class="manage-card">

                <div class="manage-icon">
                    🚚
                </div>

                <h3>
                    Delivery
                </h3>

                <p>
                    Manage delivery details and status.
                </p>

            </a>


            <a href="view_feedback.php"
               class="manage-card">

                <div class="manage-icon">
                    ⭐
                </div>

                <h3>
                    Feedback
                </h3>

                <p>
                    View customer feedback and reviews.
                </p>

            </a>


            <a href="report.php"
               class="manage-card">

                <div class="manage-icon">
                    📈
                </div>

                <h3>
                    Reports
                </h3>

                <p>
                    View sales and order reports.
                </p>

            </a>


            <a href="offers.php"
               class="manage-card">

                <div class="manage-icon">
                    🎁
                </div>

                <h3>
                    Offers / Coupons
                </h3>

                <p>
                    Manage discounts and special offers.
                </p>

            </a>


            <a href="staff.php"
               class="manage-card">

                <div class="manage-icon">
                    👨‍💼
                </div>

                <h3>
                    Staff Management
                </h3>

                <p>
                    Manage staff and employee information.
                </p>

            </a>


            <a href="enquiry.php"
               class="manage-card">

                <div class="manage-icon">
                    📩
                </div>

                <h3>
                    Enquiries
                </h3>

                <p>
                    View customer enquiries and messages.
                </p>

            </a>


            <a href="setting.php"
               class="manage-card">

                <div class="manage-icon">
                    ⚙️
                </div>

                <h3>
                    Settings
                </h3>

                <p>
                    Manage admin and website settings.
                </p>

            </a>


        </div>


        <!-- ================= RECENT ORDERS ================= -->

        <div class="order-box">


            <div class="order-header">

                <h3>
                    Recent Orders
                </h3>


                <a href="order.php"
                   class="view-all">

                    View All

                </a>

            </div>


            <table>


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
                            Amount
                        </th>

                        <th>
                            Status
                        </th>

                    </tr>

                </thead>


                <tbody>


                <?php

                if (
                    $recent_orders &&
                    mysqli_num_rows($recent_orders) > 0
                ) {

                    while (
                        $order =
                        mysqli_fetch_assoc($recent_orders)
                    ) {

                        $order_id =
                            $order['id'] ?? '';

                        $customer_name =
                            $order['customer_name']
                            ?? $order['name']
                            ?? 'Customer';

                        $cake_name =
                            $order['cake_name']
                            ?? 'Cake';

                        $amount =
                            floatval(
                                $order['amount'] ?? 0
                            );

                        $status =
                            $order['status']
                            ?? 'Pending';


                        $status_lower =
                            strtolower($status);


                        if (
                            $status_lower == 'delivered' ||
                            $status_lower == 'completed'
                        ) {

                            $status_class =
                                'completed';

                        } else {

                            $status_class =
                                'pending';

                        }

                ?>


                    <tr>


                        <td>

                            #<?php

                            echo htmlspecialchars(
                                $order_id
                            );

                            ?>

                        </td>


                        <td>

                            <?php

                            echo htmlspecialchars(
                                $customer_name
                            );

                            ?>

                        </td>


                        <td>

                            <?php

                            echo htmlspecialchars(
                                $cake_name
                            );

                            ?>

                        </td>


                        <td>

                            ₹<?php

                            echo number_format(
                                $amount,
                                2
                            );

                            ?>

                        </td>


                        <td>

                            <span
                                class="status
                                <?php
                                echo $status_class;
                                ?>"
                            >

                                <?php

                                echo htmlspecialchars(
                                    $status
                                );

                                ?>

                            </span>

                        </td>


                    </tr>


                <?php

                    }

                } else {

                ?>


                    <tr>

                        <td
                            colspan="5"
                            style="
                            text-align:center;
                            padding:30px;
                            color:#777;
                            "
                        >

                            No orders found.

                        </td>

                    </tr>


                <?php

                }

                ?>


                </tbody>

            </table>

        </div>


    </div>


    <!-- FOOTER -->

    <div class="footer">

        © 2026

        <span>
            Swiffin Cake Shop
        </span>

        | Admin Panel

    </div>


</div>


</body>

</html>
