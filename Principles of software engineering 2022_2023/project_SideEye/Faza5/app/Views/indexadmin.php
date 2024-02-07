<section>
    <div class="container-fluid admin-background">
        <div class="row">
            <div class="text-block col" style="margin-left: -15px;margin-right: -15px;">
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
            <div class="col"></div>
        </div>
    </div>
</section>