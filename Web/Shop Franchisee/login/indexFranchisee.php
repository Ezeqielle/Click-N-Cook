<?php
session_start();
require_once "../bdd/connection.php";
$db = connectDB();
include('../extensions/lang.php');
if(isset($_POST['signInFranchisee'])) {

    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    if(isset($_POST['email']) AND isset($_POST['password']) AND !empty($_POST['email']) AND !empty($_POST['password'])) {

        $reqPassword = $db->prepare('SELECT password FROM FRANCHISEE WHERE email = :email');
        $reqPassword->execute(array(
            'email' => $email
        ));
        $passwordFromDB = $reqPassword->fetch();
        if(password_verify($password, $passwordFromDB['password'])) {

            $requser = $db->prepare('SELECT * FROM FRANCHISEE WHERE email = :email');
            $requser->execute(array(
                'email' => $email
            ));
            $userData = $requser->fetch();

            if($userData['active'] == true) {
                if($userData['admin'] == true) {
                    $_SESSION['id'] = $userData['id'];
                    $_SESSION['email'] = $userData['email'];
                    $_SESSION['firstname'] = $userData['firstName'];
                    $_SESSION['name'] = $userData['lastName'];
                    $_SESSION['administrator'] = $userData['admin'];
                    header('Location: ../admin/shopAdmin.php');
                    exit;
                } else {
                    $_SESSION['id'] = $userData['id'];
                    $_SESSION['email'] = $userData['email'];
                    $_SESSION['firstname'] = $userData['firstName'];
                    $_SESSION['name'] = $userData['lastName'];
                    $_SESSION['entranceFee'] = $userData['entranceFee'];
                    $_SESSION['administrator'] = $userData['admin'];
                    header('Location: ../franchisee/shop.php');
                    exit;
                }
            } else {
                if($_SESSION['lang'] == 'EN') {
                    $error = 'Check your mails to activate your account';
                } else {
                    $error = 'Consultez vos mails pour activer votre compte';
                }
            }
        } else {
            if($_SESSION['lang'] == 'EN') {
                $error = 'Wrong email or password !';
            } else {
                $error = 'Mauvais email ou mot de passe !';
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
require('indexHeader.php');
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
                        <?php
                        if(isset($_GET['change']) AND !empty($_GET['change']) AND $_GET['change'] == 'success') {
                            echo '<div class="alert alert-success">
                                <strong>Succès!</strong> Votre changement de mot de passe a été effectué avec succès.
                            </div>';
                        }
                        ?>
                        <form action="indexFranchisee.php" method="post" class="form-signin">
                            <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email" value="<?= isset($email) ? $email : ''; ?>" autofocus>
                            <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password">
                            <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="signInFranchisee">Sign in</button>
                        </form>
                        <center>
                            <a href="forgotFranchisee.php" class="option">Forgot the password ?</a>
                            or
                            <a href="registerFranchisee.php" class="option">Create account.</a>
                            <?= isset($error) ? '<font color="red">' . $error . "</font><br>" : ''; ?>
                        </center>
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