<?php
$db = \Config\Database::connect();
$builder = $db->table("mesto");
$mesta = $builder->get()->getResult();

$builder = $db->table("prevoznosredstvo");
$prevoznaSredstva = $builder->get()->getResult();

$zatrazenaPonuda = session()->get("zatrazenaPonuda");

?>

<section>
   <div class="banner-main">
      <img src="<?php echo base_url('images/GettyImages-185298837.jpg') ?>" style="width:100%" alt="#" />
      <div class="text-block2">
         <div class="titlepage">
            <br /><br /><br />
            <h2>KREIRANJE PONUDE</h2>
         </div>
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
         <form style="background-image:url(<?php echo base_url('anja/css/slikaPozadine.jpg') ?>);color:#004043;border-radius:10px;" enctype="multipart/form-data"  method="post" action="<?= site_url("PrivatnikController/napraviPonuduSubmit") ?>">
            <input type="hidden" name='SifK' value="<?php 
            if ($SifK!=null && $SifK>=0) {
                  echo $SifK;
            } 
            else {echo -1; }?>" />
            <div class="row" style="margin-top:50px;margin-right:30px;margin-left:30px;">
               <div class="col-md-12">
                  <div class="row">
                     <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12" style="margin-top:30px">
                        <label>Prevozno sredstvo</label>
                        <select class="form-control" name="prevoznoSredstvo" required>
                           <?php
                           $prevoznoSredstvoSesija = "";
                           if (!empty($zatrazenaPonuda)) {
                              $builder = $db->table("prevoznosredstvo");
                              $prevoznoSredstvoSesija = ($builder->where("SifSred", $zatrazenaPonuda->SifSred)->get()->getResult())[0]->Naziv;
                              echo "<option>{$prevoznoSredstvoSesija}</option>";
                           } else if (!empty(session()->get("prevoznoSredstvo"))) {
                              $prevoznoSredstvoSesija = session()->get("prevoznoSredstvo");
                              echo "<option>{$prevoznoSredstvoSesija}</option>";
                           }
                           foreach ($prevoznaSredstva as $prevoznoSredstvo) {
                              if ($prevoznoSredstvoSesija != $prevoznoSredstvo->Naziv) {
                                 echo "<option>{$prevoznoSredstvo->Naziv}</option>";
                              }
                           }
                           ?>
                        </select>
                     </div>
                     <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12" style="margin-top:30px">
                        <label>Mesto od</label>
                        <select class="form-control" name="mestoPolaska" required>
                           <?php
                           $mestoOdSesija = "";
                           if (!empty($zatrazenaPonuda)) {
                              $builder = $db->table("mesto");
                              $mestoOdSesija = ($builder->where("SifM", $zatrazenaPonuda->SifMesOd)->get()->getResult())[0]->Naziv;
                              echo "<option>{$mestoOdSesija}</option>";
                           } else if (!empty(session()->get("mestoOd"))) {
                              $mestoOdSesija = session()->get("mestoOd");
                              echo "<option>{$mestoOdSesija}</option>";
                           }
                           foreach ($mesta as $mesto) {
                              if ($mestoOdSesija != $mesto->Naziv) {
                                 echo "<option>{$mesto->Naziv}</option>";
                              }
                           }
                           ?>
                        </select>
                     </div>
                     <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12" style="margin-top:30px">
                        <label>Mesto do</label>
                        <select class="form-control" name="mestoDolaska" required>
                           <?php
                           $mestoDoSesija = "";
                           if (!empty($zatrazenaPonuda)) {
                              $builder = $db->table("mesto");
                              $mestoDoSesija = ($builder->where("SifM", $zatrazenaPonuda->SifMesDo)->get()->getResult())[0]->Naziv;
                              echo "<option>{$mestoDoSesija}</option>";
                           } else if (!empty(session()->get("mestoDo"))) {
                              $mestoDoSesija = session()->get("mestoDo");
                              echo "<option>{$mestoDoSesija}</option>";
                           }
                           foreach ($mesta as $mesto) {
                              if ($mestoDoSesija != $mesto->Naziv) {
                                 echo "<option>{$mesto->Naziv}</option>";
                              }
                           }
                           ?>
                        </select>
                     </div>
                     <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <label>Cena prevoza</label>
                        <input class="form-control" placeholder="0" type="text" name="cenaKarte" required
                                 <?php if (!empty(session()->get("cenaKarte"))) echo "value='".session()->get("cenaKarte")."'" ?>>
                     </div>
                     <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <label> Broj slobodnih mesta</label>
                        <input class="form-control" placeholder="0" type="text" name="brMesta"  required
                                 <?php if (!empty($zatrazenaPonuda)) {echo "value='{$zatrazenaPonuda->BrMesta}'";}
                                 else if (!empty(session()->get("brMesta"))) {echo "value='".session()->get("brMesta")."'";} ?>>
                     </div>
                     <div class="col-xl-3 col-lg-4 col-md-3 col-sm-6 col-12">
                        <label>Datum polaska</label>
                        <input class="form-control" placeholder="Any" type="date" name="datumOd" required
                                 <?php if (!empty($zatrazenaPonuda)) {echo "value='{$zatrazenaPonuda->DatumOd}'";}
                                 else  if (!empty(session()->get("datumOd"))) {echo "value='".session()->get("datumOd")."'";} ?>>
                     </div>
                     <div class="col-xl-3 col-lg-4 col-md-3 col-sm-6 col-12">
                        <label>Datum dolaska</label>
                        <input class="form-control" placeholder="Any" type="date" name="datumDo" required
                                 <?php if (!empty(session()->get("datumDo"))) {echo "value='".session()->get("datumDo")."'";}?>>
                     </div>
                     <div class="col-xl-3 col-lg-4 col-md-3 col-sm-6 col-12">
                        <label>Vreme polaska</label>
                        <input class="form-control" placeholder="Any" type="time" name="vremeOd" required
                                 <?php if (!empty($zatrazenaPonuda)) {echo "value='{$zatrazenaPonuda->VremeOd}'";}
                                 else  if (!empty(session()->get("vremeOd"))) {echo "value='".session()->get("vremeOd")."'";} ?>>
                     </div>
                     <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                        <label>Vreme dolaska</label>
                        <input class="form-control" placeholder="Any" type="time" name="vremeDo" required
                                 <?php if (!empty(session()->get("vremeDo"))) {echo "value='".session()->get("vremeDo")."'";}?>>
                     </div>
                     <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12" style="margin-bottom:30px">
                        <label>Izaberite fotografiju</label>
                        <input class="form-control" style="height:40px;width:270px;" placeholder="Any" type="file" name="slika">
                     </div>
                     <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12" style="margin-bottom:30px">
                        <label style="margin-left:70px;width:150px">Unesite otkazni rok</label>
                        <input class="form-control" style="height:40px;width:250px;margin-left:70px" placeholder="5" type="number" name="rokZaOtkazivanje" required
                                 <?php if (!empty(session()->get("rokZaOtkazivanje"))) {echo "value='".session()->get("rokZaOtkazivanje")."'";}?>>
                     </div>
                  </div>
               </div>
            </div>
            <div class="row justify-content-center" style="margin-top:50px; padding-bottom: 50px">
               <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12" style="margin-right:-100px; justify-content: center; display: flex">
                  <input type='submit' class="dugme" value="Napravi">
               </div>
               <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12" style="justify-content: center; display: flex">
                  <input type='reset' class="dugme" value="Resetuj">
               </div>
            </div>
         </form>
      </div>
   </div>
</section>