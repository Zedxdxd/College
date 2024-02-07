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
    <title>ePutuj|Korisnik</title>
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
    <link rel="stylesheet" href="<?php echo base_url('lana/css/pozadina.css') ?>">
    <link rel="stylesheet" href="<?php echo base_url('lana/css/korisnik.css') ?>">

    <script src="<?php echo base_url('milica/js/jquery.min.js') ?>"></script>
</head>

<body class="main-layout">
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
                                        <td><img src="<?php echo base_url('images/1.png') ?>" alt="#" /> Bulevar Kralja Aleksandra 73, Beograd
                                            &nbsp; &nbsp;</td>
                                        <td><img src="<?php echo base_url('images/2.png') ?>" alt="#" /> +381 65 432 1010 &nbsp; &nbsp;</td>
                                        <td><img src="<?php echo base_url('images/3.png') ?>" alt="#" /> eputuj@gmail.com &nbsp; &nbsp;</td>
                                        <td>
                                            <div class="dropdown dugme" style="background-color: white; top: 0; position: absolute;">
                                                <button class="btn btn-primary" style="background-color: rgb(242, 243, 223); margin-left: 50px;" data-bs-toggle="collapse" data-bs-target="#profil_lista">
                                                    <img src="<?php echo base_url('images/user.png') ?>" alt="#" style="width: 0.75cm;" /> <span style="color: black;"><?= session()->get("korisnik")->KorisnickoIme ?></span>
                                                </button>
                                                <div id="profil_lista" class="collapse">
                                                    <ul class="dropdown-menu">
                                                        <li><a href="<?=base_url("KorisnikController/izmenaProfila")?>" class="dropdown-item">Izmeni
                                                                profil</a></li>
                                                        <li><a href="<?php echo base_url('KorisnikController/logout') ?>" class="dropdown-item">Log out</a></li>
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
                                <div class="logo"> <a href="<?php echo base_url('KorisnikController/index') ?>"><img src="<?php echo base_url('images/logo.jfif') ?>" style="height:50%; width:50%" alt="#"></a> </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-xl-9 col-lg-9 col-md-9 col-sm-9">
                        <div class="menu-area">
                            <div class="limit-box">
                                <nav class="main-menu">
                                    <ul class="menu-area-main">


                                        <li id="liPocetnaK"><a href="<?php echo base_url('KorisnikController/index') ?>">Početna</a></li>
                                        <li><a href="#contact">Kontakt</a></li>
                                        <li id="liInboxK"> <a href="<?php echo base_url('KorisnikController/inboxKorisnik') ?>">Inbox <span class="badge bg-danger"><?php if($brPoruka>0)echo $brPoruka;?></span></a> </li>
                                        <li> <a href="<?=base_url("KorisnikController/index#onama")?>">O nama</a> </li>
                                        <li id="liPregled"><a href="<?=base_url("KorisnikController/pretragaPonuda")?>">Pretraži ponude</a></li>
                                        <li id="liTrazi"><a href="<?=base_url("KorisnikController/trazenjeVoznje")?>">Zatraži vožnju</a></li>
                                        <li id="liRezervacije"><a href="<?=base_url("KorisnikController/rezervacije")?>">Moje rezervacije</a></li>
                                        <li><a href="<?=base_url("KorisnikController/spintheweel")?>">Točak sreće</a></li>
                                        <li id="liReport"><a href="<?=base_url("KorisnikController/report")?>">Report</a></li>
                                        <li id="liOceni"><a href="<?=base_url("KorisnikController/ocenjivanje")?>">Oceni privatnika</a></li>

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
            // inkrementalno dodavati svaki active
            let url = window.location.href;
            if (url.includes("napraviPonudu")){
                $("#liONama").addClass("active");
            }
            else if (url.includes("zurira")){
                $("#liPregledaj").addClass("active");
            }
            else if (url.includes("inboxKorisnik") || url.includes("inboxKorisnikPoruka") || url.includes("prikazPonudeInbox") ){
                $("#liInboxK").addClass("active");
            }
            else if (url.includes("otkazi") || url.includes("trazenjeVoznje")){
                $("#liTrazi").addClass("active");
            }
            else if (url.includes("rezervacije")){
                $("#liRezervacije").addClass("active");
            }
            else if (url.includes("report")){
                $("#liReport").addClass("active");
            }
            else if (url.includes("pregledPonuda")){
                $("#liPregled").addClass("active");
            }
            else if (url.includes("ocenjivanje")){
                $("#liOceni").addClass("active");
            }
            else {
                $("#liPocetnaK").addClass("active");
            }
        });
    </script>