<?php

session_start();
include "config.php";


/* =====================================================
   UPDATE STATUS
===================================================== */

if (isset($_POST['update_status'])) {

    $id = intval($_POST['id']);

    $status = mysqli_real_escape_string(
        $conn,
        $_POST['status']
    );

    $sql = "UPDATE enquiry
            SET status='$status'
            WHERE id='$id'";

    if (mysqli_query($conn, $sql)) {

        echo "<script>
                alert('Enquiry Status Updated Successfully');
                window.location='enquiry.php';
              </script>";

        exit();

    } else {

        die(
            "Update Error: " .
            mysqli_error($conn)
        );

    }
}


/* =====================================================
   DELETE ENQUIRY
===================================================== */

if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    $delete_query = mysqli_query(
        $conn,
        "DELETE FROM enquiry WHERE id='$id'"
    );

    if (!$delete_query) {

        die(
            "Delete Error: " .
            mysqli_error($conn)
        );

    }

    echo "<script>
            alert('Enquiry Deleted Successfully');
            window.location='enquiry.php';
          </script>";

    exit();
}


/* =====================================================
   GET ALL ENQUIRIES
===================================================== */

$query = mysqli_query(
    $conn,
    "SELECT *
     FROM enquiry
     ORDER BY id DESC"
);

if (!$query) {

    die(
        "Database Error: " .
        mysqli_error($conn)
    );

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

<title>
Enquiry Management | Swiffin Cake Shop
</title>


<!-- BOOTSTRAP -->

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet"
>


<!-- FONT AWESOME -->

<link
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
rel="stylesheet"
>


<style>

/* =====================================================
   RESET
===================================================== */

*{
    box-sizing:border-box;
}


/* =====================================================
   BODY
===================================================== */

body{

    margin:0;

    padding:0;

    background:#000;

    color:#fff;

    font-family:
    Arial,
    Helvetica,
    sans-serif;

}


/* =====================================================
   MAIN
===================================================== */

.main{

    width:95%;

    max-width:1300px;

    margin:40px auto;

}


/* =====================================================
   TITLE
===================================================== */

.page-title{

    text-align:center;

    margin-bottom:30px;

}


.page-title h2{

    color:#E88F2A;

    font-weight:bold;

}


.page-title p{

    color:#aaa;

}


/* =====================================================
   TABLE BOX
===================================================== */

.table-box{

    background:#111;

    border:1px solid #E88F2A;

    border-radius:18px;

    padding:20px;

    box-shadow:
    0 0 20px rgba(232,143,42,.20);

}


/* =====================================================
   TABLE
===================================================== */

.table{

    margin:0;

}


.table thead th{

    background:#E88F2A !important;

    color:#fff !important;

    text-align:center;

    vertical-align:middle;

    white-space:nowrap;

}


.table tbody td{

    background:#1b1b1b !important;

    color:#fff !important;

    text-align:center;

    vertical-align:middle;

    border-color:#333;

}


/* =====================================================
   MESSAGE
===================================================== */

.message{

    max-width:300px;

    min-width:200px;

    color:#ccc;

    text-align:left !important;

    line-height:1.5;

}


/* =====================================================
   STATUS SELECT
===================================================== */

.status-select{

    background:#000 !important;

    color:#fff !important;

    border:1px solid #444;

    border-radius:20px;

    padding:6px 10px;

    min-width:110px;

}


.status-select:focus{

    border-color:#E88F2A;

    box-shadow:none;

    outline:none;

}


/* =====================================================
   UPDATE BUTTON
===================================================== */

.update-btn{

    background:#E88F2A;

    color:#fff;

    border:none;

    border-radius:20px;

    padding:7px 14px;

    font-weight:bold;

    margin-top:5px;

}


.update-btn:hover{

    background:#d67d18;

    color:#fff;

}


/* =====================================================
   DELETE BUTTON
===================================================== */

.delete-btn{

    display:inline-block;

    background:#dc3545;

    color:#fff;

    text-decoration:none;

    padding:7px 14px;

    border-radius:20px;

    font-weight:bold;

    margin-top:5px;

}


.delete-btn:hover{

    background:#bb2d3b;

    color:#fff;

}


/* =====================================================
   DATE
===================================================== */

.date{

    color:#aaa;

    white-space:nowrap;

}


/* =====================================================
   BACK BUTTON
===================================================== */

.back-btn{

    display:inline-block;

    margin-top:25px;

    background:#E88F2A;

    color:#fff;

    text-decoration:none;

    padding:11px 25px;

    border-radius:25px;

    font-weight:bold;

}


.back-btn:hover{

    background:#d67d18;

    color:#fff;

}


/* =====================================================
   MOBILE
===================================================== */

@media(max-width:600px){

    .main{

        width:98%;

        margin:20px auto;

    }


    .table-box{

        padding:10px;

    }


    .page-title h2{

        font-size:24px;

    }

}

</style>

</head>


<body>


<div class="main">


<!-- =====================================================
     TITLE
===================================================== -->

<div class="page-title">

<h2>

<i class="fas fa-envelope-open-text"></i>

&nbsp; Enquiry Management

</h2>


<p>

View and manage customer enquiries

</p>

</div>


<!-- =====================================================
     ALL ENQUIRIES
===================================================== -->

<div class="table-box">


<div class="table-responsive">


<table class="table table-bordered table-hover">


<thead>

<tr>

<th>ID</th>

<th>Name</th>

<th>Email</th>

<th>Mobile</th>

<th>Subject</th>

<th>Message</th>

<th>Status</th>

<th>Date</th>

<th>Action</th>

</tr>

</thead>


<tbody>


<?php

if (mysqli_num_rows($query) > 0) {

    while ($row = mysqli_fetch_assoc($query)) {

?>


<tr>


<!-- ID -->

<td>

<?php

echo intval($row['id']);

?>

</td>


<!-- NAME -->

<td>

<strong>

<?php

echo htmlspecialchars(
    $row['name']
);

?>

</strong>

</td>


<!-- EMAIL -->

<td>

<?php

echo htmlspecialchars(
    $row['email']
);

?>

</td>


<!-- MOBILE -->

<td>

<?php

echo htmlspecialchars(
    $row['mobile']
);

?>

</td>


<!-- SUBJECT -->

<td>

<?php

echo htmlspecialchars(
    $row['subject']
);

?>

</td>


<!-- MESSAGE -->

<td class="message">

<?php

echo nl2br(
    htmlspecialchars(
        $row['message']
    )
);

?>

</td>


<!-- STATUS -->

<td>


<form
method="POST"
>


<input
type="hidden"
name="id"
value="<?php
echo intval($row['id']);
?>"
>


<select
name="status"
class="status-select"
required
>


<option
value="Pending"

<?php

if (
    $row['status'] == "Pending"
) {

    echo "selected";

}

?>

>

Pending

</option>


<option
value="Read"

<?php

if (
    $row['status'] == "Read"
) {

    echo "selected";

}

?>

>

Read

</option>


<option
value="Replied"

<?php

if (
    $row['status'] == "Replied"
) {

    echo "selected";

}

?>

>

Replied

</option>


</select>


<br>


<button
type="submit"
name="update_status"
class="update-btn"
>

<i class="fas fa-save"></i>

&nbsp; Update

</button>


</form>


</td>


<!-- DATE -->

<td class="date">

<?php

if (
    !empty($row['enquiry_date']) &&
    $row['enquiry_date'] != "0000-00-00 00:00:00"
) {

    echo date(
        "d-m-Y h:i A",
        strtotime(
            $row['enquiry_date']
        )
    );

} else {

    echo "-";

}

?>

</td>


<!-- DELETE -->

<td>


<a
href="enquiry.php?delete=<?php
echo intval($row['id']);
?>"
class="delete-btn"

onclick="return confirm('Are you sure you want to delete this enquiry?');"
>

<i class="fas fa-trash"></i>

&nbsp; Delete

</a>


</td>


</tr>


<?php

    }

} else {

?>


<tr>

<td colspan="9">

No Enquiries Found

</td>

</tr>


<?php

}

?>


</tbody>


</table>


</div>

</div>


<!-- =====================================================
     BACK BUTTON
===================================================== -->

<div class="text-center">

<a
href="admin_dashboard.php"
class="back-btn"
>

<i class="fas fa-arrow-left"></i>

&nbsp; Back To Dashboard

</a>

</div>


</div>


</body>

</html>
