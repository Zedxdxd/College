<div class="container-fluid admin-background">
    <div class="row">
        <div class="text-block col-sm" style="margin-left: -15px;margin-right: -15px;">
        <table class="table table-striped" style="width: 100%">
                    <?php

                        foreach($nalozi as $nalog){ 
                            echo " <tr>
                        <td>
                            <h4>Zahtev za brisanje korisničkog naloga <strong>".$nalog->KorisnickoIme.".</strong></h4>
                            <div style='width: 25%'>
                            <a href=".base_url('AdminController/potvrdiBrisanje?izbor='.$nalog->SifK)."><button class='dugme'>Pregledaj</button></a>
                            </div>
                        </td>
                    </tr>";
                        }
                    ?>
                    <?php
                    
                    foreach($reportovi as $nalog){ 
                        echo "<tr>
                <td>
                    <h4>Obaveštenje o novom report-u naloga <strong>".$nalog->KorisnickoIme.".</strong></h4>

                    <div style='width: 25%'>
                    <a href=".base_url('AdminController/reportDetalji?izbor1='.$nalog->SifPrijavljen."&izbor2=".$nalog->SifPrijavio."&r=".$nalog->SifRep)."><button class='dugme'>Pregledaj</button></a>
                    </div>
                </td>
            </tr>";
                
                    }
                ?>
                    
                </table>
        </div>

        <?php 

            echo "<div class='text-block1 col-sm'>
            <form>
                <br />
                <h2> <strong>Informacije o korisničkom nalogu</strong></h2>
                <br />
                <h3>Korisničko ime</h3>
                <input type='text' disabled value=".$odabran1->KorisnickoIme." />
                <h3>Lozinka</h3>
                <input type='text' disabled value=".$odabran1->Lozinka." />
                <h3>Ime</h3>
                <input type='text' disabled value=".$odabran1->Ime." />
                <h3>Prezime</h3>
                <input type='text' disabled value=".$odabran1->Prezime." />
                <h3>Broj telefona</h3>
                <input type='text' disabled value=".$odabran1->BrTel." />
                <h3>Email</h3>
                <input type='text' disabled value=".$odabran1->Email." />
                <h3>Tip</h3>
                <input type='text' disabled value=";
                $tip="";
                if($odabran1->PrivatnikIliKorisnik=='K')$tip="Običan korisnik";
                else $tip="Privatnik";
                echo
                $tip." />
                <br />
                <br />";
        
        ?>

            </form>

            <a href="<?= base_url('AdminController/reportDetalji?izbor1='.$odabran1->SifK.'&izbor2='.$odabran2->SifK."&r=".$odabran1->SifRep)?>" style="color:white"><button class="dugme">
                        << Nazad </button></a>
        </div>
    </div>
</div>