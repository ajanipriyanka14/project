<?php
include("config.php");

$sql = "SELECT * FROM cake";
$result = mysqli_query($conn,$sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>product | SWIFFIN CAKE SHOP</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="Free HTML Templates" name="keywords">
    <meta content="Free HTML Templates" name="description">

    <!-- Favicon -->
    <link href="img/favicon.ico" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600&family=Oswald:wght@500;600;700&family=Pacifico&display=swap" rel="stylesheet"> 

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>
<style>
<body>{
	margin:0;
	padding:0;
}
.container-fluid
{
	margin-top:0!important;
	padding:0!important;
}
</style>
    <!-- Topbar Start -->

	<div class="container-fluid bg-white px-5">
    <div class="row align-items-center">

        <!-- Welcome Left -->
        <div class="col-lg-2">
            <h5 class="m-1 fw-bold">
			<?php
			if(isset($_SESSION['name'])){
               echo "Welcome ". $_SESSION['name']; 
			}
			else
			{
				echo "welcome";
			}
			?>		
            </h5>
        </div>

     <!-- SWIFFIN Center -->
<div class="col-lg-5 text-center  py-2">
    <a href="index1.php" class="navbar-brand d-flex justify-content-center align-items-center">
        <img src="img/logo.png" alt="Logo" width="60" height="60" >
        <h1 class="m-0  ms-2 text-uppercase ">
		<span style ="color:#ff9800;">SWIFFIN</span>
		</h1>
    </a>
</div>
        <!-- CALL US Right -->
        <div class="col-lg-4 text-end">
            <div class="d-inline-flex align-items-center ">
                <i class="bi bi-phone-vibrate fs-1 text-primary me-3"></i>
                <div class="text-start">
                    <h6 class="text-uppercase mb-1">CALL US</h6>
                    <span>+91 9876543210</span>
                </div>
            </div>
        </div>

    </div>
</div>
    <!-- Topbar End -->


    <!-- Navbar Start -->
    <nav class="navbar navbar-expand-lg bg-white navbar-light shadow-sm sticky-top py-3">
    <div class="container">

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbar">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbar">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item"><a href="index1.php" class="nav-link ">Home</a></li>
                <li class="nav-item"><a href="about.php" class="nav-link">About</a></li>
                <li class="nav-item"><a href="product.php" class="nav-link active">Cakes</a></li>
                <li class="nav-item"><a href="gallery.php" class="nav-link">Gallery</a></li>
                <li class="nav-item"><a href="contact.php" class="nav-link">Contact</a></li>
                <li class="nav-item"><a href="login.php" class="btn btn-primary rounded-circle ms-3 px-4">Login</a></li>
				<li class="nav-item"><a href="register.php" class="btn btn-primary  rounded-circle ms-3 px-4">Register</a></li>
				<li class="nav-item"><a href="logout.php" class="btn btn-primary rounded-circle ms-3 px-4">Logout</a></li>
            </ul>
        </div>
    </div>
</nav>
    <!-- Navbar End -->

<style>

body{
    background:#000;
    font-family:Arial, Helvetica, sans-serif;
}

.hero{
    background:#111;
    padding:90px 0;
}

.product-card{
    background:#1b1b1b;
    border-radius:20px;
    overflow:hidden;
    transition:.4s;
    color:#fff;
}

.product-card:hover{
    transform:translateY(-10px);
    box-shadow:0 15px 35px rgba(232,143,42,.35);
}

.product-card img{
    height:260px;
    object-fit:cover;
}

.product-card .card-body{
    padding:25px;
}

.product-card h4{
    color:#E88F2A;
    font-weight:bold;
}

.product-card p{
    color:#ddd;
}

.price{
    color:#E88F2A;
    font-size:28px;
    font-weight:bold;
	}

.btn-order{
    background:#E88F2A;
    color:#fff;
    width:100%;
    border-radius:30px;
    font-weight:bold;
}

.btn-order:hover{
    background:#d67d18;
    color:#fff;
}

</style>

</head>

<body>

<!-- Hero -->

<section class="hero">

<div class="container text-center">

<h5 class="text-warning fw-bold">
OUR PRODUCTS
</h5>

<h1 class="display-4 text-white fw-bold">
Fresh & Delicious Cakes
</h1>

<p class="text-light">
Choose your favourite cake for every celebration.
</p>

</div>

</section>

<!-- Products -->

<section class="py-5">

<div class="container">

<div class="row">

<?php while($row=mysqli_fetch_assoc($result)){ ?>

<div class="col-lg-4 col-md-6 mb-4">

<div class="card product-card border-0 h-100">

<img src="img/<?php echo $row['image']; ?>" class="card-img-top">

<div class="card-body text-center">

<h4>
<?php echo $row['cake_name']; ?>
</h4>

<p>
<b>Category :</b>
<?php echo $row['category']; ?>
</p>

<p>
<b>Flavour :</b>
<?php echo $row['flavour']; ?>
</p>

<p>
<b>Weight :</b>
<?php echo $row['weight']; ?>
</p>

<p>
<?php echo $row['description']; ?>
</p>

<p>
<b>Stock :</b>
<span class="text-warning fw-bold">
<?php echo $row['stock']; ?>
</span>
</p>

<p>
<b>Status :</b>

<?php
if($row['status']=="Available")
{
    echo "<span class='text-success fw-bold'>Available</span>";
}
else
{
    echo "<span class='text-danger fw-bold'>Out of Stock</span>";
}
?>

</p>

<div class="price">
₹<?php echo $row['price']; ?>
</div>

<?php
if($row['stock'] > 0 && $row['status']=="Available")
{
?>

<a href="carts.php?id=<?php echo $row['id']; ?>" class="btn btn-order mt-3 w-100">
    <i class="fas fa-shopping-cart"></i>
    Add To Cart
</a>
<?php
}
else
{
?>

<button class="btn btn-danger mt-3 w-100" disabled>
Out of Stock
</button>

<?php
}
?>

</div>

</div>

</div>

<?php } ?>

</div>

</div>

</section>
</div>

</div>

</section>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>