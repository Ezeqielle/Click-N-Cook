<?php
    session_start();
    require('indexHeader.php');
    include('../extensions/lang.php');

    if(isset($_POST['search'])) {
    
        $email = htmlspecialchars($_POST['email']);
        if(isset($_POST['email']) AND !empty($_POST['email'])) {
    
            $reqEmail = $db->prepare('SELECT email FROM CLIENT WHERE email = :email');
            $reqEmail->execute(array(
                'email' => $email
            ));
            $emailDB = $reqEmail->fetch();
            if($emailDB['email'] == $_POST['email']) {

    
    
                $reqUser = $db->prepare('SELECT * FROM CLIENT WHERE email = :email');
                $reqUser->execute(array(
                    'email' => $email
                ));
                $userData = $reqUser->fetch();
                $_SESSION['email'] = $userData['email'];
                $_SESSION['name'] = $userData['lastName'];
    
                header('Location: ../sendMail/passwordMail.php');
                exit;
            } else {
                if($_SESSION['lang'] == 'EN') {
                    $error = 'Wrong email!';
                } else {
                    $error = 'Mauvais email !';
                }
            }
        } else {
            if($_SESSION['lang'] == 'EN') {
                $error = 'Please fill in all the fields';
            } else {
                $error = 'Veuillez remplir tous les champs';
            }
        }
    }

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
    include('indexFooter.php');
?>