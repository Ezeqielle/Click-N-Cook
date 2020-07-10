<?php
session_start();
require_once "../bdd/connection.php";
$db = connectDB();
include('../lang/lang.php');

if($_SESSION['lang'] == 'FR') {
    include('../lang/fr-lang.php');
} else {
    include('../lang/en-lang.php');
}

if(isset($_POST['signIn'])) {

    $email = htmlspecialchars($_POST['email']);
    $password = $_POST['password'];
    if(isset($_POST['email']) AND isset($_POST['password']) AND !empty($_POST['email']) AND !empty($_POST['password'])) {

        $reqPassword = $db->prepare('SELECT pwd FROM CLIENT WHERE email = :email');
        $reqPassword->execute(array(
            'email' => $email
        ));
        $passwordFromDB = $reqPassword->fetch();
        if(password_verify($password, $passwordFromDB['pwd'])) {

            $requser = $db->prepare('SELECT * FROM CLIENT WHERE email = :email');
            $requser->execute(array(
                'email' => $email
            ));
            $userData = $requser->fetch();

            if($userData['active'] == true) {

                $_SESSION['id'] = $userData['id'];
                $_SESSION['email'] = $userData['email'];
                $_SESSION['firstname'] = $userData['firstName'];
                $_SESSION['name'] = $userData['lastName'];
                $_SESSION['address'] = $userData['address'];
                $_SESSION['administrator'] = 2;
                $addUser = $db->prepare('UPDATE CLIENT SET lang = :lang WHERE id = :id');
                $addUser->execute(array(
                    'lang' => $_SESSION['lang'],
                    'id' => $userData['id']
                ));

                header('Location: /client/viewShop.php');
                exit;
            } else {
                $error = TXT_INDEX_ERROR1;
            }
        } else {
            $error = TXT_INDEX_ERROR2;
        }
    } else {
        $error = TXT_INDEX_ERROR3;
    }
}
require('indexHeader.php');
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
                        <?php
                        if(isset($_GET['change']) AND !empty($_GET['change']) AND $_GET['change'] == 'success') {
                            echo '<div class="alert alert-success">
                                <strong>' . TXT_INDEX_SUCCESS . ' </strong>' . TXT_INDEX_CHANGE . '</div>';
                        }
                        ?>
                        <form action="index.php" method="post" class="form-signin">
                            <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email" value="<?= isset($email) ? $email : ''; ?>" autofocus>
                            <input type="password" name="password" id="inputPassword" class="form-control" placeholder="<?php echo TXT_INDEX_PASS; ?>">
                            <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="signIn"><?php echo TXT_INDEX_SIGN; ?></button>
                        </form>
                        <center>
                            <a href="forgot.php" class="option"><?php echo TXT_INDEX_FORGOT; ?></a>
                            or
                            <a href="register.php" class="option"><?php echo TXT_INDEX_REGISTER ?></a>
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
include('indexFooter.php');
?>