<!-- Milica Cvetkovic 2020/0003 -->

<div class="back-image-inbox">
    <div class="row">
        <div class="col-md-12 ">
            <div class="titlepage" style="margin-top: 20px;">
                <h2>Ponude</h2>
                <span> </span>
            </div>
        </div>
    </div>
    <div class="container">
        <form class="main-form" action="<?= site_url("GostController/pretragaPonuda") ?>" method="post">
            <h3>Pretraži ponudu</h3>
            <div class="row">
                <div class="col-md-9">
                    <div class="row">
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                            <label>Prevozno sredstvo</label>
                            <select class="form-control" name="prevoznoSredstvo">
                                <option></option>
                                <?php
                                foreach ($svaPrevoznaSredstva as $p) {
                                    echo "<option> {$p->Naziv} </option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                            <label>Mesto do</label>
                            <select class="form-control" name="mestoDo">
                                <option></option>
                                <?php
                                foreach ($svaMesta as $p) {
                                    echo "<option> {$p->Naziv} </option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                            <label>Mesto od</label>
                            <select class="form-control" name="mestoOd">
                                <option></option>
                                <?php
                                foreach ($svaMesta as $p) {
                                    echo "<option> {$p->Naziv} </option>";
                                }
                                ?>
                            </select>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                            <label>Minimalna cena</label>
                            <input class="form-control" placeholder="0" type="number" name="minimalnaCena">
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                            <label>Maksimalna cena</label>
                            <input class="form-control" placeholder="0" type="number" name="maksimalnaCena">
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-4 col-sm-6 col-12">
                            <label> Broj putnika</label>
                            <input class="form-control" placeholder="0" type="number" name="brojPutnika">
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-3 col-sm-6 col-12">
                            <label>Datum do</label>
                            <input class="form-control" placeholder="Any" type="date" name="datumDo">
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-3 col-sm-6 col-12">
                            <label>Datum od</label>
                            <input class="form-control" placeholder="Any" type="date" name="datumOd">
                        </div>
                        <div class="col-xl-3 col-lg-4 col-md-3 col-sm-6 col-12">
                            <label>Vreme do</label>
                            <input class="form-control" placeholder="Any" type="time" name="vremeDo">
                        </div>
                        <div class="col-xl-3 col-lg-3 col-md-3 col-sm-6 col-12">
                            <label>Vreme od</label>
                            <input class="form-control" placeholder="Any" type="time" name="vremeOd">
                        </div>
                    </div>
                </div>
                <input type="hidden" name="resetPage" value="true">
                <div class="col-xl-3 col-lg-3 col-md-3 col-sm-12 col-12">
                    <a href="#" style="background-color: rgb(6, 47, 60);" onclick="this.closest('form').submit();return false;">Pretraži</a>
                </div>
            </div>
        </form>

    </div>
    <div class="container" style="display: flex; justify-content: center">
        <form name='sortform' action="<?= site_url('GostController/pretragaPonuda'); ?>" method='post'>
            <input type="hidden" name="sort" value="true">
            <select class='form-select' name='sortiranje' onchange='this.form.submit()' style="width: unset">
                <option selected>Sortiraj</option>
                <option value='rastuceCena'>Rastuće po ceni</a></option>
                <option value='rastuceDatum'>Rastuće po datumu</a></option>
                <option value='opadajuceCena'>Opadajuće po ceni</a></option>
                <option value='opadajuceDatum'>Opadajuće po datumu</a></option>
            </select>
        </form>
    </div>

    <?php
    for ($i = 0, $j = 0; $i < 3 && $j < count($ponude); $i++) {
        echo "<div class='row' style='margin-top: 40px;'>";
        $count = 3;
        while ($count != 0 && $j < count($ponude)) {
            echo "<div class='col-xl-4 col-lg-4 col-md-6 col-sm-12'>";

            echo "<form name='ponuda{$ponude[$j]->SifP}' id='ponuda{$ponude[$j]->SifP}' method='post' action='";
            echo site_url('GostController/prikazPonudeGost');
            echo "'>"; ?>
            <a href="javascript:{}" onclick="document.getElementById('ponuda<?= $ponude[$j]->SifP ?>').submit(); return false;">
                <div class='traveling-box'>
            <?php
            $db = \Config\Database::connect();
            $builder = $db->table("rezervacija");
            $rezervacije = $builder->where("SifP", $ponude[$j]->SifP)->get()->getResult();
            echo "<i><img src='";
            echo base_url('images/ponude/' . $ponude[$j]->Slika);
            echo "' alt='icon' style='object-fit: scale-down; margin-top: 10px; width: 250px; height: 250px;' /></i>";
            echo "<h2>{$ponude[$j]->MestoOd} - {$ponude[$j]->MestoDo}</h2>";
            echo "<p>Datum od: {$ponude[$j]->DatumOd} <br/> Datum do: {$ponude[$j]->DatumDo}</p>";
            echo "<br><img src='";
            echo base_url('images/' . $ponude[$j]->prevoznoSredstvo . '_transparent.png');
            echo "' height='35' width='35'><br>";
            echo "Broj slobodnih mesta: " . ($ponude[$j]->BrMesta);
            echo "<span><img src='";
            echo base_url('images/stickman.svg.png');
            echo "' height='15' width='15'></span>";
            echo "<h3>{$ponude[$j]->CenaKarte} €</h3>";
            echo "<input type='hidden' name='izabranaPonuda' value='{$ponude[$j]->SifP}'>";
            echo "</div></a></form></div>";
            $j++;
            $count--;
        }

        echo "</div>";
    }

            ?>

            <!--dugmad za pregled stranica-->
            <div class="row justify-content-center" style="margin-top: 40px; text-align: center;">
                <div class="col-xl-2">
                    <ul class="pagination" style="margin-bottom: 40px; display: flex;">

                        <?php

                        if ($page > 1) {
                            $result = $page - 1;
                            echo "<li class='page-item'><a class='page-link make-offer-btn' href='";
                            echo site_url('GostController/pretragaPonuda?page=' . $result);
                            echo "'>Prošla</a></li>";
                        }

                        if ($page - 2 > 0) {
                            $result = $page - 2;
                            echo "<li class='page-item'><a class='page-link make-offer-btn' href='";
                            echo site_url('GostController/pretragaPonuda?page=' . $result);
                            echo "'>{$result}</a></li>";
                        }
                        if ($page - 1 > 0) {
                            $result = $page - 1;
                            echo "<li class='page-item'><a class='page-link make-offer-btn' href='";
                            echo site_url('GostController/pretragaPonuda?page=' . $result);
                            echo "'>{$result}</a></li>";
                        }

                        echo "<li class='page-item'><a class='page-link make-offer-btn' href='";
                        echo site_url('GostController/pretragaPonuda?page=' . $page);
                        echo "'>{$page}</a></li>";

                        if ($page + 1 < ceil($totalPages / $numOfResultsOnPage) + 1) {
                            $result = $page + 1;
                            echo "<li class='page-item'><a class='page-link make-offer-btn' href='";
                            echo site_url('GostController/pretragaPonuda?page=' . $result);
                            echo "'>{$result}</a></li>";
                        }
                        if ($page + 2 < ceil($totalPages / $numOfResultsOnPage) + 1) {
                            $result = $page + 2;
                            echo "<li class='page-item'><a class='page-link make-offer-btn' href='";
                            echo site_url('GostController/pretragaPonuda?page=' . $result);
                            echo "'>{$result}</a></li>";
                        }

                        if ($page < ceil($totalPages / $numOfResultsOnPage)) {
                            $result = $page + 1;
                            echo "<li class='page-item'><a class='page-link make-offer-btn' href='";
                            echo site_url('GostController/pretragaPonuda?page=' . $result);
                            echo "'>Sledeća</a></li>";
                        }
                        ?>

                    </ul>
                </div>
            </div>
                </div>