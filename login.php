<?php
session_start();
include("config.php");

$error = "";

if (isset($_POST['login'])) {

    $name     = trim($_POST['name'] ?? '');
    $email    = trim($_POST['email'] ?? '');
    $password = trim($_POST['password'] ?? '');

    if ($name == "" || $email == "" || $password == "") {

        $error = "Please enter Name, Email and Password!";

    } else {

        /* =====================================================
           ADMIN LOGIN
        ===================================================== */

        $admin_sql = "SELECT id, name, email, password
                      FROM admin
                      WHERE name = ?
                      AND email = ?
                      AND password = ?
                      LIMIT 1";

        $stmt = mysqli_prepare($conn, $admin_sql);

        if (!$stmt) {

            $error = "Admin Query Error: " . mysqli_error($conn);

        } else {

            mysqli_stmt_bind_param(
                $stmt,
                "sss",
                $name,
                $email,
                $password
            );

            mysqli_stmt_execute($stmt);

            $admin_result = mysqli_stmt_get_result($stmt);

            if ($admin_result && mysqli_num_rows($admin_result) > 0) {

                $admin = mysqli_fetch_assoc($admin_result);

                /* ================= ADMIN SESSION ================= */

                $_SESSION['admin'] = true;
                $_SESSION['admin_id'] = $admin['id'];
                $_SESSION['admin_name'] = $admin['name'];
                $_SESSION['admin_email'] = $admin['email'];

                mysqli_stmt_close($stmt);

                /* ================= ADMIN DASHBOARD ================= */

                header("Location: admin_dashboard.php");
                exit();
            }

            mysqli_stmt_close($stmt);
        }


        /* =====================================================
           CUSTOMER LOGIN
        ===================================================== */

        $customer_sql = "SELECT *
                         FROM reg
                         WHERE name = ?
                         AND email = ?
                         AND password = ?
                         LIMIT 1";

        $stmt = mysqli_prepare($conn, $customer_sql);

        if (!$stmt) {

            $error = "Customer Query Error: " . mysqli_error($conn);

        } else {

            mysqli_stmt_bind_param(
                $stmt,
                "sss",
                $name,
                $email,
                $password
            );

            mysqli_stmt_execute($stmt);

            $customer_result = mysqli_stmt_get_result($stmt);

            if ($customer_result && mysqli_num_rows($customer_result) > 0) {

                $customer = mysqli_fetch_assoc($customer_result);

                /* ================= CUSTOMER SESSION ================= */

                $_SESSION['customer'] = true;
                $_SESSION['customer_name'] = $customer['name'];
                $_SESSION['customer_email'] = $customer['email'];

                mysqli_stmt_close($stmt);

                /* ================= CUSTOMER HOME ================= */

                header("Location: index1.php");
                exit();
            }

            mysqli_stmt_close($stmt);
        }


        /* =====================================================
           INVALID LOGIN
        ===================================================== */

        $error = "Invalid Name, Email or Password!";
    }
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

<title>Login | SWIFFIN Cake Shop</title>


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

    min-height: 100vh;

    background: #000;

    font-family: Arial, Helvetica, sans-serif;

    display: flex;

    justify-content: center;

    align-items: center;

    padding: 20px;

    position: relative;

    overflow: hidden;
}


/* =====================================================
   ORANGE GLOW
===================================================== */

body::before {

    content: "";

    position: fixed;

    width: 500px;
    height: 500px;

    background: #E88F2A;

    border-radius: 50%;

    filter: blur(180px);

    opacity: 0.12;

    top: -250px;
    left: -250px;

    pointer-events: none;
}


body::after {

    content: "";

    position: fixed;

    width: 450px;
    height: 450px;

    background: #E88F2A;

    border-radius: 50%;

    filter: blur(180px);

    opacity: 0.10;

    bottom: -250px;
    right: -220px;

    pointer-events: none;
}


/* =====================================================
   LOGIN BOX
===================================================== */

.login-box {

    width: 400px;

    max-width: 100%;

    background: #111;

    padding: 40px;

    border-radius: 18px;

    border: 1px solid #E88F2A;

    box-shadow:
        0 0 20px rgba(232,143,42,0.30),
        0 0 50px rgba(232,143,42,0.15);

    position: relative;

    z-index: 10;
}


/* =====================================================
   LOGO
===================================================== */

.logo {

    text-align: center;

    color: #E88F2A;

    font-size: 36px;

    font-weight: bold;

    letter-spacing: 3px;

    margin-bottom: 8px;
}


/* =====================================================
   TITLE
===================================================== */

.title {

    text-align: center;

    color: #fff;

    font-size: 23px;

    font-weight: bold;

    margin-bottom: 8px;
}


/* =====================================================
   SUBTITLE
===================================================== */

.subtitle {

    text-align: center;

    color: #888;

    font-size: 13px;

    margin-bottom: 20px;
}


/* =====================================================
   ORANGE LINE
===================================================== */

.orange-line {

    width: 60px;

    height: 3px;

    background: #E88F2A;

    margin: 0 auto 25px;

    border-radius: 10px;
}


/* =====================================================
   ERROR
===================================================== */

.error {

    background: #250000;

    color: #ff7777;

    border: 1px solid #ff3333;

    padding: 12px;

    border-radius: 7px;

    text-align: center;

    margin-bottom: 20px;

    font-size: 13px;
}


/* =====================================================
   FORM GROUP
===================================================== */

.form-group {

    margin-bottom: 18px;
}


/* =====================================================
   LABEL
===================================================== */

.form-group label {

    display: block;

    color: #E88F2A;

    font-size: 14px;

    font-weight: bold;

    margin-bottom: 8px;
}


/* =====================================================
   INPUT
===================================================== */

.form-group input {

    width: 100%;

    height: 48px;

    padding: 0 15px;

    background: #050505;

    color: #fff;

    border: 1px solid #444;

    border-radius: 7px;

    outline: none;

    font-size: 14px;

    transition: 0.3s;
}


.form-group input::placeholder {

    color: #666;
}


.form-group input:focus {

    border-color: #E88F2A;

    box-shadow:
        0 0 8px rgba(232,143,42,0.30);
}


/* =====================================================
   LOGIN BUTTON
===================================================== */

.login-btn {

    width: 100%;

    height: 50px;

    margin-top: 5px;

    background: #E88F2A;

    color: #fff;

    border: none;

    border-radius: 8px;

    font-size: 16px;

    font-weight: bold;

    cursor: pointer;

    transition: 0.3s;
}


.login-btn:hover {

    background: #fff;

    color: #000;

    transform: translateY(-2px);
}


/* =====================================================
   REGISTER
===================================================== */

.register {

    text-align: center;

    margin-top: 22px;

    color: #aaa;

    font-size: 13px;
}


.register a {

    color: #E88F2A;

    text-decoration: none;

    font-weight: bold;

    margin-left: 4px;
}


.register a:hover {

    color: #fff;

    text-decoration: underline;
}


/* =====================================================
   BACK HOME
===================================================== */

.back {

    text-align: center;

    margin-top: 15px;
}


.back a {

    color: #E88F2A;

    text-decoration: none;

    font-size: 13px;
}


.back a:hover {

    color: #fff;

    text-decoration: underline;
}


/* =====================================================
   MOBILE
===================================================== */

@media (max-width: 500px) {

    body {

        padding: 15px;
    }


    .login-box {

        width: 100%;

        padding: 30px 25px;
    }


    .logo {

        font-size: 30px;
    }


    .title {

        font-size: 20px;
    }

}

</style>

</head>


<body>


<div class="login-box">


    <div class="logo">
        SWIFFIN
    </div>


    <div class="title">
        Welcome Back
    </div>


    <div class="subtitle">
        Login to your SWIFFIN account
    </div>


    <div class="orange-line"></div>


    <?php if ($error != "") { ?>

        <div class="error">

            <?php
            echo htmlspecialchars($error);
            ?>

        </div>

    <?php } ?>


    <form
        method="POST"
        action=""
    >


        <!-- NAME -->

        <div class="form-group">

            <label for="name">
                Name
            </label>

            <input
                type="text"
                id="name"
                name="name"
                placeholder="Enter your name"
                required
            >

        </div>


        <!-- EMAIL -->

        <div class="form-group">

            <label for="email">
                Email
            </label>

            <input
                type="email"
                id="email"
                name="email"
                placeholder="Enter your email"
                required
            >

        </div>


        <!-- PASSWORD -->

        <div class="form-group">

            <label for="password">
                Password
            </label>

            <input
                type="password"
                id="password"
                name="password"
                placeholder="Enter your password"
                required
            >

        </div>


        <!-- LOGIN BUTTON -->

        <button
            type="submit"
            name="login"
            class="login-btn"
        >
            Login
        </button>


    </form>


    <div class="register">

        Don't have an account?

        <a href="register.php">
            Register
        </a>

    </div>


    <div class="back">

        <a href="index1.php">
            ← Back to Home
        </a>

    </div>


</div>


</body>

</html>

