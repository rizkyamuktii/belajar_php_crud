<?php
session_start();
session_regenerate_id();

include 'config/koneksi.php';
if (!isset($_SESSION['NAME'])) {
    header("location:index.php");
    exit();
}

$id = isset($_GET['edit']) ? $_GET['edit'] : '';
$query = mysqli_query($conn, "SELECT * FROM sliders WHERE id='$id'");
$row = mysqli_fetch_assoc($query);

// Jika Tombol save ditekan
if (isset($_POST['save'])) {
    $title = mysqli_real_escape_string($conn, $_POST['title']);
    $subtitle = mysqli_real_escape_string($conn, $_POST['subtitle']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $button1_text = mysqli_real_escape_string($conn, $_POST['button1_text']);
    $button1_link = mysqli_real_escape_string($conn, $_POST['button1_link']);
    $button2_text = mysqli_real_escape_string($conn, $_POST['button2_text']);
    $button2_link = mysqli_real_escape_string($conn, $_POST['button2_link']);
    $image = $_FILES['image'];

    if ($image['eror'] == 0) {
        $filename = basename($image['name']);
        $filepath = "assets/img/" . $filename;
        move_uploaded_file($image['tmp_name'], $filepath);
    }


    // File upload handling
    $image = isset($row['image']) ? $row['image'] : '';
    if (isset($_FILES['image']['name']) && $_FILES['image']['name'] != '') {
        $filename = $_FILES['image']['name'];
        $ext = pathinfo($filename, PATHINFO_EXTENSION);
        $allowed = ['jpg', 'jpeg', 'png', 'webp'];

        if (in_array(strtolower($ext), $allowed)) {
            $image = time() . '_' . $filename;
            move_uploaded_file($_FILES['image']['tmp_path'], 'assets/img/sliders/' . $image);
        }
    }

    if ($id) {
        // Query update
        $edit = mysqli_query($conn, "UPDATE sliders SET 
            title='$title', 
            subtitle='$subtitle', 
            button1_text='$button1_text', 
            button1_link='$button1_link', 
            button2_text='$button2_text', 
            button2_link='$button2_link', 
            image='$image', 
            description='$description' 
            WHERE id='$id'");
        header("location:slider.php?update=berhasil");
        exit();
    } else {
        // Query insert
        $insert = mysqli_query($conn, "INSERT INTO sliders (title, subtitle,description, button1_text, button1_link, button2_text, image, button2_link) 
            VALUES ('$title', '$subtitle','$description', '$button1_text', '$button1_link', '$button2_text','$image', '$button2_link')");
        header("location:slider.php?tambah=berhasil");
        exit();
    }
}
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>PROJECT CRUD3 - Management Sliders</title>
    <meta content="width=device-width, initial-scale=1.0, shrink-to-fit=no" name="viewport" />
    <?php
    include "inc/css.php";
    ?>

</head>

<body>
    <div class="wrapper">
        <!-- Sidebar -->
        <?php
        include "inc/sidebar.php";
        ?>
        <!-- End Sidebar -->

        <div class="main-panel">
            <div class="main-header">
                <div class="main-header-logo">
                    <!-- Logo Header -->
                    <div class="logo-header" data-background-color="dark">
                        <a href="index.html" class="logo">
                            <img src="assets/img/kaiadmin/logo_light.svg" alt="navbar brand" class="navbar-brand"
                                height="20" />
                        </a>
                        <div class="nav-toggle">
                            <button class="btn btn-toggle toggle-sidebar">
                                <i class="gg-menu-right"></i>
                            </button>
                            <button class="btn btn-toggle sidenav-toggler">
                                <i class="gg-menu-left"></i>
                            </button>
                        </div>
                        <button class="topbar-toggler more">
                            <i class="gg-more-vertical-alt"></i>
                        </button>
                    </div>
                    <!-- End Logo Header -->
                </div>
                <!-- Navbar Header -->
                <?php
                include "inc/navbar.php";
                ?>
                <!-- End Navbar -->
            </div>

            <div class="container">
                <div class="page-inner">
                    <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row pt-2 pb-4">
                        <div>
                            <h3 class="fw-bold mb-3">
                                <?php echo isset($_GET['edit']) ? 'Edit slider' : 'Create New slider'; ?></h3>
                            <h6 class="op-7 mb-2"><?php echo isset($_GET['edit']) ? 'Edit Nih' : 'Baru Nih'; ?></h6>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-md-12">
                            <div class="card">
                                <div class="card-body">

                                    <form action="" method="post" enctype="multipart/form-data">
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label for="title" class="form-label fw-bold">Title</label>
                                                    <input type="text" name="title" class="form-control" required
                                                        value="<?php echo isset($row['title']) ? $row['title'] : '' ?>">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label for="subtitle" class="form-label fw-bold">Subtitle</label>
                                                    <input type="text" name="subtitle" class="form-control" required
                                                        value="<?php echo isset($row['subtitle']) ? $row['subtitle'] : '' ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label for="button1_text" class="form-label fw-bold">Button 1 -
                                                        Text</label>
                                                    <input type="text" name="button1_text" class="form-control" required
                                                        value="<?php echo isset($row['button1_text']) ? $row['button1_text'] : '' ?>">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="button1_link" class="form-label fw-bold">Button 1 -
                                                        Link</label>
                                                    <input type="text" name="button1_link" class="form-control"
                                                        value="<?php echo isset($row['button1_link']) ? $row['button1_link'] : '' ?>">
                                                </div>
                                            </div>
                                            <div class="col-sm-6">
                                                <div class="mb-3">
                                                    <label for="button2_text" class="form-label fw-bold">Button 2 -
                                                        Text</label>
                                                    <input type="text" name="button2_text" class="form-control" required
                                                        value="<?php echo isset($row['button2_text']) ? $row['button2_text'] : '' ?>">
                                                </div>
                                                <div class="mb-3">
                                                    <label for="button2_link" class="form-label fw-bold">Button 2 -
                                                        Link</label>
                                                    <input type="text" name="button2_link" class="form-control"
                                                        value="<?php echo isset($row['button2_link']) ? $row['button2_link'] : '' ?>">
                                                </div>
                                            </div>
                                        </div>
                                        <div class="row">
                                            <div class="col-lg-12">
                                                <div class="mb-3">
                                                    <label for="image" class="form-label fw-bold">Image</label>
                                                    <input type="file" class="form-control" name="image">
                                                    <?php if ($id && !empty($row['image'])): ?>
                                                        <small class="text-muted">Current file:
                                                            <?php echo $row['image']; ?></small>
                                                    <?php endif; ?>
                                                </div>
                                                <div class="mb-3">
                                                    <label for="description"
                                                        class="form-label fw-bold">Description</label>
                                                    <textarea name="description" class="form-control"
                                                        rows="3"><?php echo isset($row['description']) ? $row['description'] : ''; ?></textarea>
                                                </div>
                                                <button class="btn btn-primary" type="submit" name="save">Save</button>
                                            </div>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <footer class="footer">
                <div class="container-fluid d-flex justify-content-between">
                    <nav class="pull-left">
                        <ul class="nav">
                            <li class="nav-item">
                                <a class="nav-link" href="http://www.themekita.com">
                                    ThemeKita
                                </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#"> Help </a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="#"> Licenses </a>
                            </li>
                        </ul>
                    </nav>
                    <div class="copyright">
                        2024, made with <i class="fa fa-heart heart text-danger"></i> by
                        <a href="http://www.themekita.com">ThemeKita</a>
                    </div>
                    <div>
                        Distributed by
                        <a target="_blank" href="https://themewagon.com/">ThemeWagon</a>.
                    </div>
                </div>
            </footer>
        </div>

        <!-- Custom template | don't include it in your project! -->
    </div>
    <?php
    include "inc/js.php";
    ?>
</body>

</html>