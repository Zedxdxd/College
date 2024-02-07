<div class="container-fluid admin-background">
    <div class="row">
        <div class="text-block col-sm" style="margin-left: -15px;margin-right: -15px;">
        <table class="table table-striped" style="width: 100%;">
                    <?php
                        foreach($nalozi as $nalog){ 
                            echo "<tr>
                            <td>
                                <h4>Zahtev za kreiranje korisničkog naloga <strong>".$nalog->KorisnickoIme."</strong></h4>
                                <div style='width: 25%'>
                                    <a href=".base_url('AdminController/potvrdiNalog?izbor='.$nalog->SifK)."><button class='dugme'>Pregledaj</button></a>
                                </div>
                            </td>
                        </tr>";
                        }
                    ?>
                </table>
        </div>

        <?php
            if(!empty($odabran)){ 
                echo "<div class='text-block1 col-sm'>
                <form>
                    <br />
                    <h2> <strong>Informacije o korisničkom nalogu</strong></h2>
                    <br />
                    <h3>Korisničko ime</h3>
                    <input type='text' disabled value=".$odabran->KorisnickoIme." />
                    <h3>Lozinka</h3>
                    <input type='text' disabled value=".$odabran->Lozinka." />
                    <h3>Ime</h3>
                    <input type='text' disabled value=".$odabran->Ime." />
                    <h3>Prezime</h3>
                    <input type='text' disabled value=".$odabran->Prezime." />
                    <h3>Broj telefona</h3>
                    <input type='text' disabled value=+381".$odabran->BrTel." />
                    <h3>Email</h3>
                    <input type='text' disabled value=".$odabran->Email." />
                    <h3>Tip</h3>
                    <input type='text' disabled value=";
                    $tip="";
                    if($odabran->PrivatnikIliKorisnik=='K')$tip="Običan korisnik";
                    else $tip="Privatnik";
                    echo
                    $tip." />
                    <br />
                    <br />";
                    
            }
            ?>
            </form>
            <a href="<?php echo base_url('AdminController/potvrdiKreiranje?izbor='.$odabran->SifK)?>"><button class='dugme' style='float:left'>Potvrdi kreiranje</button></a>
                    &nbsp;&nbsp;
            <a href="<?php echo base_url('AdminController/potvrdiOdbijanje?izbor='.$odabran->SifK)?>"><button class='dugme' style='float:left'>Odbij kreiranje</button></a>
        
            </div>
    </div>
</div>