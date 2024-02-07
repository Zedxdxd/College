<section>
   <!--Milica Cvetković 2020/0003-->
   <div class="banner-main">
      <img src="<?php echo base_url('images/header-bgsilk.jpg') ?>" alt="#" style="width:100%;" />
      <!-- <div class="container"> -->
      <div class="text-bg">
         <div class="container">
            <form class="main-form" action="<?= site_url("KorisnikController/pretragaPonuda") ?>" method="post">
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
      </div>
   </div>
   </div>
</section>

<div id="about" class="about">
   <div class="container">
      <div class="row">
         <div class="col-md-12 ">
            <div class="titlepage">
               <h2 style="margin-top: 100px;" id="onama">O nama</h2>
               <span> </span>
            </div>
         </div>
      </div>
   </div>
   <div class="bg">
      <div class="container">
         <div class="row">
            <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
               <div class="about-box">
                  <p> <span>ePutuj je najveća svetska zajednica za deljenje prevoza koja povezuje putnike i vozače
                        koji putuju u istom smeru i omogućava im da podele putne troškove. Prosečna razdaljina između
                        dve destinacije na kojoj se deli prevoz iznosi oko 340 km.
                        Razlog zbog kog naši članovi koriste ePutuj je da podele troškove. Upravo zbog toga, naš
                        servis nema za cilj da omogući vozačima da zarade od putovanja.
                     </span></p>
                  <div class="palne-img-area" style="right: -165px">
                     <img src="<?php echo base_url('images/plane-img.png') ?>" alt="images">
                  </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<div id="travel" class="traveling" style="padding-top: 15px;">
   <div class="container">
      <div class="row">
         <div class="col-md-12 ">
            <div class="titlepage">
               <h2>Opcije za putovanja</h2>
               <span>Sa nama putujte raznim prevoznim sredstvima, a očekuju vas i brojne nagrade!</span>
            </div>
         </div>
      </div>

   </div>
</div>
<div class="London">
   <div class="container">
      <div class="row">
         <div class="col-md-12">
            <div class="titlepage">
               <h2 style="color: rgb(159, 41, 41);">U London za vikend! Jedno mesto slobodno!</h2>
            </div>
         </div>
      </div>
   </div>
   <div class="container-fluid">
      <div class="London-img">
         <figure><img src="<?php echo base_url('images/London.jpg') ?>" alt="img" /></figure>
      </div>
   </div>
</div>