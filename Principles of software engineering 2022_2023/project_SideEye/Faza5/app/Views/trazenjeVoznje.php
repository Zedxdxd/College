<?php

if (!empty($poruka)) {
    echo "<div style='display: flex; justify-content: center; color: red'
                <h5>{$poruka}</h5>
             </div>";
 }
 if (!empty($porukaUspeh)) {
    echo "<div style='display: flex; justify-content: center; color: green'
                <h5>{$porukaUspeh}</h5>
             </div>";
 }

?>
<div class="back-image-inbox" style="padding-bottom: 40px;">
    <div class="row">
        <div class="col-md-12 ">
            <div class="titlepage" style="margin-top: 20px;">
                <h2>Zatraži vožnju</h2>
                <span> </span>
            </div>
        </div>
    </div>
    <div class="container">
        <form action="http://localhost:8080/KorisnikController/trazenjeVoznje" class="main-form" method="POST">
            <div class="row">
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                            <label>Prevozno sredstvo</label>
                            <select class="form-control" name="prevoz">
                            <?php 
                                foreach ($sredstva as $sredstvo) {
                                   
                                    echo " <option value=".$sredstvo->SifSred.">".$sredstvo->Naziv."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                            <label>Mesto do</label>
                            <select class="form-control" name="MesDo">
                               
                                <?php 
                                foreach ($mesta as $mesto) {
                                   
                                    echo " <option value=".$mesto->SifM.">".$mesto->Naziv."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                            <label>Mesto od</label>
                            <select class="form-control" name="MesOd">
                            <?php 
                                foreach ($mesta as $mesto) {
                                   
                                    echo " <option value=".$mesto->SifM.">".$mesto->Naziv."</option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                            <label>Minimalna cena</label>
                            <input class="form-control" placeholder="0" type="text" name="CenaOd">
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                            <label>Maksimalna cena</label>
                            <input class="form-control" placeholder="0" type="text" name="CenaDo">
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                            <label> Broj putnika</label>
                            <input class="form-control" placeholder="0" type="text" name="BrojPutnika">
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-3 col-sm-6 col-12">
                            <label>Datum od</label>
                            <input class="form-control" placeholder="Any" type="date" name="DatumOd">
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-3 col-sm-6 col-12">
                            <label>Datum do</label>
                            <input class="form-control" placeholder="Any" type="date" name="DatumDo">
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-3 col-sm-6 col-12">
                            <label>Vreme od</label>
                            <input class="form-control" placeholder="Any" type="time" name="VremeOd">
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                            <label>Vreme do</label>
                            <input class="form-control" placeholder="Any" type="time" name="VremeDo">
                        </div>


                    </div>
                </div>
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12">
                    <input type="submit" value="Zatraži" style="background-color: rgb(6, 47, 10); width: 200px; font-size: 20px; color:white; margin-top: 80px"> 
                   
                </div>


            </div>
        </form>
    </div>
</div>