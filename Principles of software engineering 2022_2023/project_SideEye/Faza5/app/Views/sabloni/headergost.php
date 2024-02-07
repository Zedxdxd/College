<!DOCTYPE html>
<html lang="en">

<head>

   <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
   <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js"></script>

   <meta charset="utf-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1">
   <meta name="viewport" content="initial-scale=1, maximum-scale=1">
   <title>ePutuj</title>
   <meta name="keywords" content="">
   <meta name="description" content="">
   <meta name="author" content="">
   <link rel="stylesheet" href="<?php echo base_url('milica/css/bootstrap.min.css') ?>">
   <link rel="stylesheet" href="<?php echo base_url('milica/css/style.css') ?>">
   <link rel="stylesheet" href="<?php echo base_url('milica/css/responsive.css') ?>">
   <link rel="icon" href="<?php echo base_url('images/fevicon.png') ?>" type="image/gif" />
   <link rel="stylesheet" href="<?php echo base_url('milica/css/jquery.mCustomScrollbar.min.css') ?>">
   <link rel="stylesheet" href="<?php echo base_url('milica/css/owl.carousel.min.css') ?>">
   <link rel="stylesheet" href="<?php echo base_url('milica/css/owl.theme.default.min.css') ?>">

   <script src="<?php echo base_url('milica/js/jquery.min.js') ?>"></script>

</head>

<body class="main-layout background-main">
   <header>
      <div class="header">
         <div class="header_white_section">
            <div class="container-fluid">
               <div class="row">
                  <div class="col-md-12">
                     <div class="header_information">
                        <table>
                           
                           <!-- INFO -->
                           <tr>
                              <td style="width:35%"><img src="<?php echo base_url('images/1.png') ?>" alt="#" /> Bulevar Kralja Aleksandra 73,
                                 Beograd &nbsp;&nbsp;&nbsp;</td>

                              <td style="width:20%"><img src="<?php echo base_url('images/2.png') ?>" alt="#" /> +381 65 432 1010
                                 &nbsp;&nbsp;&nbsp;</td>

                              <td style="width:20%"><img src="<?php echo base_url('images/3.png') ?>" alt="#" />
                                 eputuj@gmail.com&nbsp;&nbsp;&nbsp;</td>

                              <td style="width:25%; padding-right: 10px;" align="right"><a href="<?php echo site_url("GostController/login")?>"><img src="<?php echo base_url('images/Adobe_Express_20230318_1713500_1.png') ?>" style="height:15% ;width:15%" />Uloguj se</a> </td>
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
                        <div class="logo"> <a href="<?php echo base_url('GostController/index') ?>"><img src="<?php echo base_url('images/logo.jpeg') ?>" alt="#"></a> </div>
                     </div>
                  </div>
               </div>
               <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9">
                  <div class="menu-area">
                     <div class="limit-box">
                        <nav class="main-menu">
                           <ul class="menu-area-main">

                              <li id="liPocetna"> <a href="<?= site_url("GostController/index") ?>">Početna</a> </li> <!-- ovde treba prosledjivati sta je aktivno, vrv moze preko js -->
                              <li>                <a href="<?= site_url("GostController/index#onama") ?>">O nama</a> </li>
                              <li id="liPretraga"><a href="<?php echo site_url("GostController/pretragaPonuda")?>">Ponude</a></li>
                              <li id="liKontakt"> <a href="#contact">Kontakt</a></li>

                           </ul>
                        </nav>
                     </div>
                  </div>
               </div>
            </div>
         </div>
      </div>
   </header>

   <script>
        $(document).ready(function(){
            let url = window.location.href;
            if (url.includes("pretragaPonuda")){
                $("#liPretraga").addClass("active");
            }
            else {
                $("#liPocetna").addClass("active");
            }
        });
    </script>