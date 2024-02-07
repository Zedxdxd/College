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
<div class="back-image-inbox" style="padding-bottom: 15px">
    <div class="row">
        <div class="col-md-12 ">
            <div class="titlepage" style="margin-top: 20px;">
                <h2>Oceni</h2>
                <span> </span>
            </div>  
        </div>
    </div>
    <div class="container" align="center">
        <form class="main-form" action='<?= base_url("KorisnikController/ocenjivanje")?>' method="post" style="width: 50%;">
            <div class="row">
                <div class="col-sm-12">
                    <div class="row">
                        <div class="col-sm-12">
                            <label>Ime privatnika</label>
                            <input type="text" name="Imeprivatnika" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-4">
                    <label style="margin-right: 10px; margin-top: 30px;"> Ocena &nbsp; (1-5) </label>
                </div>
                <div class="col-sm-4">
                    <input name="ocena" type="range" min="1" max="5" style="margin-top: 35px;">
                </div>
                <div class="col-sm-4"><label style="margin-left: 10px; margin-top: 30px;"></label></div>
            </div>
            <div class="row">
                <div class="col-sm-12" >
                    <label>Komentar</label>
                    <input type="textarea" name="komentar" class="form-control" rows="5">
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12">
                    <input type="submit" value="Oceni" style="background-color: rgb(6, 47, 10); width: 200px; font-size: 20px; color:white; ">
                </div>
            </div>
        </form>
    </div>
</div>