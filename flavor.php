<?php
session_start();
include "config.php";

if (!isset($_SESSION['admin']) && !isset($_SESSION['admin_name'])) {
    header("Location: admin.php");
    exit();
}

include("config.php");

$message = "";
$message_type = "";

/* =========================
   ADD FLAVOR
========================= */

if (isset($_POST['add_flavor'])) {

    $flavor_name = trim($_POST['flavor_name'] ?? "");
    $description = trim($_POST['description'] ?? "");
    $status = trim($_POST['status'] ?? "Active");

    if ($flavor_name == "") {

        $message = "Flavor name is required.";
        $message_type = "error";

    } else {

        /* Duplicate Check */

        $check = mysqli_prepare(
            $conn,
            "SELECT id FROM flavor
             WHERE LOWER(TRIM(flavor_name))
             = LOWER(TRIM(?))
             LIMIT 1"
        );

        mysqli_stmt_bind_param($check, "s", $flavor_name);
        mysqli_stmt_execute($check);
        mysqli_stmt_store_result($check);

        if (mysqli_stmt_num_rows($check) > 0) {

            $message = "Flavor already exists.";
            $message_type = "error";

        } else {

            $stmt = mysqli_prepare(
                $conn,
                "INSERT INTO flavor
                (flavor_name, description, status)
                VALUES (?, ?, ?)"
            );

            mysqli_stmt_bind_param(
                $stmt,
                "sss",
                $flavor_name,
                $description,
                $status
            );

            if (mysqli_stmt_execute($stmt)) {

                $message = "Flavor added successfully!";
                $message_type = "success";

            } else {

                $message = "Database Error: " . mysqli_error($conn);
                $message_type = "error";
            }

            mysqli_stmt_close($stmt);
        }

        mysqli_stmt_close($check);
    }
}


/* =========================
   UPDATE FLAVOR
========================= */

if (isset($_POST['update_flavor'])) {

    $id = intval($_POST['id'] ?? 0);

    $flavor_name = trim($_POST['flavor_name'] ?? "");
    $description = trim($_POST['description'] ?? "");
    $status = trim($_POST['status'] ?? "Active");

    if ($flavor_name == "") {

        $message = "Flavor name is required.";
        $message_type = "error";

    } else {

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE flavor
             SET flavor_name = ?,
                 description = ?,
                 status = ?
             WHERE id = ?"
        );

        mysqli_stmt_bind_param(
            $stmt,
            "sssi",
            $flavor_name,
            $description,
            $status,
            $id
        );

        if (mysqli_stmt_execute($stmt)) {

            $message = "Flavor updated successfully!";
            $message_type = "success";

        } else {

            $message = "Database Error: " . mysqli_error($conn);
            $message_type = "error";
        }

        mysqli_stmt_close($stmt);
    }
}


/* =========================
   DELETE FLAVOR
========================= */

if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    $stmt = mysqli_prepare(
        $conn,
        "DELETE FROM flavor WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);

    header("Location: flavor.php");
    exit();
}


/* =========================
   EDIT DATA
========================= */

$edit_data = null;

if (isset($_GET['edit'])) {

    $id = intval($_GET['edit']);

    $stmt = mysqli_prepare(
        $conn,
        "SELECT * FROM flavor WHERE id = ?"
    );

    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $result_edit = mysqli_stmt_get_result($stmt);

    if (mysqli_num_rows($result_edit) > 0) {
        $edit_data = mysqli_fetch_assoc($result_edit);
    }

    mysqli_stmt_close($stmt);
}


/* =========================
   GET ALL FLAVORS
========================= */

$result = mysqli_query(
    $conn,
    "SELECT * FROM flavor ORDER BY id DESC"
);

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Flavor Management | SWIFFIN</title>

<style>

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

/* SIDEBAR */

.sidebar {
    position: fixed;
    left: 0;
    top: 0;
    width: 250px;
    height: 100vh;
    background: #111;
    border-right: 1px solid #333;
    overflow-y: auto;
}

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

.admin-box {
    text-align: center;
    padding: 20px;
    border-bottom: 1px solid #333;
}

.admin-icon {
    width: 48px;
    height: 48px;
    margin: 0 auto 10px;
    border-radius: 50%;
    background: #E88F2A;
    color: #000;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 22px;
}

.admin-box p {
    color: #777;
    font-size: 12px;
    margin-top: 5px;
}

.menu-title {
    color: #666;
    font-size: 11px;
    font-weight: bold;
    padding: 18px 20px 8px;
    text-transform: uppercase;
}

.sidebar a {
    display: flex;
    align-items: center;
    gap: 12px;
    padding: 12px 20px;
    color: #bbb;
    text-decoration: none;
    font-size: 14px;
}

.sidebar a:hover,
.sidebar a.active {
    background: rgba(232,143,42,.12);
    color: #E88F2A;
    border-left: 3px solid #E88F2A;
}

.icon {
    width: 22px;
    text-align: center;
}

/* MAIN */

.main {
    margin-left: 250px;
    min-height: 100vh;
}

.topbar {
    height: 75px;
    background: #111;
    border-bottom: 1px solid #333;
    display: flex;
    align-items: center;
    justify-content: space-between;
    padding: 0 30px;
}

.topbar h2 {
    font-size: 22px;
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

.logout {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    background: #E88F2A;
    color: #fff;
    text-decoration: none;
    padding: 9px 18px;
    border-radius: 5px;
    margin-left: 15px;
    font-size: 13px;
    font-weight: bold;
}

.logout:hover {
    background: #fff;
    color: #000;
}

/* CONTENT */

.content {
    padding: 30px;
}

.page-title {
    margin-bottom: 25px;
}

.page-title h1 {
    color: #E88F2A;
    font-size: 27px;
    margin-bottom: 7px;
}

.page-title p {
    color: #777;
    font-size: 13px;
}

/* MESSAGE */

.message {
    padding: 13px 16px;
    border-radius: 6px;
    margin-bottom: 20px;
    font-size: 14px;
}

.message.success {
    background: #132b18;
    border: 1px solid #287a3e;
    color: #65d77c;
}

.message.error {
    background: #2b1111;
    border: 1px solid #8b3030;
    color: #ff7777;
}

/* FORM */

.form-box {
    background: #111;
    border: 1px solid #333;
    border-radius: 8px;
    padding: 25px;
    margin-bottom: 30px;
}

.form-box h2 {
    font-size: 18px;
    margin-bottom: 20px;
}

.form-row {
    display: grid;
    grid-template-columns: 1fr 1.5fr 180px;
    gap: 15px;
    align-items: end;
}

.form-group label {
    display: block;
    color: #E88F2A;
    font-size: 13px;
    font-weight: bold;
    margin-bottom: 8px;
}

.form-group input,
.form-group select {
    width: 100%;
    height: 44px;
    padding: 10px;
    background: #050505;
    color: #fff;
    border: 1px solid #444;
    border-radius: 5px;
    outline: none;
}

.form-group input:focus,
.form-group select:focus {
    border-color: #E88F2A;
}

/* BUTTON */

.add-btn,
.update-btn {
    background: #E88F2A;
    color: #fff;
    border: none;
    border-radius: 6px;
    font-weight: bold;
    cursor: pointer;
}

.add-btn {
    width: 180px;
    height: 44px;
}

.update-btn {
    width: 90px;
    height: 40px;
}

.add-btn:hover,
.update-btn:hover {
    background: #fff;
    color: #000;
}

.cancel-btn {
    display: inline-flex;
    height: 40px;
    min-width: 80px;
    align-items: center;
    justify-content: center;
    background: #333;
    color: #fff;
    text-decoration: none;
    border-radius: 5px;
    font-size: 13px;
    font-weight: bold;
    margin-left: 5px;
}

/* TABLE */

.table-box {
    background: #111;
    border: 1px solid #333;
    border-radius: 8px;
    overflow-x: auto;
}

.table-header {
    padding: 20px;
    border-bottom: 1px solid #333;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.table-header h2 {
    font-size: 18px;
}

.count {
    color: #E88F2A;
    font-size: 13px;
}

table {
    width: 100%;
    border-collapse: collapse;
    min-width: 900px;
}

th {
    background: #151515;
    color: #E88F2A;
    padding: 15px;
    text-align: left;
    font-size: 12px;
}

td {
    padding: 15px;
    border-top: 1px solid #222;
    color: #bbb;
    font-size: 13px;
}

tr:hover {
    background: #161616;
}

/* STATUS */

.status-active {
    background: rgba(85,204,119,.1);
    color: #55cc77;
    padding: 6px 11px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: bold;
}

.status-inactive {
    background: rgba(220,53,69,.1);
    color: #ff6666;
    padding: 6px 11px;
    border-radius: 20px;
    font-size: 11px;
    font-weight: bold;
}

/* ACTION */

.action-buttons {
    display: flex;
    gap: 8px;
}

.edit-btn,
.delete-btn {
    display: inline-flex;
    align-items: center;
    justify-content: center;
    width: 80px;
    height: 38px;
    border-radius: 5px;
    text-decoration: none;
    font-size: 12px;
    font-weight: bold;
}

.edit-btn {
    background: #E88F2A;
    color: #fff;
}

.delete-btn {
    background: #8b0000;
    color: #fff;
}

.edit-btn:hover {
    background: #fff;
    color: #000;
}

.delete-btn:hover {
    background: #ff3333;
}

/* FOOTER */

.footer {
    text-align: center;
    padding: 20px;
    color: #555;
    border-top: 1px solid #222;
    margin-top: 40px;
    font-size: 12px;
}

.footer span {
    color: #E88F2A;
}

@media(max-width:1000px) {

    .form-row {
        grid-template-columns: 1fr;
    }

    .add-btn {
        width: 100%;
    }
}

</style>

</head>

<body>


<!-- SIDEBAR -->

<div class="sidebar">

    <div class="logo">
        SWIFFIN
    </div>

    <div class="admin-box">

        <div class="admin-icon">
            👤
        </div>

        <h4>
            <?php
            echo htmlspecialchars(
                $_SESSION['admin_name']
                ?? $_SESSION['admin']
                ?? 'Admin'
            );
            ?>
        </h4>

        <p>Administrator</p>

    </div>


    <div class="menu-title">
        Main Menu
    </div>

    <a href="admin_dashboard.php">
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

    <a href="edit_cake.php">
        <span class="icon">📝</span>
        <span>Edit Cake</span>
    </a>

    <a href="delete_cake.php">
        <span class="icon">🗑️</span>
        <span>Delete Cake</span>
    </a>

    <a href="category.php">
        <span class="icon">📂</span>
        <span>Categories</span>
    </a>

    <a href="flavor.php" class="active">
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

    <a href="carts.php">
        <span class="icon">🛒</span>
        <span>Carts</span>
    </a>

    <a href="payment.php">
        <span class="icon">💳</span>
        <span>Payments</span>
    </a>


    <div class="menu-title">
        Other Management
    </div>

    <a href="stock.php">
        <span class="icon">📊</span>
        <span>Stock</span>
    </a>

    <a href="delivery.php">
        <span class="icon">🚚</span>
        <span>Delivery</span>
    </a>

    <a href="feedback.php">
        <span class="icon">⭐</span>
        <span>Feedback</span>
    </a>

    <a href="reports.php">
        <span class="icon">📈</span>
        <span>Reports</span>
    </a>

    <a href="offers.php">
        <span class="icon">🎁</span>
        <span>Offers</span>
    </a>

    <a href="staff.php">
        <span class="icon">👨‍💼</span>
        <span>Staff</span>
    </a>

    <a href="enquiry.php">
        <span class="icon">📩</span>
        <span>Enquiries</span>
    </a>

    <a href="settings.php">
        <span class="icon">⚙️</span>
        <span>Settings</span>
    </a>

    <a href="logout.php">
        <span class="icon">🚪</span>
        <span>Logout</span>
    </a>

</div>


<!-- MAIN -->

<div class="main">


    <!-- TOPBAR -->

    <div class="topbar">

        <div>

            <h2>
                Flavor Management
            </h2>

            <p>
                Manage Swiffin Cake Shop Flavors
            </p>

        </div>


        <div>

            <span class="admin-name">

                Welcome,

                <span>
                    <?php
                    echo htmlspecialchars(
                        $_SESSION['admin_name']
                        ?? $_SESSION['admin']
                        ?? 'Admin'
                    );
                    ?>
                </span>

            </span>


            <a href="logout.php" class="logout">
                Logout
            </a>

        </div>

    </div>


    <!-- CONTENT -->

    <div class="content">


        <div class="page-title">

            <h1>
                🍓 Flavor Management
            </h1>

            <p>
                Add, view, edit and delete cake flavors.
            </p>

        </div>


        <!-- MESSAGE -->

        <?php if ($message != "") { ?>

            <div class="message <?php echo $message_type; ?>">

                <?php
                echo htmlspecialchars($message);
                ?>

            </div>

        <?php } ?>


        <!-- FORM -->

        <div class="form-box">


            <?php if ($edit_data) { ?>


                <h2>
                    ✏️ Edit Flavor
                </h2>


                <form method="POST">


                    <input
                        type="hidden"
                        name="id"
                        value="<?php
                        echo $edit_data['id'];
                        ?>"
                    >


                    <div class="form-row">


                        <div class="form-group">

                            <label>
                                Flavor Name
                            </label>

                            <input
                                type="text"
                                name="flavor_name"
                                value="<?php
                                echo htmlspecialchars(
                                    $edit_data['flavor_name']
                                );
                                ?>"
                                required
                            >

                        </div>


                        <div class="form-group">

                            <label>
                                Description
                            </label>

                            <input
                                type="text"
                                name="description"
                                value="<?php
                                echo htmlspecialchars(
                                    $edit_data['description'] ?? ''
                                );
                                ?>"
                                placeholder="Enter description"
                            >

                        </div>


                        <div class="form-group">

                            <label>
                                Status
                            </label>

                            <select name="status">

                                <option
                                    value="Active"
                                    <?php
                                    echo
                                    (($edit_data['status'] ?? '')
                                    == 'Active')
                                    ? 'selected'
                                    : '';
                                    ?>
                                >
                                    Active
                                </option>

                                <option
                                    value="Inactive"
                                    <?php
                                    echo
                                    (($edit_data['status'] ?? '')
                                    == 'Inactive')
                                    ? 'selected'
                                    : '';
                                    ?>
                                >
                                    Inactive
                                </option>

                            </select>

                        </div>

                    </div>


                    <div style="margin-top:20px;">

                        <button
                            type="submit"
                            name="update_flavor"
                            class="update-btn"
                        >
                            Update
                        </button>

                        <a
                            href="flavor.php"
                            class="cancel-btn"
                        >
                            Cancel
                        </a>

                    </div>


                </form>


            <?php } else { ?>


                <h2>
                    ➕ Add New Flavor
                </h2>


                <form method="POST">


                    <div class="form-row">


                        <div class="form-group">

                            <label>
                                Flavor Name
                            </label>

                            <input
                                type="text"
                                name="flavor_name"
                                placeholder="Example: Chocolate"
                                required
                            >

                        </div>


                        <div class="form-group">

                            <label>
                                Description
                            </label>

                            <input
                                type="text"
                                name="description"
                                placeholder="Example: Rich chocolate flavor"
                            >

                        </div>


                        <div class="form-group">

                            <label>
                                Status
                            </label>

                            <select name="status">

                                <option value="Active">
                                    Active
                                </option>

                                <option value="Inactive">
                                    Inactive
                                </option>

                            </select>

                        </div>

                    </div>


                    <div style="margin-top:20px;">

                        <button
                            type="submit"
                            name="add_flavor"
                            class="add-btn"
                        >
                            + Add Flavor
                        </button>

                    </div>


                </form>


            <?php } ?>

        </div>


        <!-- ALL FLAVORS -->

        <div class="table-box">


            <div class="table-header">

                <h2>
                    👁️ All Flavors
                </h2>

                <div class="count">

                    <?php
                    echo mysqli_num_rows($result);
                    ?>

                    Flavors

                </div>

            </div>


            <table>

                <thead>

                    <tr>

                        <th>ID</th>
                        <th>Flavor Name</th>
                        <th>Description</th>
                        <th>Status</th>
                        <th>Created Date</th>
                        <th>Action</th>

                    </tr>

                </thead>


                <tbody>


                <?php

                if (mysqli_num_rows($result) > 0) {

                    while ($row = mysqli_fetch_assoc($result)) {

                ?>

                    <tr>

                        <td>
                            <?php echo $row['id']; ?>
                        </td>


                        <td>

                            <?php
                            echo htmlspecialchars(
                                $row['flavor_name']
                            );
                            ?>

                        </td>


                        <td>

                            <?php
                            echo htmlspecialchars(
                                $row['description'] ?? ''
                            );
                            ?>

                        </td>


                        <td>

                            <?php
                            if (
                                ($row['status'] ?? '')
                                == 'Active'
                            ) {
                            ?>

                                <span class="status-active">
                                    Active
                                </span>

                            <?php
                            } else {
                            ?>

                                <span class="status-inactive">
                                    Inactive
                                </span>

                            <?php
                            }
                            ?>

                        </td>


                        <td>

                            <?php

                            if (!empty($row['created_at'])) {

                                echo date(
                                    "d-m-Y",
                                    strtotime($row['created_at'])
                                );

                            } else {

                                echo "-";

                            }

                            ?>

                        </td>


                        <td>

                            <div class="action-buttons">


                                <a
                                    href="flavor.php?edit=<?php
                                    echo $row['id'];
                                    ?>"
                                    class="edit-btn"
                                >
                                    ✏️ Edit
                                </a>


                                <a
                                    href="flavor.php?delete=<?php
                                    echo $row['id'];
                                    ?>"
                                    class="delete-btn"
                                    onclick="
                                    return confirm(
                                    'Are you sure you want to delete this flavor?'
                                    );
                                    "
                                >
                                    🗑️ Delete
                                </a>


                            </div>

                        </td>

                    </tr>


                <?php

                    }

                } else {

                ?>

                    <tr>

                        <td
                            colspan="6"
                            style="
                            text-align:center;
                            padding:40px;
                            color:#777;
                            "
                        >

                            No Flavors Found

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
            SWIFFIN Cake Shop
        </span>

        | Flavor Management

    </div>


</div>


</body>

</html>

<?php
mysqli_close($conn);
?>
