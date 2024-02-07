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
 
?>
<div class="back-image-inbox">
    <div class="row">
        <div class="col-md-12 ">
            <div class="titlepage" style="margin-top: 20px;">
                <h2>Moje rezervacije</h2>
                <span> </span>
            </div>
        </div>
    </div>
    <div class="row">
    <div class="container-fluid " >

        <?php if (isset($mojeRezervacije)) {
        

            foreach ($mojeRezervacije as $i => $rezervacija) {
                
                if ($i % 3 == 0) {
                    echo '<div class="row">';
                }   
            echo'


                <div class="col-md-4 col-sm-12" style="margin-top: 40px; margin-bottom: 40px;">
                    
                        <div class="row">
                        <a href="#">
                            <div class="traveling-box">

                                <i><img src="'. base_url("images/ponude/{$rezervacija->Slika}") .'" alt="icon" style="object-fit: scale-down; margin-top: 10px; width: 250px; height: 250px;" /></i>
                                <h2>'.$rezervacija->SifMesOd .' -> '. $rezervacija->SifMesDo.'</h2>'
                                . $rezervacija->DatumDo.'<br><img src="'. base_url("images/car_transparent.png").'" height="35" width="35"><br>'
                                . $rezervacija->BrMesta.' <span><img src="'.base_url("images/stickman.svg.png").'" height="15" width="15"></span>
                                <h3>'. $rezervacija->CenaKarte.'</h3>

                            </div>
                            </a>
                        </div>
                        <div class="row" style="display:flex; justify-content:center; margin-top: 20px">
                            <button  class="btn make-offer-btn open-button" onclick="openForm('.$rezervacija->SifR.')" style="width:30%; position: relative;  ">Kupi</button>
                        </div>
                    
                    <div class="kupovina">
                        <div class="form-popup" id="myForm'.$rezervacija->SifR.'" style="bottom:0%">
                            <table class="table table-borderless" style="opacity:95%;color:white;background-color: #6181aa;width:400px;">

                                <form class="form-container " style="position: relative;" action="'. base_url("KorisnikController/kupi_kartu").'" method="POST">
                                    
                                    <tr>
                                        <td>
                                            <h1 class="boja" style="font-weight:bold;color:white;">Rezervacija
                                                <hr />
                                            </h1>
                                        </td>
                                        <td style="text-align:right;"><a onclick="closeForm('.$rezervacija->SifR.')" class="btn cancel" style="background-color: pink;width:30px; "  ><span style="display:flex; justify-content:center">X</span> </a></td>

                                       
                                    </tr>
                                    <tr>
                                        <td><label for="brtel"><b>Broj telefona</b></label></td>
                                        <td><span id="brtel">'. $rezervacija->KorisnikTel.'</span></td>
                                        <td> <input type="hidden" name="SifR" value='. $rezervacija->SifR.'></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <hr />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label for="psw"><b>Broj mesta</b></label></td>
                                        <td><input type="text" name="BrMesta" value='.$rezervacija->BrRez.' id="brojMesta"></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <hr />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label for="cena"><b>Iznos</b></label></td>
                                        <td> <span id="cena">'.$rezervacija->BrRez * $rezervacija->CenaKarte.'€</span></td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <hr />
                                        </td>
                                    </tr>
                                    <tr>
                                        <td><label for="placanje"><b>Način plaćanja</b></label></td>
                                        <td> <select name="placanje" id="placanje">
                                                <option value="gotovina">Gotovina</option>

                                            </select>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td colspan="2">
                                            <hr />
                                        </td>
                                    </tr>';


                                     if (isset($mojenagrade)) {
                                        foreach ($mojenagrade as $nagrada) {
                                            echo '
                                            <tr>
                                                <td><label for="opcija1"><b>'. $nagrada->Iznos . $nagrada->TipPoklona.'</b></label><br></td>
                                                <td><input type="radio" name="grupa" value='. $nagrada->SifPokl.'></td>
                                            </tr>';
                                        }
                                    }
                                    echo '
                                    <tr>

                                        <td text-align="center"><button name="kupi" value="kupi" type="submit" class="btn " style="background-color: pink;width:100px;">Kupi</button></td>

                                        <td text-align="center"><button  name="kupi" value="odustani" type="submit"  class="btn "    style="background-color: pink;width:300px" >Odustani od rezervacije</button></td>
                                    </tr>
                                </form>
                            </table>
                        </div>
                    </div>
                </div>';



        
                if ($i % 3 == 2) {
                    echo '</div>';
                }
                if(($i+1) == count($mojeRezervacije)){
                    echo '</div>';
                }
            }} ?>
        
    </div>
    </div>

</div>

<script>
    function openForm(sifR) {
        document.getElementById("myForm"+sifR).style.display = "block";
    }

    function closeForm(sifR) {
        document.getElementById("myForm"+sifR).style.display = "none";
    }
</script>