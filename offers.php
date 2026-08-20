<?php

session_start();
include "config.php";

/* =====================================================
   UPDATE OFFER
===================================================== */

if (isset($_POST['update_offer'])) {

    $id = intval($_POST['id']);
    $cake_id = intval($_POST['cake_id']);

    $offer_title = mysqli_real_escape_string(
        $conn,
        $_POST['offer_title']
    );

    $description = mysqli_real_escape_string(
        $conn,
        $_POST['description']
    );

    $discount = intval($_POST['discount']);

    $start_date = mysqli_real_escape_string(
        $conn,
        $_POST['start_date']
    );

    $end_date = mysqli_real_escape_string(
        $conn,
        $_POST['end_date']
    );

    $status = mysqli_real_escape_string(
        $conn,
        $_POST['status']
    );

    $sql = "UPDATE offers SET
            cake_id = '$cake_id',
            offer_title = '$offer_title',
            description = '$description',
            discount = '$discount',
            start_date = '$start_date',
            end_date = '$end_date',
            status = '$status'
            WHERE id = '$id'";

    if (mysqli_query($conn, $sql)) {

        echo "<script>
                alert('Offer Updated Successfully');
                window.location='offers.php';
              </script>";

        exit();

    } else {

        die(
            "Offer Update Error: " .
            mysqli_error($conn)
        );
    }
}


/* =====================================================
   ADD OFFER
===================================================== */

if (isset($_POST['add_offer'])) {

    $cake_id = intval($_POST['cake_id']);

    $offer_title = mysqli_real_escape_string(
        $conn,
        $_POST['offer_title']
    );

    $description = mysqli_real_escape_string(
        $conn,
        $_POST['description']
    );

    $discount = intval($_POST['discount']);

    $start_date = mysqli_real_escape_string(
        $conn,
        $_POST['start_date']
    );

    $end_date = mysqli_real_escape_string(
        $conn,
        $_POST['end_date']
    );

    $status = mysqli_real_escape_string(
        $conn,
        $_POST['status']
    );

    $sql = "INSERT INTO offers
            (
                cake_id,
                offer_title,
                description,
                discount,
                start_date,
                end_date,
                status
            )
            VALUES
            (
                '$cake_id',
                '$offer_title',
                '$description',
                '$discount',
                '$start_date',
                '$end_date',
                '$status'
            )";

    if (mysqli_query($conn, $sql)) {

        echo "<script>
                alert('Offer Added Successfully');
                window.location='offers.php';
              </script>";

        exit();

    } else {

        die(
            "Offer Insert Error: " .
            mysqli_error($conn)
        );
    }
}


/* =====================================================
   DELETE OFFER
===================================================== */

if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    $delete_query = mysqli_query(
        $conn,
        "DELETE FROM offers WHERE id='$id'"
    );

    if ($delete_query) {

        echo "<script>
                alert('Offer Deleted Successfully');
                window.location='offers.php';
              </script>";

        exit();

    } else {

        die(
            "Delete Error: " .
            mysqli_error($conn)
        );
    }
}


/* =====================================================
   EDIT OFFER
===================================================== */

$edit_offer = null;

if (isset($_GET['edit'])) {

    $id = intval($_GET['edit']);

    $edit_query = mysqli_query(
        $conn,
        "SELECT * FROM offers
         WHERE id='$id'
         LIMIT 1"
    );

    if (!$edit_query) {

        die(
            "Edit Database Error: " .
            mysqli_error($conn)
        );
    }

    if (mysqli_num_rows($edit_query) > 0) {

        $edit_offer = mysqli_fetch_assoc(
            $edit_query
        );
    }
}


/* =====================================================
   GET CAKES
===================================================== */

$cake_query = mysqli_query(
    $conn,
    "SELECT id, cake_name
     FROM cake
     ORDER BY cake_name ASC"
);

if (!$cake_query) {

    die(
        "Cake Database Error: " .
        mysqli_error($conn)
    );
}


/* =====================================================
   GET OFFERS
===================================================== */

$query = mysqli_query(
    $conn,
    "SELECT
        offers.*,
        cake.cake_name
     FROM offers
     LEFT JOIN cake
     ON offers.cake_id = cake.id
     ORDER BY offers.id DESC"
);

if (!$query) {

    die(
        "Offer Database Error: " .
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
Offers | Swiffin Cake Shop
</title>


<!-- Bootstrap -->

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet"
>


<!-- Font Awesome -->

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

    max-width:1250px;

    margin:40px auto;

}


/* =====================================================
   PAGE TITLE
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
   OFFER BOX
===================================================== */

.offer-box{

    background:#111;

    border:1px solid #E88F2A;

    border-radius:18px;

    padding:25px;

    margin-bottom:30px;

    box-shadow:
    0 0 20px rgba(232,143,42,.20);

}


/* =====================================================
   SECTION TITLE
===================================================== */

.section-title{

    color:#E88F2A;

    font-size:22px;

    font-weight:bold;

    margin-bottom:20px;

}


/* =====================================================
   FORM LABEL
===================================================== */

.form-label{

    color:#ccc;

    font-weight:bold;

}


/* =====================================================
   INPUT
===================================================== */

.form-control,
.form-select{

    background:#000 !important;

    color:#fff !important;

    border:1px solid #444;

}


.form-control:focus,
.form-select:focus{

    border-color:#E88F2A;

    box-shadow:
    0 0 8px rgba(232,143,42,.25);

}


.form-control::placeholder{

    color:#777;

}


/* =====================================================
   SELECT OPTION
===================================================== */

.form-select option{

    background:#000;

    color:#fff;

}


/* =====================================================
   DATE
===================================================== */

input[type="date"]{

    color-scheme:dark;

}


/* =====================================================
   BUTTONS
===================================================== */

.add-btn,
.update-btn{

    background:#E88F2A;

    color:#fff;

    border:none;

    border-radius:25px;

    padding:11px 25px;

    font-weight:bold;

}


.add-btn:hover,
.update-btn:hover{

    background:#d67d18;

    color:#fff;

}


.cancel-btn{

    background:#6c757d;

    color:#fff;

    border:none;

    border-radius:25px;

    padding:11px 25px;

    font-weight:bold;

    text-decoration:none;

    display:inline-block;

}


.cancel-btn:hover{

    background:#5c636a;

    color:#fff;

}


/* =====================================================
   TABLE BOX
===================================================== */

.table-box{

    background:#111;

    border:1px solid #333;

    border-radius:18px;

    padding:20px;

    overflow:hidden;

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


.table tbody tr:hover td{

    background:#252525 !important;

}


/* =====================================================
   DISCOUNT
===================================================== */

.discount{

    color:#E88F2A;

    font-size:20px;

    font-weight:bold;

}


/* =====================================================
   STATUS
===================================================== */

.active{

    color:#28a745;

    font-weight:bold;

}


.inactive{

    color:#dc3545;

    font-weight:bold;

}


/* =====================================================
   ACTION BUTTONS
===================================================== */

.edit-btn{

    background:#0d6efd;

    color:#fff;

    text-decoration:none;

    border:none;

    padding:7px 14px;

    border-radius:20px;

    font-weight:bold;

    display:inline-block;

    margin:2px;

}


.edit-btn:hover{

    background:#0b5ed7;

    color:#fff;

}


.delete-btn{

    background:#dc3545;

    color:#fff;

    text-decoration:none;

    border:none;

    padding:7px 14px;

    border-radius:20px;

    font-weight:bold;

    display:inline-block;

    margin:2px;

}


.delete-btn:hover{

    background:#bb2d3b;

    color:#fff;

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

@media(max-width:700px){

    .main{

        width:96%;

        margin:25px auto;

    }

    .offer-box{

        padding:18px;

    }

    .page-title h2{

        font-size:25px;

    }

    .table{

        font-size:14px;

    }

}

</style>

</head>


<body>


<div class="main">


<!-- =====================================================
     PAGE TITLE
===================================================== -->

<div class="page-title">

<h2>

<i class="fas fa-tags"></i>

&nbsp; Offers Management

</h2>

<p>

Add, edit and manage Swiffin Cake Shop offers

</p>

</div>


<!-- =====================================================
     ADD / EDIT FORM
===================================================== -->

<div class="offer-box">


<div class="section-title">

<?php

if ($edit_offer) {

?>

<i class="fas fa-edit"></i>

&nbsp; Edit Offer

<?php

} else {

?>

<i class="fas fa-plus-circle"></i>

&nbsp; Add New Offer

<?php

}

?>

</div>


<form method="POST">


<!-- EDIT ID -->

<?php

if ($edit_offer) {

?>

<input
type="hidden"
name="id"
value="<?php
echo intval($edit_offer['id']);
?>"
>

<?php

}

?>


<div class="row g-3">


<!-- =================================================
     CAKE
================================================= -->

<div class="col-md-6">

<label class="form-label">

Select Cake

</label>


<select
name="cake_id"
class="form-select"
required
>

<option value="">

-- Select Cake --

</option>


<?php

while (
    $cake = mysqli_fetch_assoc(
        $cake_query
    )
) {

    $selected = "";

    if (
        $edit_offer &&
        $edit_offer['cake_id'] ==
        $cake['id']
    ) {

        $selected = "selected";
    }

?>

<option
value="<?php
echo intval($cake['id']);
?>"
<?php
echo $selected;
?>
>

<?php

echo htmlspecialchars(
    $cake['cake_name']
);

?>

</option>

<?php

}

?>

</select>

</div>


<!-- =================================================
     OFFER TITLE
================================================= -->

<div class="col-md-6">

<label class="form-label">

Offer Title

</label>


<input
type="text"
name="offer_title"
class="form-control"
placeholder="Example: Red Velvet Special"
value="<?php

if ($edit_offer) {

    echo htmlspecialchars(
        $edit_offer['offer_title']
    );

}

?>"
required
>

</div>


<!-- =================================================
     DISCOUNT
================================================= -->

<div class="col-md-3">

<label class="form-label">

Discount (%)

</label>


<input
type="number"
name="discount"
class="form-control"
min="1"
max="100"
placeholder="20"
value="<?php

if ($edit_offer) {

    echo intval(
        $edit_offer['discount']
    );

}

?>"
required
>

</div>


<!-- =================================================
     STATUS
================================================= -->

<div class="col-md-3">

<label class="form-label">

Status

</label>


<select
name="status"
class="form-select"
required
>


<option
value="Active"
<?php

if (
    $edit_offer &&
    $edit_offer['status'] ==
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
    $edit_offer &&
    $edit_offer['status'] ==
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


<!-- =================================================
     DESCRIPTION
================================================= -->

<div class="col-md-6">

<label class="form-label">

Description

</label>


<textarea
name="description"
class="form-control"
rows="3"
placeholder="Write offer details..."
required
><?php

if ($edit_offer) {

    echo htmlspecialchars(
        $edit_offer['description']
    );

}

?></textarea>

</div>


<!-- =================================================
     START DATE
================================================= -->

<div class="col-md-3">

<label class="form-label">

Start Date

</label>


<input
type="date"
name="start_date"
class="form-control"
value="<?php

if ($edit_offer) {

    echo htmlspecialchars(
        $edit_offer['start_date']
    );

}

?>"
required
>

</div>


<!-- =================================================
     END DATE
================================================= -->

<div class="col-md-3">

<label class="form-label">

End Date

</label>


<input
type="date"
name="end_date"
class="form-control"
value="<?php

if ($edit_offer) {

    echo htmlspecialchars(
        $edit_offer['end_date']
    );

}

?>"
required
>

</div>


<!-- =================================================
     BUTTON
================================================= -->

<div class="col-12">


<?php

if ($edit_offer) {

?>

<button
type="submit"
name="update_offer"
class="update-btn"
>

<i class="fas fa-save"></i>

&nbsp; Update Offer

</button>


<a
href="offers.php"
class="cancel-btn ms-2"
>

<i class="fas fa-times"></i>

&nbsp; Cancel

</a>


<?php

} else {

?>

<button
type="submit"
name="add_offer"
class="add-btn"
>

<i class="fas fa-plus"></i>

&nbsp; Add Offer

</button>

<?php

}

?>

</div>


</div>

</form>

</div>


<!-- =====================================================
     ALL OFFERS
===================================================== -->

<div class="section-title">

<i class="fas fa-list"></i>

&nbsp; All Offers

</div>


<div class="table-box">


<div class="table-responsive">


<table
class="table table-bordered table-hover"
>


<thead>

<tr>

<th>ID</th>

<th>Cake</th>

<th>Offer</th>

<th>Description</th>

<th>Discount</th>

<th>Start Date</th>

<th>End Date</th>

<th>Status</th>

<th>Action</th>

</tr>

</thead>


<tbody>


<?php

if (
    mysqli_num_rows($query) > 0
) {

    while (
        $row =
        mysqli_fetch_assoc($query)
    ) {

?>


<tr>


<!-- ID -->

<td>

<?php

echo intval($row['id']);

?>

</td>


<!-- CAKE -->

<td>

<strong>

<?php

echo htmlspecialchars(
    $row['cake_name'] ??
    "Unknown Cake"
);

?>

</strong>

</td>


<!-- OFFER -->

<td>

<?php

echo htmlspecialchars(
    $row['offer_title']
);

?>

</td>


<!-- DESCRIPTION -->

<td>

<?php

echo htmlspecialchars(
    $row['description']
);

?>

</td>


<!-- DISCOUNT -->

<td>

<span class="discount">

<?php

echo intval(
    $row['discount']
);

?>%

</span>

</td>


<!-- START DATE -->

<td>

<?php

if (
    !empty($row['start_date']) &&
    $row['start_date'] !=
    "0000-00-00"
) {

    echo date(
        "d-m-Y",
        strtotime(
            $row['start_date']
        )
    );

} else {

    echo "-";

}

?>

</td>


<!-- END DATE -->

<td>

<?php

if (
    !empty($row['end_date']) &&
    $row['end_date'] !=
    "0000-00-00"
) {

    echo date(
        "d-m-Y",
        strtotime(
            $row['end_date']
        )
    );

} else {

    echo "-";

}

?>

</td>


<!-- STATUS -->

<td>

<?php

if (
    $row['status'] ==
    "Active"
) {

?>

<span class="active">

<i class="fas fa-circle-check"></i>

Active

</span>

<?php

} else {

?>

<span class="inactive">

<i class="fas fa-circle-xmark"></i>

Inactive

</span>

<?php

}

?>

</td>


<!-- ACTION -->

<td>


<a
href="offers.php?edit=<?php
echo intval($row['id']);
?>"
class="edit-btn"
>

<i class="fas fa-edit"></i>

Edit

</a>


<a
href="offers.php?delete=<?php
echo intval($row['id']);
?>"
class="delete-btn"
onclick="return confirm('Are you sure you want to delete this offer?');"
>

<i class="fas fa-trash"></i>

Delete

</a>


</td>


</tr>


<?php

    }

} else {

?>


<tr>

<td
colspan="9"
style="
padding:30px;
color:#aaa;
text-align:center;
"
>

<i
class="fas fa-tags"
style="
font-size:30px;
color:#555;
display:block;
margin-bottom:10px;
"
></i>

No Offers Found

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
