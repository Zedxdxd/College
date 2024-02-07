<!DOCTYPE html>
<html lang="en">

<head>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&display=swap" rel="stylesheet">
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="viewport" content="initial-scale=1, maximum-scale=1">
    <title>ePutuj|Admin</title>
    <meta name="keywords" content="">
    <meta name="description" content="">
    <meta name="author" content="">
    <link rel="stylesheet" href="<?php echo base_url('anja/css/bootstrap.min.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('anja/css/style.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('anja/css/responsive.css') ?>">
    <link rel="icon" href="<?php echo base_url('images/fevicon.png') ?>" type="image/gif" />
    <link rel="stylesheet" href="<?php echo base_url('anja/css/jquery.mCustomScrollbar.min.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('anja/css/owl.carousel.min.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('anja/css/owl.theme.default.min.css') ?>">

    <link rel="stylesheet" href="<?php echo base_url('zeljko/css/style.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('lana/css/korisnik.css') ?>">

</head>

<body class="main-layout" style="background-color: white">
    <header>
        <div class="header">
            <div class="header_white_section">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="header_information" style="height: 60px;">
                                <table>
                                    <!-- INFO -->
                                    <tr>
                                        <td>
                                            <div class="dropdown dugme" style="background-color: white; top: 0; position: absolute; left: 650px">
                                                <button class="btn btn-primary" style="background-color: rgb(242, 243, 223); margin-left: 50px;" data-bs-toggle="collapse" data-bs-target="#profil_lista">
                                                    <img src="<?php echo base_url('images/user.png') ?>" alt="#" style="width: 0.75cm;" /> <span style="color: black;">admin</span>
                                                </button>
                                                <div id="profil_lista" class="collapse">
                                                    <ul class="dropdown-menu">
                                                        <li><a href="<?php echo base_url('AdminController/logout') ?>" class="dropdown-item">Log out</a></li>
                                                    </ul>
                                                </div>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- NAVIGACIJA -->
            <div class="container">
                <div class="row">
                    <div class="col-xl-3 col-lg-3 col-md-3 col-sm-3 col logo_section">
                        <div class="full">
                            <div class="center-desk">
                                <div class="logo"> <a href="<?php echo base_url('AdminController/index') ?>"><img src="<?php echo base_url('images/logo.jfif') ?>" style="height:50%; width:50%" alt="#"></a> </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9">
                        <div class="menu-area">
                            <div class="limit-box">
                                <nav class="main-menu">
                                    <ul class="menu-area-main" style="padding-top:5%">

                                       <?php echo '<li ';if($broj==1){echo "class='active'";}echo '>';?><a href="<?php echo base_url('AdminController/index') ?>">Potvrda registracija <?php  if($nalog!=0)echo '<span class="badge bg-danger">'.$nalog.'</span>'?></a> </li>
                                       <?php echo '<li ';if($broj==2){echo "class='active'";} echo '>';?><a href="<?php echo base_url('AdminController/ukloniNalog') ?>">Ukloni nalog <?php  if($brisanje!=0)echo '<span class="badge bg-danger">'.$brisanje.'</span>'?></a>
                                        </li>
                                        <?php echo '<li ';if($broj==3){echo "class='active'";} echo '>';?><a href="<?php echo base_url('AdminController/dodajMesto') ?>">Dodaj mesto</a></li>

                                    </ul>
                                </nav>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </header>