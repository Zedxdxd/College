<!-- Milica Cvetkovic 2020/0003 -->
   <div class="login-and-registration">
      <div class="container">
         <div class="row">
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12" id="login">
               <h3 class="mb-4 pb-2 pb-md-0 mb-md-5">Ulogujte se</h3>
               <form name="loginform" action="<?= site_url("GostController/loginSubmit") ?>" method="post">
                    <?php if(!empty($poruke['prazno'])){
                        echo "<div class='alert alert-dark' role='alert'>";
                        echo "<span>{$poruke['prazno']}</span>";
                        echo "</div>";
                    }
                    ?>
                   <div class="form-outline mb-4">
                     <input type="text" name="username-input" id="username-input" class="form-control" />
                     <label class="form-label" for="username-input">Korisničko ime</label>
                     <br>
                     <?php if(!empty($poruke['korisnickoime'])) {
                         echo "<div class='alert alert-dark' role='alert'>";
                        echo "<span>{$poruke['korisnickoime']}</span>";
                        echo "</div>";
                     }
                    ?>
                   </div>
                  <div class="form-outline mb-4">
                     <input type="password" name="password-input" id="password-input" class="form-control" />
                     <label class="form-label" for="password-input">Lozinka</label>
                     <br>
                     <?php if(!empty($poruke['lozinka'])) {
                         echo "<div class='alert alert-dark' role='alert'>";
                        echo "<span>{$poruke['lozinka']}</span>";
                        echo "</div>";
                     }
                     ?>
                  </div>
                  <div class="row mb-4">
                     <div class="col">
                        <a href="<?= site_url("GostController/zaboravljenaLozinka") ?>">Zaboravili ste lozinku?</a>
                     </div>
                  </div>
                   <input type='submit' class="btn btn-primary btn-block mb-4 confirm-btn" value="Potvrdi">
               </form>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12" id="logreg">
               <h3 class="mb-4 pb-2 pb-md-0 mb-md-5">Nemate nalog?<br>Registrujte se!</h3>
               <button type="button" class="btn btn-primary btn-block mb-4 confirm-btn"
                  onclick="window.location.href='<?php echo site_url("GostController/registracija")?>'">Registruj se</button>
            </div>
         </div>
      </div>
   </div>
   