<?php
session_start();

include "config.php";

$error = "";

if (isset($_POST['login'])) {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);


    /* ================= ADMIN LOGIN ================= */

    if (
        $name == "admin" &&
        $email == "admin@gmail.com" &&
        $password == "admin"
    ) {

        $_SESSION['name'] = $name;
        $_SESSION['admin'] = $email;
        $_SESSION['admin_name'] = $name;

        header("Location: admin_dashboard.php");
        exit();
    }


    /* ================= CUSTOMER LOGIN ================= */

    else {

        $query = mysqli_query(
            $conn,
            "SELECT * FROM customer
             WHERE name='$name'
             AND email='$email'
             AND password='$password'"
        );


        if (mysqli_num_rows($query) > 0) {

            $customer = mysqli_fetch_assoc($query);

            $_SESSION['name'] = $customer['name'];
            $_SESSION['customer_id'] = $customer['id'];
            $_SESSION['email'] = $customer['email'];

            header("Location: index1.php");
            exit();

        } else {

            $error = "Invalid Name, Email or Password!";
        }
    }
}
?>


<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Login | Swiffin Cake Shop</title>


<style>

* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}


/* ================= BODY ================= */

body {

    font-family: Arial, sans-serif;

    background: #000;

    min-height: 100vh;

    display: flex;

    justify-content: center;

    align-items: center;

    position: relative;

    overflow: hidden;
}


/* Orange Glow */

body::before {

    content: "";

    position: absolute;

    width: 500px;

    height: 500px;

    background: #E88F2A;

    border-radius: 50%;

    filter: blur(180px);

    opacity: 0.12;

    top: -250px;

    left: -200px;
}


body::after {

    content: "";

    position: absolute;

    width: 450px;

    height: 450px;

    background: #E88F2A;

    border-radius: 50%;

    filter: blur(180px);

    opacity: 0.10;

    bottom: -250px;

    right: -200px;
}


/* ================= LOGIN BOX ================= */

.login-box {

    position: relative;

    z-index: 2;

    width: 400px;

    background: #111;

    padding: 42px;

    border-radius: 18px;

    border: 1px solid #E88F2A;

    box-shadow:

        0 0 15px rgba(232,143,42,0.35),

        0 0 40px rgba(232,143,42,0.18);
}


/* ================= LOGO ================= */

.logo {

    text-align: center;

    color: #E88F2A;

    font-size: 36px;

    font-weight: bold;

    letter-spacing: 3px;

    margin-bottom: 8px;
}


/* ================= TITLE ================= */

.title {

    text-align: center;

    color: #fff;

    font-size: 23px;

    font-weight: bold;

    margin-bottom: 25px;
}


/* ================= LINE ================= */

.orange-line {

    width: 60px;

    height: 3px;

    background: #E88F2A;

    margin: -15px auto 28px;

    border-radius: 5px;
}


/* ================= ERROR ================= */

.error {

    background: #250000;

    color: #ff6666;

    border: 1px solid #ff3333;

    padding: 12px;

    border-radius: 7px;

    text-align: center;

    margin-bottom: 20px;

    font-size: 14px;
}


/* ================= LABEL ================= */

label {

    display: block;

    color: #E88F2A;

    font-size: 14px;

    font-weight: bold;

    margin-bottom: 8px;
}


/* ================= INPUT ================= */

input[type="text"],
input[type="email"],
input[type="password"] {

    width: 100%;

    height: 48px;

    padding: 0 15px;

    background: #050505;

    color: #fff;

    border: 1px solid #444;

    border-radius: 7px;

    outline: none;

    font-size: 15px;

    margin-bottom: 20px;

    transition: 0.3s;
}


input::placeholder {

    color: #777;
}


input[type="text"]:focus,
input[type="email"]:focus,
input[type="password"]:focus {

    border-color: #E88F2A;

    box-shadow: 0 0 8px rgba(232,143,42,0.35);
}


/* ================= BUTTON ================= */

input[type="submit"] {

    width: 100%;

    height: 50px;

    background: #E88F2A;

    color: #fff;

    border: none;

    border-radius: 7px;

    font-size: 17px;

    font-weight: bold;

    cursor: pointer;

    transition: 0.3s;
}


input[type="submit"]:hover {

    background: #fff;

    color: #000;

    transform: translateY(-2px);

    box-shadow: 0 5px 20px rgba(232,143,42,0.35);
}


/* ================= REGISTER ================= */

.register {

    text-align: center;

    margin-top: 20px;

    color: #ccc;

    font-size: 14px;
}


.register a {

    color: #E88F2A;

    text-decoration: none;

    font-weight: bold;
}


.register a:hover {

    color: #fff;

    text-decoration: underline;
}


/* ================= BACK ================= */

.back {

    text-align: center;

    margin-top: 15px;
}


.back a {

    color: #E88F2A;

    text-decoration: none;

    font-size: 14px;
}


.back a:hover {

    color: #fff;

    text-decoration: underline;
}


/* ================= MOBILE ================= */

@media (max-width: 500px) {

    .login-box {

        width: 90%;

        padding: 32px 25px;
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
        Login
    </div>


    <div class="orange-line"></div>


    <?php if ($error != "") { ?>

        <div class="error">
            <?php echo htmlspecialchars($error); ?>
        </div>

    <?php } ?>


    <form method="POST" action="">


        <!-- NAME -->

        <label>Name</label>

        <input
            type="text"
            name="name"
            placeholder="Enter Name"
            required
        >


        <!-- EMAIL -->

        <label>Email</label>

        <input
            type="email"
            name="email"
            placeholder="Enter Email"
            required
        >


        <!-- PASSWORD -->

        <label>Password</label>

        <input
            type="password"
            name="password"
            placeholder="Enter Password"
            required
        >


        <!-- LOGIN BUTTON -->

        <input
            type="submit"
            name="login"
            value="Login"
        >

    </form>


    <!-- REGISTER -->

    <div class="register">

        Don't have an account?

        <a href="register.php">
            Register
        </a>

    </div>


    <!-- BACK -->

    <div class="back">

        <a href="index1.php">
            ← Back to Home
        </a>

    </div>


</div>

</body>

</html>
