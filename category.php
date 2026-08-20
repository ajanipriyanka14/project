<?php

session_start();

/* =====================================================
   ADMIN LOGIN CHECK
===================================================== */

/*
   Admin login ma session nu naam
   admin athva admin_name hoy to banne accept karishu.
*/

if (
    !isset($_SESSION['admin']) &&
    !isset($_SESSION['admin_name'])
) {
    header("Location: admin.php");
    exit();
}


/* =====================================================
   DATABASE CONNECTION
===================================================== */

include("config.php");


/* =====================================================
   VARIABLES
===================================================== */

$message = "";
$message_type = "";

$image_folder = "img/category/";


/* =====================================================
   CREATE IMAGE FOLDER
===================================================== */

if (!is_dir($image_folder)) {

    mkdir($image_folder, 0777, true);

}


/* =====================================================
   ADD CATEGORY
===================================================== */

if (isset($_POST['add_category'])) {

    $category_name = trim($_POST['category_name'] ?? "");
    $description   = trim($_POST['description'] ?? "");
    $status        = trim($_POST['status'] ?? "Active");

    $image_name = "";


    /* CATEGORY NAME CHECK */

    if ($category_name == "") {

        $message = "Category name is required.";
        $message_type = "error";

    } else {


        /* =================================================
           IMAGE UPLOAD
        ================================================= */

        if (
            isset($_FILES['image']) &&
            $_FILES['image']['error'] == 0
        ) {

            $allowed = array(
                "jpg",
                "jpeg",
                "png",
                "webp"
            );


            $extension = strtolower(
                pathinfo(
                    $_FILES['image']['name'],
                    PATHINFO_EXTENSION
                )
            );


            if (in_array($extension, $allowed)) {

                $image_name =
                    time() .
                    "_" .
                    uniqid() .
                    "." .
                    $extension;


                if (!move_uploaded_file(
                    $_FILES['image']['tmp_name'],
                    $image_folder . $image_name
                )) {

                    $message = "Image upload failed.";
                    $message_type = "error";
                }

            } else {

                $message =
                    "Only JPG, JPEG, PNG and WEBP images are allowed.";

                $message_type = "error";
            }
        }


        /* =================================================
           INSERT CATEGORY
        ================================================= */

        if ($message == "") {

            $stmt = mysqli_prepare(
                $conn,
                "INSERT INTO category
                (category_name, description, image, status)
                VALUES (?, ?, ?, ?)"
            );


            mysqli_stmt_bind_param(
                $stmt,
                "ssss",
                $category_name,
                $description,
                $image_name,
                $status
            );


            if (mysqli_stmt_execute($stmt)) {

                $message =
                    "Category added successfully!";

                $message_type = "success";

            } else {

                $message =
                    "Database Error: " .
                    mysqli_error($conn);

                $message_type = "error";
            }


            mysqli_stmt_close($stmt);
        }
    }
}


/* =====================================================
   UPDATE CATEGORY
===================================================== */

if (isset($_POST['update_category'])) {

    $id = intval($_POST['id'] ?? 0);

    $category_name =
        trim($_POST['category_name'] ?? "");

    $description =
        trim($_POST['description'] ?? "");

    $status =
        trim($_POST['status'] ?? "Active");

    $old_image =
        $_POST['old_image'] ?? "";

    $image_name = $old_image;


    /* =================================================
       NEW IMAGE
    ================================================= */

    if (
        isset($_FILES['image']) &&
        $_FILES['image']['error'] == 0
    ) {

        $allowed = array(
            "jpg",
            "jpeg",
            "png",
            "webp"
        );


        $extension = strtolower(
            pathinfo(
                $_FILES['image']['name'],
                PATHINFO_EXTENSION
            )
        );


        if (in_array($extension, $allowed)) {

            $new_image =
                time() .
                "_" .
                uniqid() .
                "." .
                $extension;


            if (move_uploaded_file(
                $_FILES['image']['tmp_name'],
                $image_folder . $new_image
            )) {

                $image_name = $new_image;


                /* DELETE OLD IMAGE */

                if (
                    $old_image != "" &&
                    file_exists(
                        $image_folder . $old_image
                    )
                ) {

                    unlink(
                        $image_folder . $old_image
                    );
                }
            }

        } else {

            $message =
                "Only JPG, JPEG, PNG and WEBP images are allowed.";

            $message_type = "error";
        }
    }


    /* =================================================
       UPDATE DATABASE
    ================================================= */

    if ($message == "") {

        $stmt = mysqli_prepare(
            $conn,
            "UPDATE category
             SET
             category_name = ?,
             description = ?,
             image = ?,
             status = ?
             WHERE id = ?"
        );


        mysqli_stmt_bind_param(
            $stmt,
            "ssssi",
            $category_name,
            $description,
            $image_name,
            $status,
            $id
        );


        if (mysqli_stmt_execute($stmt)) {

            $message =
                "Category updated successfully!";

            $message_type = "success";

        } else {

            $message =
                "Database Error: " .
                mysqli_error($conn);

            $message_type = "error";
        }


        mysqli_stmt_close($stmt);
    }
}


/* =====================================================
   DELETE CATEGORY
===================================================== */

if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);


    /* GET IMAGE */

    $stmt = mysqli_prepare(
        $conn,
        "SELECT image
         FROM category
         WHERE id = ?"
    );


    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $id
    );


    mysqli_stmt_execute($stmt);


    $delete_result =
        mysqli_stmt_get_result($stmt);


    $delete_data =
        mysqli_fetch_assoc($delete_result);


    mysqli_stmt_close($stmt);


    /* DELETE IMAGE */

    if (
        $delete_data &&
        !empty($delete_data['image']) &&
        file_exists(
            $image_folder .
            $delete_data['image']
        )
    ) {

        unlink(
            $image_folder .
            $delete_data['image']
        );
    }


    /* DELETE CATEGORY */

    $stmt = mysqli_prepare(
        $conn,
        "DELETE FROM category
         WHERE id = ?"
    );


    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $id
    );


    mysqli_stmt_execute($stmt);

    mysqli_stmt_close($stmt);


    header("Location: category.php");

    exit();
}


/* =====================================================
   EDIT CATEGORY DATA
===================================================== */

$edit_data = null;


if (isset($_GET['edit'])) {

    $id = intval($_GET['edit']);


    $stmt = mysqli_prepare(
        $conn,
        "SELECT *
         FROM category
         WHERE id = ?"
    );


    mysqli_stmt_bind_param(
        $stmt,
        "i",
        $id
    );


    mysqli_stmt_execute($stmt);


    $edit_result =
        mysqli_stmt_get_result($stmt);


    if (mysqli_num_rows($edit_result) > 0) {

        $edit_data =
            mysqli_fetch_assoc($edit_result);
    }


    mysqli_stmt_close($stmt);
}


/* =====================================================
   GET ALL CATEGORIES
===================================================== */

$result = mysqli_query(
    $conn,
    "SELECT *
     FROM category
     ORDER BY id DESC"
);

?>

<!DOCTYPE html>

<html lang="en">

<head>

<meta charset="UTF-8">

<meta
name="viewport"
content="width=device-width, initial-scale=1.0">

<title>Category Management | SWIFFIN</title>


<style>

/* =====================================================
   RESET
===================================================== */

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}


/* =====================================================
   BODY
===================================================== */

body {

    font-family: Arial, sans-serif;

    background: #000;

    color: #fff;

}


/* =====================================================
   SIDEBAR
===================================================== */

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


.admin-box h4 {

    margin-bottom: 5px;

}


.admin-box p {

    color: #777;

    font-size: 12px;

}


/* =====================================================
   MENU
===================================================== */

.menu-title {

    color: #666;

    font-size: 11px;

    font-weight: bold;

    padding: 18px 20px 8px;

    text-transform: uppercase;

    letter-spacing: 1px;

}


.sidebar a {

    display: flex;

    align-items: center;

    gap: 12px;

    padding: 12px 20px;

    color: #bbb;

    text-decoration: none;

    font-size: 14px;

    transition: .3s;

}


.sidebar a:hover {

    background: #1b1b1b;

    color: #E88F2A;

    border-left: 3px solid #E88F2A;

}


.sidebar a.active {

    background: rgba(232,143,42,.12);

    color: #E88F2A;

    border-left: 3px solid #E88F2A;

}


.icon {

    width: 22px;

    min-width: 22px;

    text-align: center;

}


/* =====================================================
   MAIN
===================================================== */

.main {

    margin-left: 250px;

    min-height: 100vh;

}


/* =====================================================
   TOPBAR
===================================================== */

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


/* =====================================================
   CONTENT
===================================================== */

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


/* =====================================================
   MESSAGE
===================================================== */

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


/* =====================================================
   FORM BOX
===================================================== */

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

    color: #fff;

}


/* =====================================================
   FORM
===================================================== */

.form-row {

    display: grid;

    grid-template-columns:
        1fr
        1.5fr
        180px
        180px;

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

    box-shadow:
        0 0 7px rgba(232,143,42,.2);

}


.form-group input::placeholder {

    color: #666;

}


/* =====================================================
   FILE INPUT
===================================================== */

.form-group input[type="file"] {

    padding: 8px;

    cursor: pointer;

}


/* =====================================================
   BUTTONS
===================================================== */

.add-btn {

    width: 180px;

    height: 44px;

    background: #E88F2A;

    color: #fff;

    border: none;

    border-radius: 6px;

    font-weight: bold;

    cursor: pointer;

}


.add-btn:hover {

    background: #fff;

    color: #000;

}


.update-btn {

    width: 90px;

    height: 40px;

    background: #E88F2A;

    color: #fff;

    border: none;

    border-radius: 5px;

    font-weight: bold;

    cursor: pointer;

}


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


.cancel-btn:hover {

    background: #555;

}


/* =====================================================
   CURRENT IMAGE
===================================================== */

.current-image {

    margin-top: 10px;

}


.current-image img {

    width: 65px;

    height: 65px;

    object-fit: cover;

    border-radius: 7px;

    border: 1px solid #444;

}


/* =====================================================
   TABLE
===================================================== */

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

    min-width: 1000px;

}


th {

    background: #151515;

    color: #E88F2A;

    padding: 15px;

    text-align: left;

    font-size: 12px;

    white-space: nowrap;

}


td {

    padding: 15px;

    border-top: 1px solid #222;

    color: #bbb;

    font-size: 13px;

    vertical-align: middle;

}


tr:hover {

    background: #161616;

}


/* =====================================================
   IMAGE
===================================================== */

.category-image {

    width: 70px;

    height: 70px;

    object-fit: cover;

    border-radius: 8px;

    border: 1px solid #444;

}


.category-image:hover {

    border-color: #E88F2A;

}


/* =====================================================
   STATUS
===================================================== */

.status-active {

    display: inline-block;

    background: rgba(85,204,119,.1);

    color: #55cc77;

    padding: 6px 11px;

    border-radius: 20px;

    font-size: 11px;

    font-weight: bold;

}


.status-inactive {

    display: inline-block;

    background: rgba(220,53,69,.1);

    color: #ff6666;

    padding: 6px 11px;

    border-radius: 20px;

    font-size: 11px;

    font-weight: bold;

}


/* =====================================================
   ACTION
===================================================== */

.action-buttons {

    display: flex;

    gap: 8px;

    white-space: nowrap;

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


.edit-btn:hover {

    background: #fff;

    color: #000;

}


.delete-btn {

    background: #8b0000;

    color: #fff;

}


.delete-btn:hover {

    background: #ff3333;

    color: #fff;

}


/* =====================================================
   FOOTER
===================================================== */

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


/* =====================================================
   RESPONSIVE
===================================================== */

@media(max-width:1200px) {

    .form-row {

        grid-template-columns: 1fr 1fr;

    }

}


@media(max-width:800px) {

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

        padding: 13px 5px;

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


    .topbar {

        padding: 0 20px;

    }

}


@media(max-width:550px) {

    .form-row {

        grid-template-columns: 1fr;

    }


    .add-btn {

        width: 100%;

    }


    .topbar h2 {

        font-size: 18px;

    }


    .page-title h1 {

        font-size: 22px;

    }

}

</style>

</head>


<body>


<!-- =====================================================
     SIDEBAR
===================================================== -->

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


        <p>
            Administrator
        </p>

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


    <!-- CATEGORY ACTIVE -->

    <a
        href="category.php"
        class="active"
    >

        <span class="icon">📂</span>

        <span>Categories</span>

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



<!-- =====================================================
     MAIN
===================================================== -->

<div class="main">


    <!-- TOPBAR -->

    <div class="topbar">


        <div>

            <h2>
                Category Management
            </h2>

            <p>
                Manage Swiffin Cake Shop Categories
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


            <a
                href="logout.php"
                class="logout"
            >
                Logout
            </a>

        </div>


    </div>



    <!-- CONTENT -->

    <div class="content">


        <div class="page-title">

            <h1>
                📂 Category Management
            </h1>

            <p>
                Add, view, edit and delete cake categories.
            </p>

        </div>



        <!-- MESSAGE -->

        <?php if ($message != "") { ?>

            <div
                class="message <?php echo $message_type; ?>"
            >

                <?php

                echo htmlspecialchars($message);

                ?>

            </div>

        <?php } ?>



        <!-- =================================================
             ADD / EDIT
        ================================================= -->

        <div class="form-box">


            <?php if ($edit_data) { ?>


                <!-- EDIT -->

                <h2>
                    ✏️ Edit Category
                </h2>


                <form
                    method="POST"
                    enctype="multipart/form-data"
                >


                    <input
                        type="hidden"
                        name="id"
                        value="<?php
                        echo $edit_data['id'];
                        ?>"
                    >


                    <input
                        type="hidden"
                        name="old_image"
                        value="<?php
                        echo htmlspecialchars(
                            $edit_data['image'] ?? ''
                        );
                        ?>"
                    >


                    <div class="form-row">


                        <div class="form-group">

                            <label>
                                Category Name
                            </label>

                            <input
                                type="text"
                                name="category_name"
                                value="<?php
                                echo htmlspecialchars(
                                    $edit_data['category_name']
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
                                    $edit_data['description']
                                );
                                ?>"
                            >

                        </div>


                        <div class="form-group">

                            <label>
                                Category Image
                            </label>

                            <input
                                type="file"
                                name="image"
                                accept=".jpg,.jpeg,.png,.webp"
                            >


                            <?php

                            if (
                                !empty(
                                    $edit_data['image']
                                )
                            ) {

                            ?>

                                <div class="current-image">

                                    <img
                                        src="img/category/<?php
                                        echo htmlspecialchars(
                                            $edit_data['image']
                                        );
                                        ?>"
                                        alt="Category"
                                    >

                                </div>

                            <?php } ?>

                        </div>


                        <div class="form-group">

                            <label>
                                Status
                            </label>

                            <select name="status">

                                <option
                                    value="Active"
                                    <?php
                                    if (
                                        ($edit_data['status']
                                        ?? '') ==
                                        "Active"
                                    ) {
                                        echo "selected";
                                    }
                                    ?>
                                >
                                    Active
                                </option>


                                <option
                                    value="Inactive"
                                    <?php
                                    if (
                                        ($edit_data['status']
                                        ?? '') ==
                                        "Inactive"
                                    ) {
                                        echo "selected";
                                    }
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
                            name="update_category"
                            class="update-btn"
                        >
                            Update
                        </button>


                        <a
                            href="category.php"
                            class="cancel-btn"
                        >
                            Cancel
                        </a>

                    </div>


                </form>


            <?php } else { ?>


                <!-- ADD -->

                <h2>
                    ➕ Add New Category
                </h2>


                <form
                    method="POST"
                    enctype="multipart/form-data"
                >


                    <div class="form-row">


                        <div class="form-group">

                            <label>
                                Category Name
                            </label>

                            <input
                                type="text"
                                name="category_name"
                                placeholder="Example: Birthday Cakes"
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
                                placeholder="Enter description"
                            >

                        </div>


                        <div class="form-group">

                            <label>
                                Category Image
                            </label>

                            <input
                                type="file"
                                name="image"
                                accept=".jpg,.jpeg,.png,.webp"
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
                            name="add_category"
                            class="add-btn"
                        >
                            + Add Category
                        </button>

                    </div>


                </form>


            <?php } ?>


        </div>



        <!-- =================================================
             ALL CATEGORIES
        ================================================= -->

        <div class="table-box">


            <div class="table-header">

                <h2>
                    👁️ All Categories
                </h2>


                <div class="count">

                    <?php

                    echo mysqli_num_rows($result);

                    ?>

                    Categories

                </div>

            </div>



            <table>


                <thead>

                    <tr>

                        <th>ID</th>

                        <th>Category Name</th>

                        <th>Description</th>

                        <th>Image</th>

                        <th>Status</th>

                        <th>Created Date</th>

                        <th>Action</th>

                    </tr>

                </thead>


                <tbody>


                <?php

                if (
                    mysqli_num_rows($result) > 0
                ) {

                    while (
                        $row =
                        mysqli_fetch_assoc($result)
                    ) {

                ?>


                    <tr>


                        <td>

                            <?php
                            echo $row['id'];
                            ?>

                        </td>


                        <td>

                            <?php

                            echo htmlspecialchars(
                                $row['category_name']
                            );

                            ?>

                        </td>


                        <td>

                            <?php

                            echo htmlspecialchars(
                                $row['description']
                                ?? ''
                            );

                            ?>

                        </td>


                        <td>


                            <?php

                            if (
                                !empty(
                                    $row['image']
                                )
                            ) {

                            ?>

                                <img
                                    src="img/category/<?php
                                    echo htmlspecialchars(
                                        $row['image']
                                    );
                                    ?>"
                                    class="category-image"
                                    alt="Category"
                                >

                            <?php

                            } else {

                            ?>

                                <span
                                    style="color:#666;"
                                >
                                    No Image
                                </span>

                            <?php

                            }

                            ?>

                        </td>


                        <td>


                            <?php

                            if (
                                ($row['status']
                                ?? '') ==
                                "Active"
                            ) {

                            ?>

                                <span
                                    class="status-active"
                                >
                                    Active
                                </span>

                            <?php

                            } else {

                            ?>

                                <span
                                    class="status-inactive"
                                >
                                    Inactive
                                </span>

                            <?php

                            }

                            ?>

                        </td>


                        <td>

                            <?php

                            if (
                                !empty(
                                    $row['created_at']
                                )
                            ) {

                                echo date(
                                    "d-m-Y",
                                    strtotime(
                                        $row['created_at']
                                    )
                                );

                            } else {

                                echo "-";

                            }

                            ?>

                        </td>


                        <td>

                            <div
                                class="action-buttons"
                            >


                                <a
                                    href="category.php?edit=<?php
                                    echo $row['id'];
                                    ?>"
                                    class="edit-btn"
                                >
                                    ✏️ Edit
                                </a>


                                <a
                                    href="category.php?delete=<?php
                                    echo $row['id'];
                                    ?>"
                                    class="delete-btn"
                                    onclick="
                                    return confirm(
                                    'Are you sure you want to delete this category?'
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
                            colspan="7"
                            style="
                            text-align:center;
                            padding:40px;
                            color:#777;
                            "
                        >

                            No Categories Found

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

        | Category Management

    </div>


</div>


</body>

</html>

<?php

mysqli_close($conn);

?>
