<?php
    require('../extensions/indexHeader.php');
?>
    <main>
        <div class="container-fluid" id="img">
            <img class="img-fluid background-image" src="../images/food.jpg">
        </div>
        <div class="row" style="margin-bottom: 10px; width: 100%">
        </div>
        <div class="row" style="width: 100%;">
            <section style="border: none; background-color: unset;" class="col-lg-4">
            </section>
            <article style="border: none;" class="col-lg-4 radius">
                <div class="container">
                    <div class="card card-container">
                        <img class="profile-img-card logo-image" src="../images/logo.png" alt="logo.png not found"/>
                        <center>
                            <p class="Forgot">Saisissez l'addresse e-mail lié à votre compte.</p>
                        </center>
                        <form class="form-signin" action="forgot.php" method="post">
                            <input type="email" id="inputEmail" class="form-control" placeholder="Email" name="email" required autofocus>
                            <button class="btn btn-lg btn-primary btn-block btn-signin Forgot" href="#" type="submit" name="search">Continuer</button>
                        </form>
                        <center>
                            <a href="index.php" class="option">Connexion</a>
                        </center>
                        <?php
                        if(isset($error)) {
                            echo '<font color="red">' . $error . '</font>';
                        }
                        ?>
                    </div>
                </div>
            </article>
            <section style="border: none; background-color: unset" class="col-lg-4">
            </section>
        </div>
        <div class="row" style="margin-top: 10px; width: 100%">
        </div>
    </main>
<?php
    include('../extensions/indexFooter.php');
?>