<!-- Milica Cvetkovic 2020/0003 -->

<?php 
$db = \Config\Database::connect();
$builder = $db->table("postavljenaponuda");
$postavljenaponuda = ($builder->where("SifP", $ponuda->SifP)->get()->getResult());
if(count($postavljenaponuda)==0){
    $builder = $db->table("vanrednaponuda");
    $postavljenaponuda = ($builder->where("SifP", $ponuda->SifP)->get()->getResult())[0];
}else{
    $postavljenaponuda = ($builder->where("SifP", $ponuda->SifP)->get()->getResult())[0];
}

$brMesta = $ponuda->BrMesta;
?>

<div class="back-image-message">
        <div class="container-fluid">
            <div class="row">
                <div class="col-sm-12" align="center" style="margin-top: 20px;"><h1 style="font-size: 3em;"><?=$ponuda->MestoOd?> - <?=$ponuda->MestoDo?></h1></div>
                <div class="col-sm-4" style="margin-top: 30px;"><img src="<?=base_url('images/ponude/'.$ponuda->Slika)?>" style="border: 5px solid white;" ></div>
                <div class="col-sm-8" style="margin-top: 30px;">
                    <table class="table">
                        <tr>
                            <td>Privatnik <?= $ponuda->KorisnickoIme ?></td>
                        <td><span class="fa fa-star <?php if ($prosek >= 1) echo 'star-checked' ?>"></span>
                            <span class="fa fa-star <?php if ($prosek >= 2) echo 'star-checked' ?>"></span>
                            <span class="fa fa-star <?php if ($prosek >= 3) echo 'star-checked' ?>"></span>
                            <span class="fa fa-star <?php if ($prosek >= 4) echo 'star-checked' ?>"></span>
                            <span class="fa fa-star <?php if ($prosek >= 5) echo 'star-checked' ?>"></span>
                        </td>
                        </tr>
                        <tr>
                            <td>Datum polaska: <?=$ponuda->DatumOd?></td>
                            <td>Datum dolaska: <?=$ponuda->DatumDo?></td>
                        </tr>
                        <tr>
                            <td>Vreme polaska: <?=$ponuda->VremeOd?></td>
                            <td>Vreme dolaska: <?=$ponuda->VremeDo?></td>
                        </tr>
                        <tr>
                            <td>Broj slobodnih mesta:</td>
                            <td><?=$brMesta?>
                            <span>
                                <img src="<?=base_url('images/stickman.svg.png')?>"
                                height="25" width="25">
                            <span>
                        </tr>
                        <tr>
                            <td>Prevozno Sredstvo:</td>
                            <td><?=$ponuda->prevoznoSredstvo?>
                                <img src='<?=base_url('images/'.$ponuda->prevoznoSredstvo.'_transparent.png')?>' height='30' width='30'>
                            </td>
                        </tr>
                        <tr>
                            <td>Rok za otkaz rezervacije</td>
                            <td><?= $postavljenaponuda->RokZaOtkazivanje?> dana</td>
                        </tr>
                    </table>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-6"></div>
                <div class="col-sm-6"><h1 style="font-size: 2em;">Cena: <?=$ponuda->CenaKarte?> din.</h1></div>
            </div>
            <div class="row">
                <div class="col-sm-5"></div>
                <div class="col-sm-1"><button type="button" class="btn make-offer-btn" style="position: relative; left: 50%;" onclick="window.location= '<?= site_url("GostController/login") ?>'">Rezerviši</button></div>
                <div class="col-sm-1"></div>
                <div class="col-sm-1" style="margin-bottom: 15px;"><button type="button" class="btn make-offer-btn" style="position: relative; left: 50%;" onclick="window.location= '<?= site_url("GostController/login") ?>'">Kupi kartu</button></div>
            </div>
        </div>
    </div>