<!-- Milica Cvetkovic 2020/0003 -->
      <div class="login-and-registration">
        <div class="container">
            <div class="row">
                <div class="col-xl-3 col-lg-3 col-md-6 col-sm-12" id="forgotten-password">
                    <h3 class="mb-4 pb-2 pb-md-0 mb-md-5">Unesite email za povratak lozinke</h3>
                    <form action="<?= site_url("GostController/zaboravljenaLozinkaSubmit") ?>" method="post">
                        <div class="form-outline mb-4">
                           <label class="form-label" for="username-input">Email</label>
                           <input type="email" name="emailReset" id="username-input" class="form-control" />
                        </div>
                        <button type="submit" class="btn btn-primary btn-block mb-4 confirm-btn">Potvrdi</button>
                    </form>
                </div>
            </div>
        </div>
      </div>
      