
<?php

session_start();
include "config.php";

$error = "";

/* =====================================================
   REGISTER
===================================================== */

if (isset($_POST['register'])) {

    $name     = trim($_POST['name'] ?? "");
    $email    = trim($_POST['email'] ?? "");
    $password = trim($_POST['password'] ?? "");
    $mobile   = trim($_POST['mobile'] ?? "");


    /* ================= VALIDATION ================= */

    if ($name == "" || $email == "" || $password == "" || $mobile == "") {

        $error = "Please fill all fields.";

    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

        $error = "Please enter a valid email.";

    } elseif (!preg_match("/^[0-9]{10}$/", $mobile)) {

        $error = "Mobile number must be 10 digits.";

    } else {


        /* =================================================
           INSERT INTO REG TABLE

           NOTE:
           Email duplicate checking is NOT used.
        ================================================= */

        $stmt = mysqli_prepare(
            $conn,
            "INSERT INTO reg
            (name, email, password, mobile)
            VALUES (?, ?, ?, ?)"
        );


        if (!$stmt) {

            $error = "Database Error: " . mysqli_error($conn);

        } else {

            mysqli_stmt_bind_param(
                $stmt,
                "ssss",
                $name,
                $email,
                $password,
                $mobile
            );


            if (mysqli_stmt_execute($stmt)) {

                echo "<script>
                    alert('Registration Successful!');
                    window.location.href='login.php';
                </script>";

                exit();

            } else {

                $error = "Registration Failed: " .
                         mysqli_error($conn);
            }


            mysqli_stmt_close($stmt);
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

<title>Register | Swiffin Cake Shop</title>


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

    width: 100%;
    min-height: 100vh;

    background: #000;

    color: #fff;

    font-family: Arial, Helvetica, sans-serif;

    display: flex;

    justify-content: center;

    align-items: center;

    padding: 20px;

    position: relative;

    overflow-x: hidden;
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
    left: -200px;

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
    right: -200px;

    pointer-events: none;
}


/* =====================================================
   REGISTER BOX
===================================================== */

.register-box {

    position: relative;

    z-index: 2;

    width: 450px;

    max-width: 100%;

    background: #111;

    padding: 35px;

    border-radius: 18px;

    border: 1px solid #E88F2A;

    box-shadow:
        0 0 20px rgba(232,143,42,0.35),
        0 0 40px rgba(232,143,42,0.15);
}


/* =====================================================
   LOGO
===================================================== */

.logo {

    text-align: center;

    color: #E88F2A;

    font-size: 32px;

    font-weight: bold;

    letter-spacing: 3px;

    margin-bottom: 6px;
}


/* =====================================================
   TITLE
===================================================== */

.title {

    text-align: center;

    color: #fff;

    font-size: 22px;

    font-weight: bold;

    margin-bottom: 10px;
}


/* =====================================================
   ORANGE LINE
===================================================== */

.orange-line {

    width: 70px;

    height: 3px;

    background: #E88F2A;

    margin: 0 auto 25px;

    border-radius: 5px;
}


/* =====================================================
   ERROR
===================================================== */

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


/* =====================================================
   FORM GROUP
===================================================== */

.form-group {

    margin-bottom: 17px;
}


/* =====================================================
   LABEL
===================================================== */

label {

    display: block;

    color: #E88F2A;

    font-size: 14px;

    font-weight: bold;

    margin-bottom: 7px;
}


/* =====================================================
   INPUT
===================================================== */

input {

    width: 100%;

    height: 48px;

    padding: 0 15px;

    background: #050505;

    color: #fff;

    border: 1px solid #444;

    border-radius: 7px;

    outline: none;

    font-size: 15px;

    transition: 0.3s;
}


input::placeholder {

    color: #666;
}


input:focus {

    border-color: #E88F2A;

    box-shadow:
        0 0 8px rgba(232,143,42,0.35);
}


/* =====================================================
   REGISTER BUTTON
===================================================== */

.register-btn {

    width: 100%;

    height: 50px;

    margin-top: 5px;

    background: #E88F2A;

    color: #fff;

    border: none;

    border-radius: 7px;

    font-size: 17px;

    font-weight: bold;

    cursor: pointer;

    transition: 0.3s;
}


.register-btn:hover {

    background: #fff;

    color: #000;

    transform: translateY(-2px);

    box-shadow:
        0 5px 20px rgba(232,143,42,0.35);
}


/* =====================================================
   LOGIN LINK
===================================================== */

.login-text {

    text-align: center;

    margin-top: 20px;

    color: #ccc;

    font-size: 14px;
}


.login-text a {

    color: #E88F2A;

    text-decoration: none;

    font-weight: bold;

    margin-left: 4px;
}


.login-text a:hover {

    color: #fff;

    text-decoration: underline;
}


/* =====================================================
   BACK HOME
===================================================== */

.back {

    text-align: center;

    margin-top: 13px;
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


/* =====================================================
   MOBILE
===================================================== */

@media (max-width: 500px) {

    body {

        padding: 15px;
    }


    .register-box {

        width: 100%;

        padding: 30px 23px;
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


<!-- =====================================================
     REGISTER BOX
===================================================== -->

<div class="register-box">


    <!-- LOGO -->

    <div class="logo">
        SWIFFIN
    </div>


    <!-- TITLE -->

    <div class="title">
        Create Account
    </div>


    <!-- ORANGE LINE -->

    <div class="orange-line"></div>


    <!-- ERROR -->

    <?php if ($error != "") { ?>

        <div class="error">

            <?php
            echo htmlspecialchars($error);
            ?>

        </div>

    <?php } ?>


    <!-- =================================================
         REGISTER FORM
    ================================================= -->

    <form method="POST" action="">


        <!-- NAME -->

        <div class="form-group">

            <label for="name">
                Name
            </label>

            <input
                type="text"
                id="name"
                name="name"
                placeholder="Enter Name"
                autocomplete="name"
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
                placeholder="Enter Email"
                autocomplete="email"
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
                placeholder="Enter Password"
                autocomplete="new-password"
                required
            >

        </div>


        <!-- MOBILE -->

        <div class="form-group">

            <label for="mobile">
                Mobile Number
            </label>

            <input
                type="tel"
                id="mobile"
                name="mobile"
                placeholder="Enter Mobile Number"
                maxlength="10"
                pattern="[0-9]{10}"
                required
            >

        </div>


        <!-- REGISTER BUTTON -->

        <button
            type="submit"
            name="register"
            class="register-btn"
        >
            Register
        </button>


    </form>


    <!-- LOGIN -->

    <div class="login-text">

        Already have an account?

        <a href="login.php">
            Login
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


