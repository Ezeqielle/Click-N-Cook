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

if(isset($_POST['register'])) {
    $firstname = htmlspecialchars($_POST['firstname']);
    $name = htmlspecialchars($_POST['name']);
    $email = htmlspecialchars($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $password2 = password_hash($_POST['password2'], PASSWORD_DEFAULT);
    $contactNumber = htmlspecialchars($_POST['contactNumber']);
    $address = htmlspecialchars($_POST['address']);

    if(!empty($_POST['firstname']) AND !empty($_POST['email']) AND !empty($_POST['name']) AND !empty($_POST['password']) AND !empty($_POST['password2']) AND !empty($_POST['contactNumber']) AND !empty($_POST['address']) AND isset($_POST['address']) AND isset($_POST['contactNumber']) AND isset($_POST['firstname']) AND isset($_POST['email']) AND isset($_POST['name']) AND isset($_POST['password']) AND isset($_POST['password2'])) {
        $firstnameLength = strlen($firstname);

        if($firstnameLength <= 50) {
            $nameLength = strlen($name);

            if($nameLength <= 50) {

                if(filter_var($email, FILTER_VALIDATE_EMAIL)) { //verfier que c'est un email valide
                    $reqemail = $db->prepare('SELECT * FROM CLIENT WHERE email = :email');
                    $reqemail->execute(array(
                        'email' => $email
                    ));
                    $emailExist = $reqemail->rowCount();

                    if($emailExist == 0) {

                        if($_POST['password'] == $_POST['password2']) {
                            $passwordLength = strlen($password);

                            if($passwordLength >= 6 AND $passwordLength <= 100) {

                                if(strlen($_POST['contactNumber']) <= 15) {

                                    if(strlen($_POST['address']) <= 255) {

                                        if (isset($_POST['captcha']) AND !empty($_POST['captcha'])) {

                                            if($_POST['captcha'] == $_SESSION['captcha']) {
                                                $insertUser = $db->prepare('INSERT INTO CLIENT(lastName, firstName, email, pwd, contactNumber, address) VALUES(:lastName, :firstName , :email , :password , :contactNumber, :address)');
                                                $insertUser->execute(array(
                                                    'lastName' => $name,
                                                    'firstName' => $firstname,
                                                    'email' => $email,
                                                    'password' => $password,
                                                    'contactNumber' => $contactNumber,
                                                    'address' => $address
                                                ));
                                                $reqUser = $db->prepare('SELECT * FROM CLIENT WHERE email = :email');
                                                $reqUser->execute(array(
                                                    'email' => $email
                                                ));
                                                $userData = $reqUser->fetch();
                                                $_SESSION['email'] = $userData['email'];
                                                $_SESSION['name'] = $userData['lastName'];
                                                header('Location: /sendMail/mail.php');
                                                exit;

                                            } else {
                                                $error = TXT_REGISTER_ERROR1;
                                            }
                                        } else {
                                            $error = TXT_REGISTER_ERROR2;
                                        }
                                    } else {
                                        $error = TXT_REGISTER_ERROR3;
                                    }
                                } else {
                                    $error = TXT_REGISTER_ERROR4;
                                }
                            } else {
                                $error = TXT_REGISTER_ERROR5;
                            }
                        } else {
                            $error = TXT_REGISTER_ERROR6;
                        }
                    } else {
                        $error = TXT_REGISTER_ERROR7;
                    }
                } else {
                    $error = TXT_REGISTER_ERROR8;
                }
            } else {
                $error = TXT_REGISTER_ERROR9;
            }
        } else {
            $error = TXT_REGISTER_ERROR10;
        }
    } else {
        $error = TXT_REGISTER_ERROR11;
    }
}
include('indexHeader.php');
?>
    <main>
        <div class="container-fluid" id="img">
            <img class="img-fluid background-image" src="/images/food.png">
        </div>
        <div class="row" style="margin-bottom: 10px; width: 100%">
        </div>
        <div class="row" style="width: 100%;">
            <section style="border: none; background-color: unset;" class="col-lg-4">
            </section>
            <article style="border: none;" class="col-lg-4 radius">
                <div class="container">
                    <div class="card card-container">
                        <img class="profile-img-card logo-image" src="/images/logo.png" alt="logo.png not found"/>
                        <form class="form-signin" action="register.php" method="post">
                            <input type="firstname" id="inputFirstName" class="form-control" name="firstname" placeholder="<?php echo TXT_REGISTER_PLACEHOLDER1; ?>" value="<?= isset($firstname) ? $firstname : ''; ?>" autofocus>
                            <input type="name" id="inputLastName" class="form-control" name="name" placeholder="<?php echo TXT_REGISTER_PLACEHOLDER2; ?>" value="<?= isset($name) ? $name : ''; ?>">
                            <input type="email" id="inputEmail" class="form-control" name="email" placeholder="Email" value="<?= isset($email) ? $email : ''; ?>">
                            <input type="contactNumber" id="inputContactNumber" class="form-control" name="contactNumber" placeholder="<?php echo TXT_REGISTER_PLACEHOLDER4; ?>" value="<?= isset($contactNumber) ? $contactNumber : ''; ?>">
                            <input type="address" id="inputAddress" class="form-control" name="address" placeholder="<?php echo TXT_REGISTER_PLACEHOLDER5; ?>" value="<?= isset($address) ? $address : ''; ?>">
                            <input type="password" id="inputPassword" class="form-control" name="password" placeholder="<?php echo TXT_REGISTER_PLACEHOLDER6; ?>">
                            <input type="password" id="inputPassword" class="form-control" name="password2" placeholder="<?php echo TXT_REGISTER_PLACEHOLDER3; ?>">
                            <div>
                                <center>
                                    <img src="/captcha/captcha.php">
                                </center>
                            </div>
                            <input type="captcha" id="inputCaptcha" class="form-control" name="captcha" placeholder="<?php echo TXT_REGISTER_PLACEHOLDER7; ?>">
                            <button class="btn btn-lg btn-primary btn-block btn-signin Register" href="#" type="submit" name="register"><?php echo TXT_INDEX_REGISTER; ?></button>
                            <center>
                                <a href="index.php" class="option"><?php echo TXT_INDEX_LOGIN; ?></a>
                            </center>
                        </form>
                    </div>
                </article>
                <section style="border: none; background-color: unset" class="col-lg-4">
                </section>
            </div>
            <div class="row" style="margin-top: 10px; width: 100%">
            </div>
        </div>
    </main>
<?php
include('indexFooter.php')
?>