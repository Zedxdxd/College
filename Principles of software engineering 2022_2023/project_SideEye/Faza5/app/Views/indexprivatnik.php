<!-- Željko Urošević 2020/0073 -->

<section>
   <div class="banner-main">
      <div class="container-fluid px-0" style="margin-right: unset">
         <img src="<?php echo base_url('images/GettyImages-185298837.jpg') ?>" alt="#" style="
   width: 100vw; 
   position: relative;
   left: 50.55%;  
   margin-left: -50vw;
   width: 100%;" />
         <div class="text-bg col">
            <h1><strong class="black">Dobrodošli</strong><br><strong class="white">na </strong> <strong class="boja">ePutuj!</strong></h1>
         </div>
      </div>
   </div>
</section>
<div id="onama" class="about" style="background-color:white;">
   <div class="container">
      <div class="row">
         <div class="col-md-12 ">
            <div class="titlepage">
               <br /><br />
               <h2>O NAMA</h2>
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


<div id="travel" class="traveling">
   <div class="container" style="display: unset;">
      <div class="row">
         <div class="col-md-12 ">
            <div class="titlepage" id="ponude">
               <h2>Postavljene ponude</h2>
            </div>
         </div>
      </div>
      <?php
      if (empty($ponude)) {
         echo '<div class="titlepage" id="ponude">
               <h3>Nema postavljenih ponuda</h3>
               </div>';
         echo '<div class="justify-content-center bg about" style="padding-bottom: unset;">
         <a href="'; echo base_url("PrivatnikController/napraviPonudu"); echo '"style="background-color: rgb(63, 155, 183); ">Napravi ponudu
         </a></div>';
      } else {
         echo '<div class="row">';
         echo '<div id="postavljenePonude" class="carousel slide" data-bs-ride="carousel" data-bs-interval="false">';
         echo '<div class="carousel-inner">';
         $postavljeniDivovi = false;
         foreach ($ponude as $i => $ponuda) {
            if ($i % 3 == 0) {
               if ($i == 0) {
                  echo '<div class="carousel-item active">';
               } else {
                  echo '<div class="carousel-item">';
               }
               echo '<div class="row">';
            }
            $db = \Config\Database::connect();
            $builder = $db->table('mesto');
            $mestoOd = ($builder->where("SifM", $ponuda->SifMesOd)->get()->getResult())[0]->Naziv;
            $mestoDo = ($builder->where("SifM", $ponuda->SifMesDo)->get()->getResult())[0]->Naziv;
            $builder = $db->table("prevoznosredstvo");
            $prevoznoSredstvo = ($builder->where("SifSred", $ponuda->SifSred)->get()->getResult())[0]->Naziv;
            $dan = explode("-", $ponuda->DatumOd)[2];
            $mesec = explode("-", $ponuda->DatumOd)[1];
            $godina = explode("-", $ponuda->DatumOd)[0];
            $datum = $dan . "/" . $mesec . "/" . $godina;
            $builder = $db->table("rezervacija");
            $rezervacije = $builder->where("SifP", $ponuda->SifP)->get()->getResult();
            echo '<div class="col-xl-4 col-lg-4 col-md-6 col-sm-12">';
            echo "<a href='";
            echo base_url("PrivatnikController/prikazPonude/{$ponuda->SifP}");
            echo "'>"; 
            echo '<div class="traveling-box" >';
            echo '<i><img src="'.base_url("images/ponude/{$ponuda->Slika}").'" alt="icon" style="width:250px;height:200px" style="object-fit: scale-down; margin-top: 10px" /></i>
               <h2>' . $mestoOd . ' -> ' . $mestoDo . '</h2>
               ' . $datum . ' <br><img src="';
            echo base_url("images/{$prevoznoSredstvo}_transparent.png");
            echo '"';
            echo ' height="35" width="35"><br>
               ' . $ponuda->BrMesta . ' <span><img src="';
            echo base_url("images/stickman.png");
            echo '"';
            echo ' height="15" width="15"></span>
               <h3>' . $ponuda->CenaKarte . '€</h3>
               </div>';
            echo '</a>';
            echo '</div>';
            if ($i % 3 == 2) {
               $postavljeniDivovi = true;
               echo '</div>';
               echo '</div>';
            } else {
               $postavljeniDivovi = false;
            }
         }
         if (!$postavljeniDivovi) {
            echo '</div>';
            echo '</div>';
         }
         echo '</div>';
         echo '</div>';
         echo '</div>';

         echo '<div class="row" style="justify-content: center;">
            <div class="col-md-12">
               <button class="carousel-control-prev" type="button" data-bs-target="#postavljenePonude" data-bs-slide="prev" style="left: 30%;">
                  <span class="carousel-control-prev-icon" style="color: black !important; 
                                                               font-size: 50px;
                                                               background-image: unset;">&lt;</span>
               </button>
               <button class="carousel-control-next" type="button" data-bs-target="#postavljenePonude" data-bs-slide="next" style="left: 55%">
                  <span class="carousel-control-next-icon" style="color: black !important; 
                                                               font-size: 50px; 
                                                               background-image: unset;">&gt;</span>
               </button>
            </div>
         </div>';
      }
      ?>
   </div>
</div>