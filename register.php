<?php
session_start();

include "config.php";

$error = "";
$success = "";

if (isset($_POST['register'])) {

    $name = trim($_POST['name']);
    $email = trim($_POST['email']);
    $password = trim($_POST['password']);
    $mobile = trim($_POST['mobile']);

    /* CHECK EXISTING EMAIL */

    $check = mysqli_query(
        $conn,
        "SELECT * FROM customer WHERE email='$email'"
    );

    if (mysqli_num_rows($check) > 0) {

        $error = "Email already registered!";

    } else {

        /* INSERT CUSTOMER */

        $insert = mysqli_query(
            $conn,
            "INSERT INTO customer
            (name, email, password, mobile)
            VALUES
            ('$name', '$email', '$password', '$mobile')"
        );

        if ($insert) {

            echo "<script>
                    alert('Registration Successful!');
                    window.location.href='login.php';
                  </script>";
            exit();

        } else {

            $error = "Registration Failed: " . mysqli_error($conn);
        }
    }
}
?>

<!DOCTYPE html>

<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Register | Swiffin Cake Shop</title>


    <!-- Bootstrap -->

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >


    <!-- Register CSS -->

    <link
        rel="stylesheet"
        href="css/register.css"
    >


    <style>

        body {

            margin: 0;
            padding: 0;

            background: #000;

            color: #fff;

            font-family: Arial, sans-serif;

            min-height: 100vh;

            display: flex;

            justify-content: center;

            align-items: center;
        }


        .register-box {

            width: 450px;

            background: #111;

            padding: 35px;

            border-radius: 18px;

            border: 1px solid #E88F2A;

            box-shadow:
                0 0 20px rgba(232,143,42,0.35),
                0 0 40px rgba(232,143,42,0.15);
        }


        .logo {

            text-align: center;

            color: #E88F2A;

            font-size: 32px;

            font-weight: bold;

            letter-spacing: 2px;

            margin-bottom: 5px;
        }


        .title {

            text-align: center;

            color: #fff;

            font-size: 22px;

            font-weight: bold;

            margin-bottom: 28px;
        }


        .orange-line {

            width: 70px;

            height: 3px;

            background: #E88F2A;

            margin: -18px auto 25px;

            border-radius: 5px;
        }


        .form-group {

            margin-bottom: 18px;
        }


        label {

            display: block;

            color: #E88F2A;

            font-weight: bold;

            margin-bottom: 7px;
        }


        input {

            width: 100%;

            padding: 12px;

            background: #050505;

            color: #fff;

            border: 1px solid #444;

            border-radius: 7px;

            box-sizing: border-box;

            outline: none;

            font-size: 15px;

            transition: 0.3s;
        }


        input::placeholder {

            color: #777;
        }


        input:focus {

            border-color: #E88F2A;

            box-shadow:
                0 0 8px rgba(232,143,42,0.35);
        }


        .register-btn {

            width: 100%;

            padding: 13px;

            background: #E88F2A;

            color: #fff;

            border: none;

            border-radius: 7px;

            font-size: 18px;

            font-weight: bold;

            cursor: pointer;

            transition: 0.3s;
        }


        .register-btn:hover {

            background: #fff;

            color: #000;

            box-shadow:
                0 0 15px rgba(232,143,42,0.4);

            transform: translateY(-2px);
        }


        .error {

            background: #dc3545;

            color: #fff;

            padding: 10px;

            border-radius: 6px;

            text-align: center;

            margin-bottom: 20px;
        }


        .login-text {

            text-align: center;

            margin-top: 20px;

            color: #ccc;
        }


        .login-text a {

            color: #E88F2A;

            text-decoration: none;

            font-weight: bold;
        }


        .login-text a:hover {

            color: #fff;

            text-decoration: underline;
        }


        .back {

            text-align: center;

            margin-top: 12px;
        }


        .back a {

            color: #E88F2A;

            text-decoration: none;
        }


        .back a:hover {

            color: #fff;

            text-decoration: underline;
        }


        @media (max-width: 500px) {

            .register-box {

                width: 90%;

                padding: 28px 22px;
            }
        }

    </style>

</head>


<body>


<div class="register-box">


    <!-- LOGO -->

    <div class="logo">
        SWIFFIN
    </div>


    <!-- TITLE -->

    <div class="title">
        Create Account
    </div>


    <div class="orange-line"></div>


    <!-- ERROR MESSAGE -->

    <?php if ($error != "") { ?>

        <div class="error">
            <?php echo htmlspecialchars($error); ?>
        </div>

    <?php } ?>


    <!-- REGISTER FORM -->

    <form method="POST" action="">


        <!-- NAME -->

        <div class="form-group">

            <label>Name</label>

            <input
                type="text"
                name="name"
                placeholder="Enter Name"
                required
            >

        </div>


        <!-- EMAIL -->

        <div class="form-group">

            <label>Email</label>

            <input
                type="email"
                name="email"
                placeholder="Enter Email"
                required
            >

        </div>


        <!-- PASSWORD -->

        <div class="form-group">

            <label>Password</label>

            <input
                type="password"
                name="password"
                placeholder="Enter Password"
                required
            >

        </div>


        <!-- MOBILE -->

        <div class="form-group">

            <label>Mobile Number</label>

            <input
                type="tel"
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


    <!-- LOGIN LINK -->

    <div class="login-text">

        Already have an account?

        <a href="login.php">
            Login
        </a>

    </div>


    <!-- BACK TO HOME -->

    <div class="back">

        <a href="index1.php">
            ← Back to Home
        </a>

    </div>


</div>


</body>

</html>
