<div class="back-image-message" height="100%">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-4 back-image-inbox">
                <ul style="color:black;">
                    <?php
                        foreach($poruke as $poruka){ 
                            echo "<li>
                                <div class='container-fluid' style='margin: 2em;'>
                                    <div><b>".$poruka->korisnik."</b></div>
                                    <a href=".base_url('PrivatnikController/inboxPrivatnikPoruka?poruka='.$poruka->SifPor).">
                                        <div class='hoverable-cells div-messages'style='padding-left: 15px;'>
                                            Mesto polaska:".$poruka->mestoOd->Naziv."
                                        </div>
                                    </a>
                                </div>
                            </li>"; 
                        }

                        if(empty($poruke)){ 
                            echo 
                            "<div class='container-fluid' style='margin: 2em;'>
                                <div style='padding:25px'><b></b></div>
                            
                            </div>";
                        }
                    ?> 
                </ul>
            </div>
            <?php
                if(!empty($odabrana)){ 
                    session()->set("zatrazenaPonuda",$odabrana);
                    echo "<div class='col-sm-8 justify-content-center back-image-message'>
                    <div class='container-fluid'>
                       <table class='table' style='color: black; border-width: 2px; text-align: center;'>
                          <tr>
                             <td>Mesto polaska</td>
                             <td>".$odabrana->mestoOd->Naziv."</td>
                          </tr>
                          <tr>
                             <td>Mesto dolaska</td>
                             <td>".$odabrana->mestoDo->Naziv."</td>
                          </tr>
                          <tr>
                             <td>Datum polaska od</td>
                             <td>".$odabrana->DatumOd."</td>
                          </tr>
                          <tr>
                             <td>Datum polaska do</td>
                             <td>".$odabrana->DatumDo."</td>
                          </tr>
                          <tr>
                             <td>Vreme polaska od</td>
                             <td>".$odabrana->VremeOd."</td>
                          </tr>
                          <tr>
                             <td>Vreme polaska do</td>
                             <td>".$odabrana->VremeDo."</td>
                          </tr>
                          <tr>
                             <td>Prevozno sredstvo</td>
                             <td>".$odabrana->sredstvo."</td>
                          </tr>
                          <tr>
                             <td>Cena od</td>
                             <td>".$odabrana->CenaOd."</td>
                          </tr>
                          <tr>
                             <td>Cena do</td>
                             <td>".$odabrana->CenaDo."</td>
                          </tr>
                          <tr>
                             <td>Broj putnika</td>
                             <td>".$odabrana->BrMesta."</td>
                          </tr>
                       </table>
                       <div class='container-fluid'>
                          <a href=".base_url('PrivatnikController/napraviPonudu?SifK='.$odabrana->SifK)."><button type='button' class='btn make-offer-btn' style='position: relative; left: 50%;margin-bottom:10%'>Napravi
                                ponudu</button></a>
                       </div>
                    </div>";
                }
            ?>
         </div>
        </div>
    </div>
</div>