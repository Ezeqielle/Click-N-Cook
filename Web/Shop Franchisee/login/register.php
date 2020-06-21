<?php
session_start();
require_once "../bdd/connection.php";
$db = connectDB();
include('../extensions/lang.php');
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
                                                header('Location: ../sendMail/mail.php');
                                                exit;

                                            } else {
                                                if($_SESSION['lang'] == 'EN') {
                                                    $error = "Captcha is incorrect";
                                                } else {
                                                    $error = "Captcha incorrect";
                                                }
                                            }
                                        } else {
                                            if($_SESSION['lang'] == 'EN') {
                                                $error = "Captcha isn't set";
                                            } else {
                                                $error = "Captcha n'est pas remplit.";
                                            }
                                        }
                                    } else {
                                       if($_SESSION['lang'] == 'EN') {
                                            $error = "Your address musn't cross 500 characters !";
                                        } else {
                                            $error = "Votre adresse ne doit pas dépasser 500 caractères !";
                                        }
                                    }
                                } else {
                                    if($_SESSION['lang'] == 'EN') {
                                        $error = "Your contact number musn't cross 15 characters !";
                                    } else {
                                        $error = "Votre numéro de téléphone ne doit pas dépasser 15 caractères !";
                                    }
                                }
                            } else {
                                if($_SESSION['lang'] == 'EN') {
                                    $error = "Your password must have between 6 and 100 characters";
                                } else {
                                    $error = "Votre mot de passe doit comporter entre 6 et 100 caractères";
                                }
                            }
                        } else {
                            if($_SESSION['lang'] == 'EN') {
                                $error = "Your passwords don't match !";
                            } else {
                                $error = "Vos mots de passe ne correspondent pas !";
                            }
                        }
                    } else {
                        if($_SESSION['lang'] == 'EN') {
                            $error = "Email address already use !";
                        } else {
                            $error = "Adresse email déjà utilisée !";
                        }
                    }
                } else {
                    if($_SESSION['lang'] == 'EN') {
                        $error = "Invalid email address !";
                    } else {
                        $error = "Adresse email invalide !";
                    }
                }
            } else {
                if($_SESSION['lang'] == 'EN') {
                    $error = "Your name musn't cross 50 characters !";
                } else {
                    $error = "Votre nom ne doit pas dépasser 50 caractères !";
                }
            }
        } else {
            if($_SESSION['lang'] == 'EN') {
                $error = "Your firstname musn't cross 50 characters !";
            } else {
                $error = "Votre prénom ne doit pas dépasser 50 caractères !";
            }
        }
    } else {
        if($_SESSION['lang'] == 'EN') {
            $error = "Please fill in all the fields !";
        } else {
            $error = "Veuillez remplir tous les champs !";
        }
    }
}
include('indexHeader.php');
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
                        <?php
                        if($_SESSION['lang'] == 'EN') {
                            ?>
                            <img class="profile-img-card logo-image" src="../images/logo.png" alt="logo.png not found"/>
                            <form class="form-signin" action="register.php" method="post">
                                <input type="firstname" id="inputFirstName" class="form-control" name="firstname" placeholder="First name" value="<?= isset($firstname) ? $firstname : ''; ?>" autofocus>
                                <input type="name" id="inputLastName" class="form-control" name="name" placeholder="Last name" value="<?= isset($name) ? $name : ''; ?>">
                                <input type="email" id="inputEmail" class="form-control" name="email" placeholder="Email" value="<?= isset($email) ? $email : ''; ?>">
                                <input type="contactNumber" id="inputContactNumber" class="form-control" name="contactNumber" placeholder="Contact Number" value="<?= isset($contactNumber) ? $contactNumber : ''; ?>">
                                <input type="address" id="inputAddress" class="form-control" name="address" placeholder="Address" value="<?= isset($address) ? $address : ''; ?>">
                                <input type="password" id="inputPassword" class="form-control" name="password" placeholder="Password">
                                <input type="password" id="inputPassword" class="form-control" name="password2" placeholder="Password verification">
                                <div>
                                    <center>
                                        <img src="../captcha/captcha.php">
                                    </center>
                                </div>
                                <input type="captcha" id="inputCaptcha" class="form-control" name="captcha" placeholder="Enter captcha">
                                <button class="btn btn-lg btn-primary btn-block btn-signin Register" href="#" type="submit" name="register">Register</button>
                                <center>
                                    <a href="index.php" class="option">Connexion</a>
                                </center>
                            </form>
                            <?= isset($error) ? '<font color="red">' . $error . "</font>" : '';
                        } else {
                            ?>
                            <img class="profile-img-card logo-image" src="../images/logo.png" alt="logo.png not found"/>
                            <form class="form-signin" action="register.php" method="post">
                                <input type="firstname" id="inputFirstName" class="form-control" name="firstname" placeholder="Prénom" value="<?= isset($firstname) ? $firstname : ''; ?>" autofocus>
                                <input type="name" id="inputLastName" class="form-control" name="name" placeholder="Nom de famille" value="<?= isset($name) ? $name : ''; ?>">
                                <input type="email" id="inputEmail" class="form-control" name="email" placeholder="Email" value="<?= isset($email) ? $email : ''; ?>">
                                <input type="contactNumber" id="inputContactNumber" class="form-control" name="contactNumber" placeholder="Numéro de Téléphone" value="<?= isset($contactNumber) ? $contactNumber : ''; ?>">
                                <input type="address" id="inputAddress" class="form-control" name="address" placeholder="Adresse" value="<?= isset($address) ? $address : ''; ?>">
                                <input type="password" id="inputPassword" class="form-control" name="password" placeholder="Mot de passe">
                                <input type="password" id="inputPassword" class="form-control" name="password2" placeholder="Mot de passe">
                                <div>
                                    <center>
                                        <img src="../captcha/captcha.php">
                                    </center>
                                </div>
                                <input type="captcha" id="inputCaptcha" class="form-control" name="captcha" placeholder="Entrer le captcha">
                                <button class="btn btn-lg btn-primary btn-block btn-signin Forgot" href="#" type="submit" name="register">S'inscrire</button>
                            </form>
                        <center>
                            <a href="index.php" class="option">Connexion</a>
                        </center>
                            </form>
                            <?= isset($error) ? '<font color="red">' . $error . "</font>" : '';
                        }
                        ?>
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