<?php
$db      = \Config\Database::connect();
$builder = $db->table("pretplata");
$pretplataStandard = ($builder->where("Naziv", "Standard")->get()->getResult())[0];
$pretplataPremium = ($builder->where("Naziv", "Premium")->get()->getResult())[0];

?>

<section>
    <div class="banner-main">
        <img src="<?php echo base_url('images/GettyImages-185298837.jpg') ?>" style="width:100%" alt="#" />
        <div class="text-block2">
            <div class="titlepage" style="padding-bottom: unset">
                <br /><br /><br />
                <h2>PROMENA PRETPLATE</h2>
            </div>
            <br /><br />
            <div class="klasa">
                <ul>
                    <?php
                    if (!empty($poruka)) {
                        echo "<div style='color: red; display: flex; justify-content: center; margin-bottom: 20px'>{$poruka}</div>";
                    }
                    if (!empty($porukaUspeh)) {
                        echo "<div style='color: red; display: flex; justify-content: center; margin-bottom: 20px'>{$porukaUspeh}</div>";
                    }
                    ?>
                    <il><strong style="font-size:25px">STANDARD </strong>običan privatnik, bez dodatnih privilegija.
                        Mesečna pretplata iznosi </il>
                    <strong><?= $pretplataStandard->Iznos ?>€.</strong>
                    <br /><br />
                    <il><strong style="font-size:25px">PREMIUM </strong>poseduje dodatne mogućnosti promovisanja
                        ponuda:<br /><br />
                        <ul class="klasa" style="list-style-type:disc;color:deeppink;">
                            <il>
                                <font color=black>✔ </font> Isticanje Vaših ponuda korisniku, prilikom pretrage
                            </il><br /><br />
                            <il>
                                <font color=black>✔ </font> Vaše ponude će se reklamirati na početnoj stranici korisnika
                            </il><br /><br />
                            <il>
                                <font color=black>✔ </font> Probni period do početka narednog meseca
                            </il><br /><br />
                            <il>
                                <font color=black>✔ </font> Cena pretplate<strong>
                                    <font color=black><?= $pretplataPremium->Iznos ?> € </font>
                                </strong>
                            </il><br /><br />
                            <il>
                                <font color=black>✔ </font> Mogućnost povratka na standardnu pretplatu u bilo kom trenutku.
                            </il>
                        </ul>
                    </il>
                </ul>
                <br />
                <form method="post" action="<?= site_url("PrivatnikController/promenaPretplateSubmit") ?>">
                    <div class="container-fluid">
                        <div class="row">
                            <div class="col-2"></div>
                            <div class="col-3">
                                <button name="dugme" value="Premium" style="background-color:deeppink;color:white;width:200px;height:70px">Isprobajte
                                    PREMIUM <strong><?= $pretplataPremium->Iznos ?> €/1 month</strong></button>
                            </div>
                            <div class="col-2"></div>
                            <div class="col-2">
                                <button name="dugme" value="Standard" style="background-color:black;color:white; width:210px;height:70px;"> Povratak na
                                    STANDARD </button>
                            </div>
                            <div class="col-3"></div>
                        </div>
                    </div>
                </form>
                <p class="klasa" style="font-size:15px;"><strong style="color:black">Napomena:</strong>Nova pretplata
                    se uključuje korisniku od prvog dana u narednom mesecu.</p>
            </div>
        </div>
    </div>
</section>