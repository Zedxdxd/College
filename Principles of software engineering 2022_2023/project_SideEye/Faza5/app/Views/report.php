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
<div class="report">
   <div class="container" style="justify-content: center;">
      <div class="col-12">
         <div class="row">
            <div class="col-4"></div>
            <div class="col-4" id="login">
               <h3 class="mb-4 pb-2 pb-md-0 mb-md-5">Report</h3>
               <form action="<?=base_url("KorisnikController/report")?>" method="post">
                  <div class="form-outline mb-4">
                     <label class="form-label" for="username-input">Korisničko ime za prijavu</label>
                     <input type="text" id="username-input" name="SifK" class="form-control" />
                  </div>
                  <div class="form-outline mb-4">
                     <label class="form-label" for="password-input">Komentar</label>
                     <textarea id="password-input" name="komentar" class="form-control"></textarea>
                  </div>
                  <button type="submit" class="btn btn-primary btn-block mb-4 confirm-btn">Potvrdi</button>
               </form>
            </div>
            <div class="col-4"></div>
         </div>
      </div>
   </div>
</div>