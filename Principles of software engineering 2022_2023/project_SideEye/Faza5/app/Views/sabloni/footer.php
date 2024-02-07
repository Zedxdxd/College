<footer>
   <div id="contact" class="footer">
      <div class="container">
         <div class="row pdn-top-30">
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12" style="color:#004043">
               <div class="Follow">
                  <h3>Kontakt:</h3>
                  <span>Bulevar Kralja Aleksandra 73, <br>Palilula,<br>
                     Beograd<br>
                     +381 65 432 1010</span>
               </div>
            </div>
            <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12">
               <div class="Follow">
                  <h3>Prečice:</h3>
                  <ul class="link">
                     <li> <a href="index#onama">O nama</a></li>
                  </ul>
               </div>
            </div>
            <div class="col-xl-6 col-lg-6 col-md-6 col-sm-12">
               <form action='<?php echo base_url($kontroler."/komentar")?>' method="get">
                  <div class="Follow">
                     <h3>Ostavite komentar:</h3>

                     <div class="row">
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                           <input class="Newsletter" placeholder="Name" type="text" name="ime">
                        </div>
                        <div class="col-xl-6 col-lg-6 col-md-6 col-sm-6">
                           <input class="Newsletter" placeholder="Email" type="text" name='email'>
                        </div>
                        <div class="col-xl-12 col-lg-12 col-md-12 col-sm-12">
                           <textarea class="textarea" placeholder="Komentar" type="text" name='komentar'></textarea>
                        </div>
                        <?php  
                           echo '<input type="hidden" name="stranica" value='.$stranica.'>';
                        
                        ?>
                     </div>
                     <input type="submit" class="Subscribe" value="Pošalji">
                  </div>
               </form>
            </div>
         </div>
      </div>
   </div>
</footer>

</body>

</html>