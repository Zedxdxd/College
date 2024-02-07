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
            echo "
            <div class='text-block1 col-sm'>
            <form>
               <br />
               <h2> <strong>Obaveštenje</strong></h2>
               <div class='row'>
                  <table class='table-stripped' style='margin-left:20px'>
                     <tr>
                        <th style='width:200px'>Report za nalog: </th>
                        <td>".$odabran1->KorisnickoIme."</td>
                     </tr>
                     <tr>
                        <th style='width:200px'>Report od korisnika: </th>
                        <td>".$odabran2->KorisnickoIme."</td>
                     </tr>
                     <tr>
                        <th style='width:200px'>Razlog report-a: </th>
                        <td>".$razlog."</td>
                     </tr>
                  </table>
               </div>
               <br />
               <br />";
       
       ?>

         </form>
         <a href="<?= base_url('AdminController/detaljiPrivatnikaPosleReporta?izbor1='.$odabran1->SifK.'&izbor2='.$odabran2->SifK."&r=".$odabran1->SifRep)?>"
                  style="color:white;text-decoration:none"><button class="dugme" style="margin-right:10px;">Detalji o nalogu </button></a>

            <a href="<?= base_url('AdminController/posaljiEmail?izbor1='.$odabran1->SifK.'&izbor2='.$odabran2->SifK."&r=".$odabran1->SifRep)?>"><button class="dugme" style="margin-right:10px;">Upozori</button></a>

           <a href="<?= base_url('AdminController/Obrisi?izbor='.$odabran1->SifK.'&izbor2='.$odabran2->SifK."&r=".$odabran1->SifRep)?>"> <button class="dugme">Ukloni nalog</button></a>
      </div>
    </div>
</div>
