
<?php

session_start();
include("config.php");

$error = "";


/* =====================================================
   ADMIN LOGIN
===================================================== */

if (isset($_POST['login'])) {

    $name = trim($_POST['name'] ?? "");
    $email = trim($_POST['email'] ?? "");
    $password = trim($_POST['password'] ?? "");


    if ($name == "" || $email == "" || $password == "") {

        $error = "Please enter Name, Email and Password.";

    } else {

        /* =================================================
           CHECK ADMIN
        ================================================= */

        $stmt = mysqli_prepare(
            $conn,
            "SELECT name, email, password
             FROM admin
             WHERE name = ?
             AND email = ?
             AND password = ?
             LIMIT 1"
        );

        if (!$stmt) {

            $error = "Database Error: " . mysqli_error($conn);

        } else {

            mysqli_stmt_bind_param(
                $stmt,
                "sss",
                $name,
                $email,
                $password
            );

            mysqli_stmt_execute($stmt);

            $result = mysqli_stmt_get_result($stmt);


            if ($result && mysqli_num_rows($result) > 0) {

                $admin = mysqli_fetch_assoc($result);


                /* =================================================
                   CREATE ADMIN SESSION
                ================================================= */

                $_SESSION['admin'] = true;

                $_SESSION['admin_name'] = $admin['name'];

                $_SESSION['admin_email'] = $admin['email'];


                mysqli_stmt_close($stmt);


                /* =================================================
                   GO TO DASHBOARD
                ================================================= */

                header("Location: admin_dashboard.php");
                exit();

            } else {

                mysqli_stmt_close($stmt);


                /* =================================================
                   CHECK CUSTOMER
                ================================================= */

                $stmt = mysqli_prepare(
                    $conn,
                    "SELECT name, email, password
                     FROM reg
                     WHERE name = ?
                     AND email = ?
                     AND password = ?
                     LIMIT 1"
                );


                if ($stmt) {

                    mysqli_stmt_bind_param(
                        $stmt,
                        "sss",
                        $name,
                        $email,
                        $password
                    );

                    mysqli_stmt_execute($stmt);

                    $customer_result =
                        mysqli_stmt_get_result($stmt);


                    if (
                        $customer_result &&
                        mysqli_num_rows($customer_result) > 0
                    ) {

                        $customer =
                            mysqli_fetch_assoc(
                                $customer_result
                            );


                        /* CUSTOMER SESSION */

                        $_SESSION['customer'] = true;

                        $_SESSION['customer_name'] =
                            $customer['name'];

                        $_SESSION['customer_email'] =
                            $customer['email'];


                        mysqli_stmt_close($stmt);


                        header("Location: index1.php");
                        exit();

                    } else {

                        mysqli_stmt_close($stmt);

                        $error =
                            "Invalid Name, Email or Password!";
                    }

                } else {

                    $error =
                        "Customer Database Error: " .
                        mysqli_error($conn);
                }
            }
        }
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

*{
    margin:0;
    padding:0;
    box-sizing:border-box;
}


body{

    font-family:Arial,sans-serif;

    background:#000;

    min-height:100vh;

    display:flex;

    justify-content:center;

    align-items:center;

    position:relative;

    overflow:hidden;
}


body::before{

    content:"";

    position:absolute;

    width:500px;
    height:500px;

    background:#E88F2A;

    border-radius:50%;

    filter:blur(180px);

    opacity:.13;

    top:-250px;
    left:-200px;
}


body::after{

    content:"";

    position:absolute;

    width:450px;
    height:450px;

    background:#E88F2A;

    border-radius:50%;

    filter:blur(180px);

    opacity:.10;

    bottom:-250px;
    right:-200px;
}


.login-box{

    position:relative;

    z-index:2;

    width:400px;

    background:#111;

    padding:42px;

    border-radius:18px;

    border:1px solid #E88F2A;

    box-shadow:
        0 0 20px rgba(232,143,42,.30),
        0 0 45px rgba(232,143,42,.15);
}


.logo{

    text-align:center;

    color:#E88F2A;

    font-size:36px;

    font-weight:bold;

    letter-spacing:3px;

    margin-bottom:8px;
}


.title{

    text-align:center;

    color:#fff;

    font-size:23px;

    font-weight:bold;

    margin-bottom:10px;
}


.subtitle{

    text-align:center;

    color:#888;

    font-size:13px;

    margin-bottom:20px;
}


.orange-line{

    width:60px;

    height:3px;

    background:#E88F2A;

    margin:0 auto 28px;

    border-radius:5px;
}


.error{

    background:#250000;

    color:#ff6666;

    border:1px solid #ff3333;

    padding:12px;

    border-radius:7px;

    text-align:center;

    margin-bottom:20px;

    font-size:14px;
}


.form-group{

    margin-bottom:18px;
}


label{

    display:block;

    color:#E88F2A;

    font-size:14px;

    font-weight:bold;

    margin-bottom:8px;
}


input{

    width:100%;

    height:48px;

    padding:0 15px;

    background:#050505;

    color:#fff;

    border:1px solid #444;

    border-radius:7px;

    outline:none;

    font-size:15px;

    transition:.3s;
}


input::placeholder{

    color:#777;
}


input:focus{

    border-color:#E88F2A;

    box-shadow:
        0 0 8px rgba(232,143,42,.35);
}


.login-btn{

    width:100%;

    height:50px;

    margin-top:5px;

    background:#E88F2A;

    color:#fff;

    border:none;

    border-radius:8px;

    font-size:17px;

    font-weight:bold;

    cursor:pointer;

    transition:.3s;
}


.login-btn:hover{

    background:#fff;

    color:#000;

    transform:translateY(-2px);

    box-shadow:
        0 5px 20px rgba(232,143,42,.35);
}


.register{

    text-align:center;

    margin-top:22px;

    color:#ccc;

    font-size:14px;
}


.register a{

    color:#E88F2A;

    text-decoration:none;

    font-weight:bold;

    margin-left:4px;
}


.register a:hover{

    color:#fff;

    text-decoration:underline;
}


.back{

    text-align:center;

    margin-top:15px;
}


.back a{

    color:#E88F2A;

    text-decoration:none;

    font-size:14px;
}


.back a:hover{

    color:#fff;

    text-decoration:underline;
}


@media(max-width:500px){

    body{

        padding:20px;
    }


    .login-box{

        width:100%;

        padding:32px 25px;
    }


    .logo{

        font-size:30px;
    }


    .title{

        font-size:20px;
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


    <form method="POST" action="">


        <div class="form-group">

            <label>
                Name
            </label>

            <input
                type="text"
                name="name"
                placeholder="Enter your name"
                value="<?php
                echo htmlspecialchars(
                    $_POST['name'] ?? ''
                );
                ?>"
                required
            >

        </div>


        <div class="form-group">

            <label>
                Email
            </label>

            <input
                type="email"
                name="email"
                placeholder="Enter your email"
                value="<?php
                echo htmlspecialchars(
                    $_POST['email'] ?? ''
                );
                ?>"
                required
            >

        </div>


        <div class="form-group">

            <label>
                Password
            </label>

            <input
                type="password"
                name="password"
                placeholder="Enter your password"
                required
            >

        </div>


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
