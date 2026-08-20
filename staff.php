<?php

session_start();
include "config.php";

/* =====================================================
   UPDATE STAFF
===================================================== */

if (isset($_POST['update_staff'])) {

    $id = intval($_POST['id']);
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    $salary = floatval($_POST['salary']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $sql = "UPDATE staff SET
            name='$name',
            email='$email',
            mobile='$mobile',
            position='$position',
            salary='$salary',
            status='$status'
            WHERE id='$id'";

    if (mysqli_query($conn, $sql)) {

        echo "<script>
                alert('Staff Updated Successfully');
                window.location='staff.php';
              </script>";
        exit();

    } else {

        die("Staff Update Error: " . mysqli_error($conn));
    }
}


/* =====================================================
   ADD STAFF
===================================================== */

if (isset($_POST['add_staff'])) {

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $mobile = mysqli_real_escape_string($conn, $_POST['mobile']);
    $position = mysqli_real_escape_string($conn, $_POST['position']);
    $salary = floatval($_POST['salary']);
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    $sql = "INSERT INTO staff
            (name, email, mobile, position, salary, status)
            VALUES
            ('$name', '$email', '$mobile', '$position', '$salary', '$status')";

    if (mysqli_query($conn, $sql)) {

        echo "<script>
                alert('Staff Added Successfully');
                window.location='staff.php';
              </script>";
        exit();

    } else {

        die("Staff Insert Error: " . mysqli_error($conn));
    }
}


/* =====================================================
   DELETE STAFF
===================================================== */

if (isset($_GET['delete'])) {

    $id = intval($_GET['delete']);

    $delete = mysqli_query(
        $conn,
        "DELETE FROM staff WHERE id='$id'"
    );

    if (!$delete) {
        die("Staff Delete Error: " . mysqli_error($conn));
    }

    echo "<script>
            alert('Staff Deleted Successfully');
            window.location='staff.php';
          </script>";
    exit();
}


/* =====================================================
   EDIT STAFF
===================================================== */

$edit_staff = null;

if (isset($_GET['edit'])) {

    $id = intval($_GET['edit']);

    $edit_query = mysqli_query(
        $conn,
        "SELECT * FROM staff WHERE id='$id' LIMIT 1"
    );

    if (!$edit_query) {
        die("Edit Database Error: " . mysqli_error($conn));
    }

    if (mysqli_num_rows($edit_query) > 0) {
        $edit_staff = mysqli_fetch_assoc($edit_query);
    }
}


/* =====================================================
   GET ALL STAFF
===================================================== */

$query = mysqli_query(
    $conn,
    "SELECT * FROM staff ORDER BY id DESC"
);

if (!$query) {
    die("Database Error: " . mysqli_error($conn));
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">

<meta name="viewport"
      content="width=device-width, initial-scale=1.0">

<title>Staff Management | Swiffin Cake Shop</title>

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet">

<link
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css"
rel="stylesheet">

<style>

*{
    box-sizing:border-box;
}

body{
    margin:0;
    padding:0;
    background:#000;
    color:#fff;
    font-family:Arial, Helvetica, sans-serif;
}

.main{
    width:95%;
    max-width:1250px;
    margin:40px auto;
}

/* TITLE */

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

/* BOX */

.staff-box{
    background:#111;
    border:1px solid #E88F2A;
    border-radius:18px;
    padding:25px;
    margin-bottom:30px;
    box-shadow:0 0 20px rgba(232,143,42,.20);
}

/* SECTION */

.section-title{
    color:#E88F2A;
    font-size:22px;
    font-weight:bold;
    margin-bottom:20px;
}

/* FORM */

.form-label{
    color:#ccc;
    font-weight:bold;
}

.form-control,
.form-select{
    background:#000 !important;
    color:#fff !important;
    border:1px solid #444;
}

.form-control:focus,
.form-select:focus{
    background:#000 !important;
    color:#fff !important;
    border-color:#E88F2A;
    box-shadow:0 0 8px rgba(232,143,42,.25);
}

.form-control::placeholder{
    color:#777;
}

input[type="number"]{
    color-scheme:dark;
}

/* BUTTONS */

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
    text-decoration:none;
    border:none;
    border-radius:25px;
    padding:11px 25px;
    font-weight:bold;
}

.cancel-btn:hover{
    background:#5c636a;
    color:#fff;
}

/* TABLE */

.table-box{
    background:#111;
    border:1px solid #333;
    border-radius:18px;
    padding:20px;
    overflow:hidden;
}

.table{
    margin:0;
}

.table thead th{
    background:#E88F2A !important;
    color:#fff !important;
    text-align:center;
    vertical-align:middle;
}

.table tbody td{
    background:#1b1b1b !important;
    color:#fff !important;
    text-align:center;
    vertical-align:middle;
    border-color:#333;
}

/* SALARY */

.salary{
    color:#E88F2A;
    font-weight:bold;
    font-size:18px;
}

/* STATUS */

.active{
    color:#28a745;
    font-weight:bold;
}

.inactive{
    color:#dc3545;
    font-weight:bold;
}

/* ACTION */

.edit-btn{
    display:inline-block;
    background:#0d6efd;
    color:#fff;
    text-decoration:none;
    padding:7px 14px;
    border-radius:20px;
    font-weight:bold;
    margin:2px;
}

.edit-btn:hover{
    background:#0b5ed7;
    color:#fff;
}

.delete-btn{
    display:inline-block;
    background:#dc3545;
    color:#fff;
    text-decoration:none;
    padding:7px 14px;
    border-radius:20px;
    font-weight:bold;
    margin:2px;
}

.delete-btn:hover{
    background:#bb2d3b;
    color:#fff;
}

/* BACK */

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

</style>

</head>

<body>

<div class="main">

<!-- TITLE -->

<div class="page-title">

<h2>
<i class="fas fa-users"></i>
&nbsp; Staff Management
</h2>

<p>
Add, edit and manage Swiffin Cake Shop staff
</p>

</div>


<!-- ADD / EDIT STAFF -->

<div class="staff-box">

<div class="section-title">

<?php if ($edit_staff) { ?>

<i class="fas fa-user-edit"></i>
&nbsp; Edit Staff

<?php } else { ?>

<i class="fas fa-user-plus"></i>
&nbsp; Add New Staff

<?php } ?>

</div>


<form method="POST">

<?php if ($edit_staff) { ?>

<input
type="hidden"
name="id"
value="<?php echo intval($edit_staff['id']); ?>"
>

<?php } ?>


<div class="row g-3">

<!-- NAME -->

<div class="col-md-6">

<label class="form-label">
Staff Name
</label>

<input
type="text"
name="name"
class="form-control"
placeholder="Enter staff name"
value="<?php
echo $edit_staff
    ? htmlspecialchars($edit_staff['name'])
    : '';
?>"
required>

</div>


<!-- EMAIL -->

<div class="col-md-6">

<label class="form-label">
Email
</label>

<input
type="email"
name="email"
class="form-control"
placeholder="Enter email"
value="<?php
echo $edit_staff
    ? htmlspecialchars($edit_staff['email'])
    : '';
?>"
required>

</div>


<!-- MOBILE -->

<div class="col-md-4">

<label class="form-label">
Mobile
</label>

<input
type="text"
name="mobile"
class="form-control"
placeholder="Enter mobile number"
value="<?php
echo $edit_staff
    ? htmlspecialchars($edit_staff['mobile'])
    : '';
?>"
required>

</div>


<!-- POSITION -->

<div class="col-md-4">

<label class="form-label">
Position
</label>

<select
name="position"
class="form-select"
required>

<option value="">
-- Select Position --
</option>

<option value="Baker"
<?php
if ($edit_staff && $edit_staff['position']=="Baker") {
    echo "selected";
}
?>>
Baker
</option>

<option value="Cashier"
<?php
if ($edit_staff && $edit_staff['position']=="Cashier") {
    echo "selected";
}
?>>
Cashier
</option>

<option value="Sales Staff"
<?php
if ($edit_staff && $edit_staff['position']=="Sales Staff") {
    echo "selected";
}
?>>
Sales Staff
</option>

<option value="Manager"
<?php
if ($edit_staff && $edit_staff['position']=="Manager") {
    echo "selected";
}
?>>
Manager
</option>

<option value="Other"
<?php
if ($edit_staff && $edit_staff['position']=="Other") {
    echo "selected";
}
?>>
Other
</option>

</select>

</div>


<!-- SALARY -->

<div class="col-md-4">

<label class="form-label">
Salary
</label>

<input
type="number"
name="salary"
class="form-control"
placeholder="Enter salary"
min="0"
value="<?php
echo $edit_staff
    ? htmlspecialchars($edit_staff['salary'])
    : '';
?>"
required>

</div>


<!-- STATUS -->

<div class="col-md-4">

<label class="form-label">
Status
</label>

<select
name="status"
class="form-select"
required>

<option value="Active"
<?php
if (!$edit_staff || $edit_staff['status']=="Active") {
    echo "selected";
}
?>>
Active
</option>

<option value="Inactive"
<?php
if ($edit_staff && $edit_staff['status']=="Inactive") {
    echo "selected";
}
?>>
Inactive
</option>

</select>

</div>


<!-- BUTTON -->

<div class="col-12">

<?php if ($edit_staff) { ?>

<button
type="submit"
name="update_staff"
class="update-btn">

<i class="fas fa-save"></i>
&nbsp; Update Staff

</button>

<a
href="staff.php"
class="cancel-btn ms-2">

<i class="fas fa-times"></i>
&nbsp; Cancel

</a>

<?php } else { ?>

<button
type="submit"
name="add_staff"
class="add-btn">

<i class="fas fa-plus"></i>
&nbsp; Add Staff

</button>

<?php } ?>

</div>

</div>

</form>

</div>


<!-- ALL STAFF -->

<div class="section-title">

<i class="fas fa-list"></i>
&nbsp; All Staff

</div>


<div class="table-box">

<div class="table-responsive">

<table class="table table-bordered table-hover">

<thead>

<tr>

<th>ID</th>
<th>Name</th>
<th>Email</th>
<th>Mobile</th>
<th>Position</th>
<th>Salary</th>
<th>Status</th>
<th>Action</th>

</tr>

</thead>


<tbody>

<?php

if (mysqli_num_rows($query) > 0) {

    while ($row = mysqli_fetch_assoc($query)) {

?>

<tr>

<td>
<?php echo intval($row['id']); ?>
</td>


<td>

<strong>

<?php
echo htmlspecialchars($row['name']);
?>

</strong>

</td>


<td>

<?php
echo htmlspecialchars($row['email']);
?>

</td>


<td>

<?php
echo htmlspecialchars($row['mobile']);
?>

</td>


<td>

<?php
echo htmlspecialchars($row['position']);
?>

</td>


<td>

<span class="salary">

₹<?php
echo number_format(
    floatval($row['salary']),
    2
);
?>

</span>

</td>


<td>

<?php

if ($row['status'] == "Active") {

    echo '<span class="active">Active</span>';

} else {

    echo '<span class="inactive">Inactive</span>';

}

?>

</td>


<td>

<a
href="staff.php?edit=<?php echo intval($row['id']); ?>"
class="edit-btn">

<i class="fas fa-edit"></i>
Edit

</a>


<a
href="staff.php?delete=<?php echo intval($row['id']); ?>"
class="delete-btn"
onclick="return confirm('Are you sure you want to delete this staff member?');">

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

<td colspan="8">

No Staff Records Found

</td>

</tr>

<?php

}

?>

</tbody>

</table>

</div>

</div>


<!-- BACK BUTTON -->

<div class="text-center">

<a
href="admin_dashboard.php"
class="back-btn">

<i class="fas fa-arrow-left"></i>
&nbsp; Back To Dashboard

</a>

</div>

</div>

</body>

</html>
