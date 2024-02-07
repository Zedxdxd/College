<!-- Milica Cvetkovic 2020/0003 -->
    <div class="login-and-registration2">
        <div class="container">

            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12" id="registration">
                    <h3 class="mb-4 pb-2 pb-md-0 mb-md-5">Registrujte se</h3>
                    <form name="registracijaform" action="<?= site_url("GostController/registracijaSubmit") ?>" method="post">
                        <?php if(!empty($poruke['prazno'])) {
                            echo "<div class='alert alert-dark' role='alert'>";
                            echo "<span>{$poruke['prazno']}</span>";
                             echo "</div>";
                        }
                        ?>
                        <div class="form-outline mb-4">
                            <label class="form-label" for="firstname">Ime</label>
                            <input type="text" name="firstname" id="firstname" class="form-control form-control-lg" />
                        </div>
                        <div class="form-outline mb-4">
                            <label class="form-label" for="lastname">Prezime</label>
                            <input type="text" name="lastname" id="lastname" class="form-control form-control-lg" />
                        </div>
                        <div class="form-outline mb-4">
                            <label class="form-label" for="phonenumber">Broj telefona</label>
                            <input type="text" name="phonenumber" id="phonenumber" class="form-control form-control-lg" />
                        </div>
                        <div class="form-outline mb-4">
                            <label class="form-label" for="email">Email</label>
                            <input type="text" name="email" id="email" class="form-control form-control-lg" />
                            <br>
                            <?php if(!empty($poruke['email'])) {
                                 echo "<div class='alert alert-dark' role='alert'>";
                                echo "<span>{$poruke['email']}</span>";
                                echo "</div>";
                            }
                            ?>
                        </div>


                        <div class="d-md-flex justify-content-start align-items-center mb-4 py-2">
                            <label class="mb-0 me-4">Tip:&nbsp;&nbsp;</label>
                            <div class="form-check form-check-inline mb-0 me-4">
                                <input class="form-check-input" type="radio" name="choice" id="korisnik"
                                    value="Korisnik" />
                                <label class="form-check-label" for="korisnik">Korisnik</label>
                            </div>

                            <div class="form-check form-check-inline mb-0 me-4">
                                <input class="form-check-input" type="radio" name="choice" id="privatnik"
                                    value="Privatnik" />
                                <label class="form-check-label" for="privatnik">Privatnik</label>
                            </div>
                        </div>

                        <div class="form-outline mb-4">
                            <label class="form-label" for="username">Korisničko ime</label>
                            <input type="text" name="username" id="username" class="form-control form-control-lg" />
                            <br>
                            <?php if(!empty($poruke['korisnickoime'])) {
                                echo "<div class='alert alert-dark' role='alert'>";
                                echo "<span>{$poruke['korisnickoime']}</span>";
                                echo "</div>";
                            }
                            ?>
                        </div>
                        <div class="form-outline mb-4">
                            <label class="form-label" for="password">Lozinka</label>
                            <input type="password" name="password" id="password" class="form-control form-control-lg" />
                            <br>
                            <?php if(!empty($poruke['lozinka'])){ 
                                 echo "<div class='alert alert-dark' role='alert'>";
                                echo "<span>{$poruke['lozinka']}</span>";
                                echo "</div>";
                            }
                            ?>
                        </div>
                        <div class="form-outline mb-4">
                            <label class="form-label" for="checkpassword">Potvrdi lozinku</label>
                            <input type="password" name="checkpassword" id="checkpassword" class="form-control form-control-lg" />
                            <br>
                            <?php if(!empty($poruke['ponovna'])) {
                                echo "<div class='alert alert-dark' role='alert'>";
                                echo "<span>{$poruke['ponovna']}</span>";
                                echo "</div>";
                            }
                            ?>
                        </div>
                        <input type='submit' class="btn btn-primary btn-block mb-4 submit-btn" value="Potvrdi">
                        <p style="color:rgb(85, 84, 84);text-align:left;font-size:13px;margin-right:-150px;margin-left:-120px"><strong>Napomena:</strong> Morate čekati potvrdu profila od administratora da biste se ulogovali.</p>
                    </form>
                    
                </div>
                
            </div>

        </div>
    </div>
    