<!-- Željko Urošević 2020/0073 -->

<?php
$db      = \Config\Database::connect();
$builder = $db->table("mesto");
$mesta = $builder->get()->getResult();

$mestoOd = ($builder->where("SifM", $ponuda->SifMesOd)->get()->getResult())[0]->Naziv;
$mestoDo = ($builder->where("SifM", $ponuda->SifMesDo)->get()->getResult())[0]->Naziv;
$cena = $ponuda->CenaKarte;
$brMesta = $ponuda->BrMesta;
$datumOd = $ponuda->DatumOd;
$datumDo = $ponuda->DatumDo;
$vremeOd = $ponuda->VremeOd;
$vremeDo = $ponuda->VremeDo;

$builder = $db->table("rezervacija");
$rezervacije = $builder->where("SifP", $ponuda->SifP)->get()->getResult();

$builder = $db->table("postavljenaponuda");
if (!empty($builder->where("SifP", $ponuda->SifP)->get()->getResult())){
   $builder = $db->table("postavljenaponuda");
   $rokZaOtkazivanje = ($builder->where("SifP", $ponuda->SifP)->get()->getResult())[0]->RokZaOtkazivanje;
}
else {
   $builder = $db->table("vanrednaponuda");
   $rokZaOtkazivanje = ($builder->where("SifP", $ponuda->SifP)->get()->getResult())[0]->RokZaOtkazivanje;
}

$builder = $db->table("prevoznosredstvo");
$prevoznoSredstvo = ($builder->where("SifSred", $ponuda->SifSred)->get()->getResult())[0]->Naziv;
?>

<section>
   <div class="banner-main">
      <img src="<?php echo base_url('images/GettyImages-185298837.jpg') ?>" style="width:100%" alt="#" />
      <div class="text-block3">
         <div class="titlepage">
            <br /><br /><br />
            <h2>AŽURIRANJE PONUDE</h2>
         </div>
         <?php
         if (!empty($rezervacije)) {
            echo '<div style="display: flex; justify-content: center; color: red"
                        <h5>Mesta polaska i dolaska, kao i datum i vreme se ne mogu ažurirati jer je neko rezervisao.</h5>
                     </div>';
         }
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
         <form style="background-image:url(<?php echo base_url('anja/css/slikaPozadine.jpg') ?>);color:#004043;border-radius:10px;" enctype="multipart/form-data" method="post" action="<?= site_url("PrivatnikController/azuriranjePonudeSubmit/{$ponuda->SifP}") ?>">
            <div class="row" style="margin-top:50px;margin-right:30px;margin-left:30px;">
               <div class="col-md-12">
                  <div class="row">
                     <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12" style="margin-top:30px">
                        <label>Prevozno sredstvo</label>
                        <select class="form-control" name="prevoznoSredstvo" readonly>
                           <option><?= $prevoznoSredstvo ?></option>
                        </select>
                     </div>
                     <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12" style="margin-top:30px">
                        <label>Mesto od</label>
                        <select class="form-control" name="mestoPolaska" <?php if (!empty($rezervacije)) {
                                                                              echo "readonly";
                                                                           } else {
                                                                              echo "required";
                                                                           }
                                                                           ?>>
                           <?php
                           echo "<option>{$mestoOd}</option>";
                           foreach ($mesta as $mesto) {
                              if ($mestoOd != $mesto->Naziv) {
                                 echo "<option>{$mesto->Naziv}</option>";
                              }
                           }
                           ?>
                        </select>
                     </div>
                     <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12" style="margin-top:30px">
                        <label>Mesto do</label>
                        <select class="form-control" name="mestoDolaska" <?php if (!empty($rezervacije)) {
                                                                              echo "readonly";
                                                                           } else {
                                                                              echo "required";
                                                                           }
                                                                           ?>>
                           <?php
                           echo "<option>{$mestoDo}</option>";
                           foreach ($mesta as $mesto) {
                              if ($mestoDo != $mesto->Naziv) {
                                 echo "<option>{$mesto->Naziv}</option>";
                              }
                           }
                           ?>
                        </select>
                     </div>
                     <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <label>Cena prevoza</label>
                        <input class="form-control" value="<?= $cena ?>" type="number" name="cenaKarte" required>
                     </div>
                     <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12 col-12">
                        <label> Broj slobodnih mesta</label>
                        <input class="form-control" value="<?= $brMesta ?>" type="number" name="brMesta" required>
                     </div>
                     <div class="col-xl-3 col-lg-4 col-md-3 col-sm-6 col-12">
                        <label>Datum polaska</label>
                        <input class="form-control" value="<?= $datumOd ?>" type="date" name="datumOd" <?php if (!empty($rezervacije)) {
                                                                                                            echo "readonly";
                                                                                                         } else {
                                                                                                            echo "required";
                                                                                                         }
                                                                                                         ?>>
                     </div>
                     <div class="col-xl-3 col-lg-4 col-md-3 col-sm-6 col-12">
                        <label>Datum dolaska</label>
                        <input class="form-control" value="<?= $datumDo ?>" type="date" name="datumDo" <?php if (!empty($rezervacije)) {
                                                                                                            echo "readonly";
                                                                                                         } else {
                                                                                                            echo "required";
                                                                                                         }
                                                                                                         ?>>
                     </div>
                     <div class="col-xl-3 col-lg-4 col-md-3 col-sm-6 col-12">
                        <label>Vreme polaska</label>
                        <input class="form-control" value="<?= $vremeOd ?>" type="time" name="vremeOd" <?php if (!empty($rezervacije)) {
                                                                                                            echo "readonly";
                                                                                                         } else {
                                                                                                            echo "required";
                                                                                                         }
                                                                                                         ?>>
                     </div>
                     <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                        <label>Vreme dolaska</label>
                        <input class="form-control" value="<?= $vremeDo ?>" type="time" name="vremeDo" <?php if (!empty($rezervacije)) {
                                                                                                            echo "readonly";
                                                                                                         } else {
                                                                                                            echo "required";
                                                                                                         }
                                                                                                         ?>>
                     </div>
                     <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12" style="margin-bottom:30px">
                        <label>Izaberite fotografiju</label>
                        <input type="hidden" name="MAX_FILE_SIZE" value="1000000" />
                        <input class="form-control" style="height:40px;width:270px;" placeholder="Any" type="file" name="slika">
                     </div>
                     <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12" style="margin-bottom:30px">
                        <label style="margin-left:30px">Unesite otkazni rok</label>
                        <input class="form-control" style="height:40px;width:250px;margin-left:30px" value="<?= $rokZaOtkazivanje ?>" type="number" name="rokZaOtkazivanje" required>
                     </div>
                  </div>
               </div>
            </div>
            <div class="row justify-content-center" style="margin-top:50px; padding-bottom: 50px">
               <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12" style="margin-right:-100px; justify-content: center; display: flex">
                  <input type='submit' class="dugme" value="Azuriraj">
               </div>
               <div class="col-xl-4 col-lg-4 col-md-4 col-sm-12 col-12" style="justify-content: center; display: flex">
                  <input type='reset' class="dugme" value="Resetuj">
               </div>
            </div>
         </form>
      </div>
   </div>
</section>