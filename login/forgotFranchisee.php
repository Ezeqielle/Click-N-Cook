<?php
session_start();
require('indexHeader.php');
include('../lang/lang.php');

if($_SESSION['lang'] == 'FR') {
    include('../lang/fr-lang.php');
} else {
    include('../lang/en-lang.php');
}

if(isset($_POST['search'])) {

    $email = htmlspecialchars($_POST['email']);
    if(isset($_POST['email']) AND !empty($_POST['email'])) {

        $reqEmail = $db->prepare('SELECT email FROM FRANCHISEE WHERE email = :email');
        $reqEmail->execute(array(
            'email' => $email
        ));
        $emailDB = $reqEmail->fetch();
        if($emailDB['email'] == $_POST['email']) {



            $reqUser = $db->prepare('SELECT * FROM FRANCHISEE WHERE email = :email');
            $reqUser->execute(array(
                'email' => $email
            ));
            $userData = $reqUser->fetch();
            $_SESSION['email'] = $userData['email'];
            $_SESSION['name'] = $userData['lastName'];

            header('Location: /sendMail/passwordMailFranchisee.php');
            exit;
        } else {
            $error = TXT_INDEX_ERROR4;
        }
    } else {
        $error = TXT_INDEX_ERROR3;
    }
}

?>
    <main>
        <div class="container-fluid" id="img">
            <img class="img-fluid background-image" src="../images/food.png">
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
                            <p class="Forgot"><?php echo TXT_INDEX_MAIL; ?></p>
                        </center>
                        <form class="form-signin" action="forgot.php" method="post">
                            <input type="email" id="inputEmail" class="form-control" placeholder="Email" name="email" required autofocus>
                            <button class="btn btn-lg btn-primary btn-block btn-signin Forgot" href="#" type="submit" name="search"><?php echo TXT_INDEX_CONTINUE; ?></button>
                        </form>
                        <center>
                            <a href="indexFranchisee.php" class="option"><?php echo TXT_INDEX_LOGIN; ?></a>
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
include('indexFooterFranchisee.php');
?>