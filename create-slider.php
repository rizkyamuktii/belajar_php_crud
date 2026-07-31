<?php
session_start();
session_regenerate_id();

include 'config/koneksi.php';
if (!isset($_SESSION['NAME'])) {
  header("location:index.php");
  exit();
}

$id = isset($_GET['edit']) ? $_GET['edit'] : '';
$query = mysqli_query($conn, "SELECT * FROM sliders WHERE id ='$id'");
$row = mysqli_fetch_assoc($query);

//jika tombol save di tekan 
if (isset($_POST['save'])) {
  $title = $_POST['title'];
  $subtitle = $_POST['subtitle'];
  $description = $_POST['description'];
  $button1_text = $_POST['button1_text'];
  $button1_link = $_POST['button1_link'];
  $button2_text = $_POST['button2_text'];
  $button2_link = $_POST['button2_link'];
  $image = $_FILES['image'];
  $is_active = $_POST['is_active'];

  if ($image['error'] == 0) {
    $filename = uniqid() . "_" . basename($image['name']);
    $filepath = "assets/img/" . $filename;

    if ($id && !empty($row['image'])) {
      $old_picture_path = "assets/img/" . $row['image'];
      if (file_exists($old_picture_path)){
        unlink($old_picture_path);
      }
    }
    move_uploaded_file($image['tmp_name'], $filepath);

    //masukkan ke dalam users sebutkan kolom di table user nilainya diambil dari user nginput
    if ($id) {
      //query update
      $update = mysqli_query($conn, "UPDATE sliders SET title='$title', subtitle='$subtitle', button1_text='$button1_text', button1_link='$button1_link', button2_text='$button2_text', button2_link='$button2_link', image='$filename', description='$description', is_active='$is_active' WHERE id='$id'");
      header("location:slider.php?update=berhasil");
    } else {
      $insert = mysqli_query($conn, "INSERT INTO sliders (title, subtitle, button1_text, button1_link, button2_text, button2_link, image, description, is_active) 
            VALUES ('$title', '$subtitle', '$button1_text', '$button1_link', '$button2_text', '$button2_link', '$filename', '$description', '$is_active')");
      header("location:slider.php?tambah=berhasil");
    }
  }
  $update = mysqli_query($conn, "UPDATE sliders SET title='$title', subtitle='$subtitle', button1_text='$button1_text', button1_link='$button1_link', button2_text='$button2_text', button2_link='$button2_link', description='$description', is_active='$is_active' WHERE id='$id'");
  header("location:slider.php?update=berhasil");
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta http-equiv="X-UA-Compatible" content="IE=edge" />
  <title>Kaiadmin - Bootstrap 5 Admin Dashboard</title>
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
              <img src="assets/img/kaiadmin/logo_light.svg" alt="navbar brand" class="navbar-brand" height="20" />
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
              <h3 class="fw-bold mb-3"><?php echo isset($_GET['edit']) ? 'Edit User' : 'Create New User' ?></h3>
            </div>

          </div>
          <div class="row">
            <div class="col-sm-6 col-md-12">
              <div class="card">
                <div class="card-body">
                  <form action="" method="post" enctype="multipart/form-data">
                    <div class="mb-3">
                      <label for="" class="form-label fw-bold">Title</label>
                      <input type="text" class="form-control" name="title" placeholder="Enter Title" required
                        value="<?= ($id) ? $row['title'] : '' ?>">
                    </div>
                    <div class="mb-3">
                      <label for="" class="form-label fw-bold">Subtitle</label>
                      <input type="text" class="form-control" name="subtitle" placeholder="Enter Subtitle"
                        value="<?= ($id) ? $row['subtitle'] : '' ?>">
                    </div>
                    <div class="mb-3">
                      <label for="" class="form-label fw-bold">Button 1 Text</label>
                      <input type="text" class="form-control" name="button1_text" placeholder="Enter button 1 text"
                        value="<?= ($id) ? $row['button1_text'] : '' ?>">
                    </div>
                    <div class="mb-3">
                      <label for="" class="form-label fw-bold">Button 1 Link</label>
                      <input type="url" class="form-control" name="button1_link" placeholder="Enter button 1 link"
                        value="<?= ($id) ? $row['button1_link'] : '' ?>">
                    </div>
                    <div class="mb-3">
                      <label for="" class="form-label fw-bold">Button 2 Text</label>
                      <input type="text" class="form-control" name="button2_text" placeholder="Enter button 2 text"
                        value="<?= ($id) ? $row['button2_text'] : '' ?>">
                    </div>
                    <div class="mb-3">
                      <label for="" class="form-label fw-bold">Button 2 Link</label>
                      <input type="url" class="form-control" name="button2_link" placeholder="Enter button 2 link"
                        value="<?= ($id) ? $row['button2_link'] : '' ?>">
                    </div>
                    <div class="mb-3">
                      <label for="" class="form-label fw-bold">Image</label>
                      <input type="file" class="form-control" name="image" value="<?= ($id) ? $row['image'] : '' ?>">
                    </div>
                    <div class="mb-3">
                      <label for="" class="form-label fw-bold">Description</label>
                      <textarea class="form-control" name="description" cols="30"
                        rows="3"><?= ($id) ? $row['description'] : "" ?></textarea>
                    </div>
                    <div class="mb-3">
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="is_active" id="radioDefault1" value="1"
                          checked <?= ($id) && $row['is_active'] == 1 ? "checked" : '' ?>>
                        <label class="form-check-label" for="radioDefault1">
                          Active
                        </label>
                      </div>
                      <div class="form-check">
                        <input class="form-check-input" type="radio" name="is_active" id="radioDefault2" value="0"
                          <?= ($id) && $row['is_active'] == 0 ? "checked" : '' ?>>
                        <label class="form-check-label" for="radioDefault2">
                          In-Active
                        </label>
                      </div>
                    </div>
                    <div class="mb-3">
                      <button class="btn btn-primary" name="save" type="submit">
                        Save
                      </button>

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


  </div>
  <?php
  include "inc/js.php";
  ?>
</body>

</html>