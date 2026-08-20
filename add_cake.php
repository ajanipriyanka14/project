

<?php

session_start();

include("config.php");


/* =====================================================
   ADMIN LOGIN CHECK
===================================================== */

if (!isset($_SESSION['admin_id'])) {

    header("Location: admin.php");
    exit();

}


/* =====================================================
   ADMIN NAME
===================================================== */

$admin_name = $_SESSION['admin_name'] ?? "Admin";


/* =====================================================
   MESSAGE
===================================================== */

$message = "";
$message_type = "";


/* =====================================================
   ADD CAKE
===================================================== */

if (isset($_POST['save'])) {

    $cake_name   = trim($_POST['cake_name'] ?? "");
    $category    = trim($_POST['category'] ?? "");
    $price       = floatval($_POST['price'] ?? 0);
    $flavour     = trim($_POST['flavour'] ?? "");
    $weight      = trim($_POST['weight'] ?? "");
    $description = trim($_POST['description'] ?? "");
    $stock       = intval($_POST['stock'] ?? 0);
    $status      = trim($_POST['status'] ?? "Available");


    /* =================================================
       BASIC VALIDATION
    ================================================= */

    if (
        $cake_name == "" ||
        $category == "" ||
        $price <= 0 ||
        $flavour == "" ||
        $weight == "" ||
        $description == ""
    ) {

        $message = "Please fill all required fields.";
        $message_type = "danger";

    }

    elseif (!isset($_FILES['image']) ||
            $_FILES['image']['error'] != 0) {

        $message = "Please select a cake image.";
        $message_type = "danger";

    }

    else {

        $image = $_FILES['image']['name'];
        $temp  = $_FILES['image']['tmp_name'];
        $size  = $_FILES['image']['size'];


        /* =============================================
           ALLOWED EXTENSIONS
        ============================================= */

        $allowed = [
            "jpg",
            "jpeg",
            "png",
            "webp"
        ];

        $extension = strtolower(
            pathinfo($image, PATHINFO_EXTENSION)
        );


        /* =============================================
           IMAGE SIZE
        ============================================= */

        if ($size > 5 * 1024 * 1024) {

            $message =
                "Image size must be less than 5 MB.";

            $message_type = "danger";

        }

        elseif (!in_array($extension, $allowed)) {

            $message =
                "Only JPG, JPEG, PNG and WEBP images are allowed.";

            $message_type = "danger";

        }

        else {


            /* =========================================
               UNIQUE IMAGE NAME
            ========================================= */

            $safe_name = preg_replace(
                "/[^A-Za-z0-9._-]/",
                "_",
                $image
            );

            $new_image =
                time() . "_" .
                uniqid() . "_" .
                $safe_name;


            $upload_path =
                "img/" . $new_image;


            /* =========================================
               UPLOAD IMAGE
            ========================================= */

            if (move_uploaded_file($temp, $upload_path)) {


                /* =====================================
                   ESCAPE DATA
                ===================================== */

                $cake_name =
                    mysqli_real_escape_string(
                        $conn,
                        $cake_name
                    );

                $category =
                    mysqli_real_escape_string(
                        $conn,
                        $category
                    );

                $flavour =
                    mysqli_real_escape_string(
                        $conn,
                        $flavour
                    );

                $weight =
                    mysqli_real_escape_string(
                        $conn,
                        $weight
                    );

                $description =
                    mysqli_real_escape_string(
                        $conn,
                        $description
                    );

                $status =
                    mysqli_real_escape_string(
                        $conn,
                        $status
                    );

                $new_image =
                    mysqli_real_escape_string(
                        $conn,
                        $new_image
                    );


                /* =====================================
                   INSERT CAKE
                ===================================== */

                $query = "

                    INSERT INTO cake

                    (
                        cake_name,
                        category,
                        price,
                        flavour,
                        weight,
                        image,
                        description,
                        stock,
                        status
                    )

                    VALUES

                    (
                        '$cake_name',
                        '$category',
                        '$price',
                        '$flavour',
                        '$weight',
                        '$new_image',
                        '$description',
                        '$stock',
                        '$status'
                    )

                ";


                if (mysqli_query($conn, $query)) {

                    echo "

                    <script>

                        alert(
                            'Cake Added Successfully!'
                        );

                        window.location =
                            'view_cake.php';

                    </script>

                    ";

                    exit();

                }

                else {

                    /* Delete uploaded image if DB fails */

                    if (file_exists($upload_path)) {

                        unlink($upload_path);

                    }

                    $message =
                        "Database Error: " .
                        mysqli_error($conn);

                    $message_type = "danger";

                }

            }

            else {

                $message =
                    "Cake image upload failed.";

                $message_type = "danger";

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

<title>Add Cake | SWIFFIN Admin</title>


<!-- BOOTSTRAP -->

<link
href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
rel="stylesheet"
>


<!-- BOOTSTRAP ICONS -->

<link
href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css"
rel="stylesheet"
>


<style>

/* =====================================================
   BODY
===================================================== */

body{

    margin:0;

    background:#050505;

    color:#fff;

    font-family:
        Arial,
        Helvetica,
        sans-serif;

}


/* =====================================================
   HEADER
===================================================== */

.admin-header{

    height:72px;

    background:#111;

    border-bottom:1px solid #292929;

    display:flex;

    align-items:center;

    justify-content:space-between;

    padding:0 4%;

    position:sticky;

    top:0;

    z-index:1000;

}


.logo{

    color:#E88F2A;

    font-size:25px;

    font-weight:bold;

    letter-spacing:2px;

}


.logo i{

    margin-right:8px;

}


.admin-user{

    display:flex;

    align-items:center;

    gap:10px;

    color:#ccc;

}


.admin-user i{

    color:#E88F2A;

    font-size:22px;

}


/* =====================================================
   MAIN
===================================================== */

.main-container{

    width:94%;

    max-width:1250px;

    margin:40px auto 60px;

}


/* =====================================================
   HEADING
===================================================== */

.page-heading{

    margin-bottom:30px;

}


.page-heading h1{

    font-size:32px;

    font-weight:bold;

    margin-bottom:8px;

}


.page-heading h1 i{

    color:#E88F2A;

}


.page-heading p{

    color:#888;

    margin:0;

}


/* =====================================================
   FORM GRID
===================================================== */

.form-layout{

    display:grid;

    grid-template-columns:
        minmax(0,2fr)
        minmax(280px,1fr);

    gap:25px;

}


/* =====================================================
   BOX
===================================================== */

.admin-box{

    background:#111;

    border:1px solid #292929;

    border-radius:18px;

    padding:28px;

    box-shadow:
        0 0 25px
        rgba(232,143,42,.08);

}


.admin-box:hover{

    border-color:#3b3b3b;

}


/* =====================================================
   BOX TITLE
===================================================== */

.box-title{

    color:#E88F2A;

    font-size:19px;

    font-weight:bold;

    margin-bottom:25px;

    padding-bottom:15px;

    border-bottom:1px solid #292929;

}


/* =====================================================
   LABEL
===================================================== */

.form-label{

    color:#ddd;

    font-weight:bold;

    font-size:14px;

    margin-bottom:8px;

}


/* =====================================================
   INPUT
===================================================== */

.form-control,
.form-select{

    background:#1a1a1a !important;

    color:#fff !important;

    border:1px solid #3d3d3d;

    border-radius:10px;

    padding:12px 14px;

}


.form-control::placeholder{

    color:#707070;

}


.form-control:focus,
.form-select:focus{

    background:#1a1a1a !important;

    color:#fff !important;

    border-color:#E88F2A;

    box-shadow:
        0 0 0 .15rem
        rgba(232,143,42,.12);

}


.form-select option{

    background:#1a1a1a;

    color:#fff;

}


/* =====================================================
   IMAGE UPLOAD
===================================================== */

.upload-area{

    border:2px dashed #444;

    border-radius:15px;

    padding:30px 20px;

    text-align:center;

    cursor:pointer;

    transition:.3s;

}


.upload-area:hover{

    border-color:#E88F2A;

    background:#181818;

}


.upload-area i{

    font-size:48px;

    color:#E88F2A;

}


.upload-area h6{

    margin-top:12px;

    color:#fff;

}


.upload-area p{

    color:#777;

    font-size:13px;

    margin:5px 0 0;

}


#imageInput{

    display:none;

}


/* =====================================================
   IMAGE PREVIEW
===================================================== */

.preview-box{

    display:none;

    margin-top:18px;

    position:relative;

}


.preview-box img{

    width:100%;

    height:240px;

    object-fit:cover;

    border-radius:14px;

    border:1px solid #444;

}


.preview-label{

    position:absolute;

    top:10px;

    left:10px;

    background:#E88F2A;

    color:#fff;

    padding:5px 10px;

    border-radius:20px;

    font-size:12px;

    font-weight:bold;

}


/* =====================================================
   TIPS
===================================================== */

.tip-card{

    display:flex;

    gap:14px;

    background:#181818;

    border:1px solid #292929;

    border-radius:12px;

    padding:15px;

    margin-bottom:12px;

}


.tip-card i{

    color:#E88F2A;

    font-size:22px;

}


.tip-card h6{

    color:#fff;

    margin:0 0 4px;

}


.tip-card p{

    color:#777;

    font-size:13px;

    margin:0;

}


/* =====================================================
   SAVE BUTTON
===================================================== */

.btn-save{

    width:100%;

    border:none;

    background:#E88F2A;

    color:#fff;

    padding:14px;

    border-radius:30px;

    font-size:17px;

    font-weight:bold;

    transition:.3s;

}


.btn-save:hover{

    background:#fff;

    color:#000;

    transform:translateY(-2px);

}


/* =====================================================
   BOTTOM BUTTONS
===================================================== */

.action-buttons{

    display:grid;

    grid-template-columns:1fr 1fr;

    gap:12px;

    margin-top:18px;

}


.btn-back{

    text-decoration:none;

    text-align:center;

    color:#ccc;

    border:1px solid #3b3b3b;

    padding:12px;

    border-radius:25px;

    font-weight:bold;

    transition:.3s;

}


.btn-back:hover{

    color:#E88F2A;

    border-color:#E88F2A;

    background:#181818;

}


/* =====================================================
   REQUIRED
===================================================== */

.required{

    color:#E88F2A;

}


/* =====================================================
   MOBILE
===================================================== */

@media(max-width:900px){

    .form-layout{

        grid-template-columns:1fr;

    }

}


@media(max-width:600px){

    .admin-header{

        padding:0 20px;

    }

    .admin-user span{

        display:none;

    }

    .main-container{

        width:92%;

        margin-top:25px;

    }

    .page-heading h1{

        font-size:26px;

    }

    .admin-box{

        padding:20px;

    }

    .action-buttons{

        grid-template-columns:1fr;

    }

}

</style>

</head>


<body>


<!-- =====================================================
     HEADER
===================================================== -->

<header class="admin-header">


    <div class="logo">

        <i class="bi bi-cake2-fill"></i>

        SWIFFIN

    </div>


    <div class="admin-user">

        <i class="bi bi-person-circle"></i>

        <span>

            Welcome,
            <?php echo htmlspecialchars($admin_name); ?>

        </span>

    </div>


</header>



<!-- =====================================================
     MAIN
===================================================== -->

<div class="main-container">


    <!-- HEADING -->

    <div class="page-heading">

        <h1>

            <i class="bi bi-plus-circle-fill"></i>

            Add New Cake

        </h1>


        <p>

            Add a new product to your SWIFFIN Cake Shop.

        </p>

    </div>



    <!-- MESSAGE -->

    <?php if ($message != "") { ?>

        <div
            class="alert
            alert-<?php echo $message_type; ?>
            alert-dismissible fade show"
        >

            <?php
            echo htmlspecialchars($message);
            ?>


            <button
                type="button"
                class="btn-close"
                data-bs-dismiss="alert"
            ></button>

        </div>

    <?php } ?>



    <!-- =================================================
         FORM
    ================================================= -->

    <form
        method="POST"
        enctype="multipart/form-data"
    >


        <div class="form-layout">


            <!-- =========================================
                 LEFT
            ========================================= -->

            <div class="admin-box">


                <div class="box-title">

                    <i class="bi bi-info-circle-fill"></i>

                    Cake Information

                </div>



                <!-- CAKE NAME -->

                <div class="mb-4">

                    <label class="form-label">

                        Cake Name
                        <span class="required">*</span>

                    </label>


                    <input
                        type="text"
                        name="cake_name"
                        class="form-control"
                        placeholder="Chocolate Truffle Cake"
                        required
                    >

                </div>



                <!-- CATEGORY + FLAVOUR -->

                <div class="row">


                    <div class="col-md-6 mb-4">

                        <label class="form-label">

                            Category
                            <span class="required">*</span>

                        </label>


                        <select
                            name="category"
                            class="form-select"
                            required
                        >

                            <option value="">

                                Select Category

                            </option>

                            <option>
                                Birthday Cake
                            </option>

                            <option>
                                Wedding Cake
                            </option>

                            <option>
                                Anniversary Cake
                            </option>

                            <option>
                                Chocolate Cake
                            </option>

                            <option>
                                Photo Cake
                            </option>

                            <option>
                                Designer Cake
                            </option>

                        </select>

                    </div>



                    <div class="col-md-6 mb-4">

                        <label class="form-label">

                            Flavour
                            <span class="required">*</span>

                        </label>


                        <select
                            name="flavour"
                            class="form-select"
                            required
                        >

                            <option value="">

                                Select Flavour

                            </option>

                            <option>Chocolate</option>

                            <option>Vanilla</option>

                            <option>Strawberry</option>

                            <option>Butterscotch</option>

                            <option>Red Velvet</option>

                            <option>Black Forest</option>

                            <option>Oreo</option>

                        </select>

                    </div>


                </div>



                <!-- PRICE / WEIGHT / STOCK -->

                <div class="row">


                    <div class="col-md-4 mb-4">

                        <label class="form-label">

                            Price (₹)
                            <span class="required">*</span>

                        </label>


                        <input
                            type="number"
                            name="price"
                            class="form-control"
                            placeholder="750"
                            min="1"
                            step="0.01"
                            required
                        >

                    </div>



                    <div class="col-md-4 mb-4">

                        <label class="form-label">

                            Weight
                            <span class="required">*</span>

                        </label>


                        <input
                            type="text"
                            name="weight"
                            class="form-control"
                            placeholder="1 Kg"
                            required
                        >

                    </div>



                    <div class="col-md-4 mb-4">

                        <label class="form-label">

                            Stock
                            <span class="required">*</span>

                        </label>


                        <input
                            type="number"
                            name="stock"
                            class="form-control"
                            placeholder="10"
                            min="0"
                            required
                        >

                    </div>


                </div>



                <!-- DESCRIPTION -->

                <div class="mb-4">

                    <label class="form-label">

                        Description
                        <span class="required">*</span>

                    </label>


                    <textarea
                        name="description"
                        rows="5"
                        class="form-control"
                        placeholder="Write a short description about this cake..."
                        required
                    ></textarea>

                </div>



                <!-- STATUS -->

                <div>

                    <label class="form-label">

                        Product Status

                    </label>


                    <select
                        name="status"
                        class="form-select"
                    >

                        <option value="Available">
                            Available
                        </option>

                        <option value="Out of Stock">
                            Out of Stock
                        </option>

                    </select>

                </div>


            </div>



            <!-- =========================================
                 RIGHT
            ========================================= -->

            <div>


                <!-- IMAGE BOX -->

                <div class="admin-box mb-4">


                    <div class="box-title">

                        <i class="bi bi-image-fill"></i>

                        Product Image

                    </div>



                    <label
                        for="imageInput"
                        class="upload-area"
                    >

                        <i
                            class="bi bi-cloud-arrow-up-fill"
                        ></i>


                        <h6>

                            Upload Cake Image

                        </h6>


                        <p>

                            Click here to select an image

                        </p>


                        <small class="text-secondary">

                            JPG, JPEG, PNG or WEBP • Max 5 MB

                        </small>

                    </label>


                    <input
                        type="file"
                        name="image"
                        id="imageInput"
                        accept=".jpg,.jpeg,.png,.webp"
                        required
                    >



                    <!-- PREVIEW -->

                    <div
                        class="preview-box"
                        id="previewBox"
                    >

                        <span class="preview-label">

                            Image Preview

                        </span>


                        <img
                            id="imagePreview"
                            src=""
                            alt="Cake Preview"
                        >

                    </div>


                </div>



                <!-- PRODUCT TIPS -->

                <div class="admin-box">


                    <div class="box-title">

                        <i class="bi bi-lightbulb-fill"></i>

                        Product Tips

                    </div>



                    <div class="tip-card">

                        <i class="bi bi-camera-fill"></i>

                        <div>

                            <h6>
                                High Quality Image
                            </h6>

                            <p>
                                Use a clear cake image for better presentation.
                            </p>

                        </div>

                    </div>



                    <div class="tip-card">

                        <i class="bi bi-currency-rupee"></i>

                        <div>

                            <h6>
                                Correct Price
                            </h6>

                            <p>
                                Enter the actual selling price.
                            </p>

                        </div>

                    </div>



                    <div class="tip-card">

                        <i class="bi bi-box-seam-fill"></i>

                        <div>

                            <h6>
                                Keep Stock Updated
                            </h6>

                            <p>
                                Update stock quantity whenever products are sold.
                            </p>

                        </div>

                    </div>


                </div>


            </div>


        </div>



        <!-- =============================================
             SAVE AREA
        ============================================= -->

        <div class="admin-box mt-4">


            <button
                type="submit"
                name="save"
                class="btn-save"
            >

                <i class="bi bi-check-circle-fill"></i>

                &nbsp; Add Cake to Shop

            </button>



            <div class="action-buttons">


                <a
                    href="admin_dashboard.php"
                    class="btn-back"
                >

                    <i class="bi bi-arrow-left"></i>

                    Dashboard

                </a>



                <a
                    href="view_cake.php"
                    class="btn-back"
                >

                    <i class="bi bi-grid-3x3-gap-fill"></i>

                    View All Cakes

                </a>


            </div>


        </div>


    </form>


</div>



<!-- BOOTSTRAP JS -->

<script
src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
></script>



<script>

/* =====================================================
   IMAGE PREVIEW
===================================================== */

const imageInput =
    document.getElementById("imageInput");

const previewBox =
    document.getElementById("previewBox");

const imagePreview =
    document.getElementById("imagePreview");


imageInput.addEventListener(
    "change",
    function(){

        const file =
            this.files[0];


        if (!file) {

            previewBox.style.display =
                "none";

            return;

        }


        /* Check size */

        if (
            file.size >
            5 * 1024 * 1024
        ) {

            alert(
                "Image size must be less than 5 MB."
            );

            this.value = "";

            previewBox.style.display =
                "none";

            return;

        }


        /* Preview */

        const reader =
            new FileReader();


        reader.onload =
            function(e){

                imagePreview.src =
                    e.target.result;

                previewBox.style.display =
                    "block";

            };


        reader.readAsDataURL(file);

    }
);

</script>


</body>

</html>

