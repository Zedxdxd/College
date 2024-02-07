<section>
    <div class="admin-background container-fluid">
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
            <div class="col-sm"></div>
        </div>
    </div>
</section>