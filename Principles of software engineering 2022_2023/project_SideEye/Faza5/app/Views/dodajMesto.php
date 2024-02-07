<section>
    <div class="container-fluid" style="background-color: white; justify-content: center;">
        <div class="row">
            <div class="col-5"></div>
            <div class="col-4">
                
                <form action="<?= site_url("AdminController/dodavanjeMesta") ?>" method="post" >
                    <br/>
                    <br/>
                    <h1><strong>Unesite naziv mesta</strong></h1>
                    <br/>
                    <input type="text" placeholder="Mesto" name="mesto" />
                    <br/>
                    <br/>
                    <button class="dugme">Potvrdi</button>
                </form>
                <br/>
                <br/>
                <p style="color:green;font-weight:bold">
                    <?php
                        if(!empty($poruka)){ 
                            echo $poruka;
                        }
                    ?>
                </p>
                <p style="color:red;font-weight:bold">
                    <?php
                        if(!empty($poruka_neuspeh)){ 
                            echo $poruka_neuspeh;
                        }
                    ?>
                </p>
            </div>
            <div class="col-3"></div>  
        </div>
    </div>
</section>