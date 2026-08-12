<?php

session_start();
include "config.php";

/* ================= ADD TO CART ================= */

if (isset($_POST['add_to_cart'])) {

    $cake_id = intval($_POST['cake_id']);
    $quantity = intval($_POST['quantity']);

    if ($quantity <= 0) {
        die("Invalid Quantity");
    }

    /* ================= GET CAKE DETAILS ================= */

    $query = mysqli_query(
        $conn,
        "SELECT * FROM cake WHERE id='$cake_id' LIMIT 1"
    );

    if (!$query) {
        die("Database Error: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($query) == 0) {
        die("Cake Not Found");
    }

    $cake = mysqli_fetch_assoc($query);

    $cake_name = $cake['cake_name'];
    $price = floatval($cake['price']);
    $stock = intval($cake['stock']);
    $status = $cake['status'];

    /* ================= STOCK CHECK ================= */

    if ($stock <= 0 || $status != "Available") {

        echo "<script>
                alert('This Cake is Out of Stock!');
                window.history.back();
              </script>";
        exit();
    }

    if ($stock < $quantity) {

        echo "<script>
                alert('Only $stock Cake(s) Available');
                window.history.back();
              </script>";
        exit();
    }

    /* ================= TOTAL ================= */

    $total = $quantity * $price;

    /* ================= INSERT CART ================= */

    $cake_name_safe = mysqli_real_escape_string($conn, $cake_name);

    $insert = mysqli_query(
        $conn,
        "INSERT INTO carts
        (
            cake_name,
            quantity,
            price,
            total
        )
        VALUES
        (
            '$cake_name_safe',
            '$quantity',
            '$price',
            '$total'
        )"
    );

    if (!$insert) {
        die("Cart Insert Error: " . mysqli_error($conn));
    }

    /* ================= CART ID ================= */

    $cart_id = mysqli_insert_id($conn);

    /* ================= GO TO CART ================= */

    header("Location: carts.php?id=" . $cart_id);
    exit();
}

else {

    die("Invalid Request");

}

?>