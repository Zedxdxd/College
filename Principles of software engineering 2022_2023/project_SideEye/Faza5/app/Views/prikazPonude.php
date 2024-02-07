    <!-- Željko Urošević 2020/0073 -->

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
 

$db = \Config\Database::connect();
$builder = $db->table('mesto');
$mestoOd = ($builder->where("SifM", $ponuda->SifMesOd)->get()->getResult())[0]->Naziv;
$mestoDo = ($builder->where("SifM", $ponuda->SifMesDo)->get()->getResult())[0]->Naziv;

$danOd = explode("-", $ponuda->DatumOd)[2];
$mesecOd = explode("-", $ponuda->DatumOd)[1];
$godinaOd = explode("-", $ponuda->DatumOd)[0];
$datumOd = $danOd . "/" . $mesecOd . "/" . $godinaOd;

$danDo = explode("-", $ponuda->DatumDo)[2];
$mesecDo = explode("-", $ponuda->DatumDo)[1];
$godinaDo = explode("-", $ponuda->DatumDo)[0];
$datumDo = $danDo . "/" . $mesecDo . "/" . $godinaDo;

$satiOd = explode(":", $ponuda->VremeOd)[0];
$minutiOd = explode(":", $ponuda->VremeOd)[1];
$vremeOd = $satiOd . ":" . $minutiOd;

$satiDo = explode(":", $ponuda->VremeDo)[0];
$minutiDo = explode(":", $ponuda->VremeDo)[1];
$vremeDo = $satiDo . ":" . $minutiDo;

$builder = $db->table("rezervacija");
$privatnik= $db->table('korisnik')->where("SifK",$ponuda->SifK)->get()->getResult()[0]->KorisnickoIme;
$rezervacije = $builder->where("SifP", $ponuda->SifP)->get()->getResult();

$brMesta = $ponuda->BrMesta ;

$builder = $db->table("prevoznosredstvo");
$prevoznoSredstvo = ($builder->where("SifSred", $ponuda->SifSred)->get()->getResult())[0]->Naziv;

$builder = $db->table("postavljenaponuda");

$postavljenaponuda = ($builder->where("SifP", $ponuda->SifP)->get()->getResult());
if(count($postavljenaponuda)==0){
    $builder = $db->table("vanrednaponuda");
    $postavljenaponuda = ($builder->where("SifP", $ponuda->SifP)->get()->getResult())[0];
}else{
    $postavljenaponuda = ($builder->where("SifP", $ponuda->SifP)->get()->getResult())[0];
}


$rokZaOtkaz = $postavljenaponuda->RokZaOtkazivanje;

$cena = $ponuda->CenaKarte;

$builder = $db->table("ocena");
$ocene = $builder->where("SifPriv", $ponuda->SifK)->get()->getResult();
$broj = 0;
$suma = 0;
foreach ($ocene as $ocena) {
    $suma += $ocena->Ocena;
    $broj++;
}
if ($broj == 0) {
    $broj = 1;
}
$prosek = $suma * 1.0 / $broj;



?>


<hr style="height:1px;border:none;color:#333;background-color:#333;">
<div class="back-image-message">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-12" align="center" style="margin-top: 20px;">
                <h1 style="font-size: 3em;"><?= $mestoOd ?> -> <?= $mestoDo ?></h1>
            </div>
            <div class="col-sm-4" style="margin-top: 30px;"><img src="<?= base_url("images/ponude/{$ponuda->Slika}") ?>" style="border: 5px solid white;"></div>
            <div class="col-sm-8" style="margin-top: 30px;">
                <table class="table">
                    <tr>
                        <!-- nzm sta da radim sa ovim ?? nemamo ocenu u bazi nigde -->
                        <td>Privatnik <?= $privatnik ?></td>
                        <td><span class="fa fa-star <?php if ($prosek >= 1) echo 'star-checked' ?>"></span>
                            <span class="fa fa-star <?php if ($prosek >= 2) echo 'star-checked' ?>"></span>
                            <span class="fa fa-star <?php if ($prosek >= 3) echo 'star-checked' ?>"></span>
                            <span class="fa fa-star <?php if ($prosek >= 4) echo 'star-checked' ?>"></span>
                            <span class="fa fa-star <?php if ($prosek >= 5) echo 'star-checked' ?>"></span>
                        </td>
                    </tr>
                    <tr>
                        <td>Datum polaska: <?= $datumOd ?></td>
                        <td>Datum dolaska: <?= $datumDo ?></td>
                    </tr>
                    <tr>
                        <td>Vreme polaska: <?= $vremeOd ?></td>
                        <td>Vreme dolaska: <?= $vremeDo ?></td>
                    </tr>
                    <tr>
                        <td>Broj slobodnih mesta:</td>
                        <td><?= $brMesta ?>
                            <span>
                                <img src="<?php echo base_url('images/stickman.png') ?>" height="25" width="25">
                            </span>
                        </td>
                    </tr>
                    <tr>
                        <td>Prevozno Sredstvo:</td>
                        <td><?= $prevoznoSredstvo ?>
                            <img src='<?php echo base_url("images/{$prevoznoSredstvo}_transparent.png") ?>' height="30" width="30">
                        </td>
                    </tr>
                    <tr>
                        <td>Rok za otkaz rezervacije:</td>
                        <td><?= $rokZaOtkaz ?> dana</td>
                    </tr>
                </table>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-6"></div>
            <div class="col-sm-6">
                <h1 style="font-size: 2em;">Cena: <?= $cena ?>€</h1>
            </div>
        </div>
        <div class="row">
            <div class="col-sm-4"></div>
            <div class="col-sm-8">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-sm-6">
                            <button type="button"  name= "rezervisi" class="btn make-offer-btn open-button" style="position: relative; left: 20%;" onclick="openForm()">
                                Rezerviši
                            </button>
                            <div class="form-popup" id="myForm">
                                <table class="table table-borderless" style="opacity:95%;color:white;background-color: #004043;width:400px;height:500px;">
                                    <form class="form-container"  action= "<?=base_url("KorisnikController/prikazPonude/".$ponuda->SifP."/rezervisi")?>"   method="POST" >

                                        <tr>
                                            <td colspan='2'>
                                                <h1 class="boja" style="font-weight:bold;color:white;text-align:center;height:25%">Rezervacija
                                                    <hr />
                                                </h1>
                                            </td>
                                        </tr>
                                        <tr rowspan="2">
                                            <td width="50%"><label for="brtel"><b>Broj telefona</b></label></td>
                                            <td align="center"><input type="text" readonly placeholder="065 123 456 7" style="width:100%;"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <hr />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="psw"><b>Broj mesta</b></label></td>
                                            <td align="center"><input name="BrMesta" type="number" id="psw" style="width:100%"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <hr />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td align="center"><button type="submit" class="btn " style="background-color: pink;width:100px;">Rezerviši</button></td>
                                            <td align="center"><button type="button" class="btn cancel" style="background-color: pink;width:100px" onclick="closeForm()">Odustani</button></td>
                                        </tr>
                                    </form>
                                </table>
                            </div>
                        </div>
                        <div class="col-sm-6" style="margin-bottom: 15px;">
                            <button type="button" name= "kupi" class="btn make-offer-btn" style="position: relative; left: -10%;" onclick="openForm1()">
                                Kupi kartu
                            </button>
                            <div class="form-popup" id="myForm1">
                                <table class="table table-borderless" style="opacity:95%;color:white;background-color: #004043;width:400px;height:500px;">
                                    <form class="form-container"  action= "<?=base_url("KorisnikController/prikazPonude/".$ponuda->SifP."/kupi")?>" method="POST" >

                                        <tr>
                                            <td colspan='2'>
                                                <h1 class="boja" style="font-weight:bold;color:white;text-align:center">Kupovina karte</h1hr />
                                                </h1>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td width="50%"><label for="brtel"><b>Broj telefona</b></label></td>
                                            <td align="center"><input type="text" readonly placeholder="065 123 456 7" style="width:100%"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <hr />
                                            </td>
                                        </tr>
                                        <tr>
                                            <td><label for="psw"><b>Broj mesta</b></label></td>
                                            <td align="center"><input name="BrMesta" type="number" id="psw" style="width:100%"></td>
                                        </tr>
                                        <tr>
                                            <td colspan="2">
                                                <hr />
                                            </td>
                                        </tr>
                                        
                                        <tr>
                                            <td><label for="placanje"><b>Način plaćanja</b></label></td>
                                            <td align="center"> <select name="placanje" id="placanje" style="width:100%">
                                                    <option value="gotovina">Gotovina</option>
                                                    
                                                </select>
                                        <tr>
                                            <td colspan="2">
                                                <hr />
                                            </td>
                                        </tr>
                                        </td>
                                        </tr>
                                        <?php if (isset($mojenagrade)) : ?>
                                        <?php foreach ($mojenagrade as $nagrada) : ?>
                                            <tr>
                                                <td><label for="opcija1"><b><?= $nagrada->Iznos . $nagrada->TipPoklona ?></b></label><br></td>
                                                <td><input type="radio" name="grupa" value=<?= $nagrada->SifPokl ?>></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                        <tr>
                                            <td align="center"><button type="submit" class="btn " style="background-color: pink;width:100px;">Kupi</button></td>
                                            <td align="center"><button type="button" class="btn cancel" style="background-color: pink;width:100px" onclick="closeForm1()">Odustani</button></td>
                                        </tr>
                                    </form>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function openForm() {
        document.getElementById("myForm").style.display = "block";
    }

    function openForm1() {
        document.getElementById("myForm1").style.display = "block";
    }

    function closeForm() {
        document.getElementById("myForm").style.display = "none";
    }

    function closeForm1() {
        document.getElementById("myForm1").style.display = "none";
    }
</script>