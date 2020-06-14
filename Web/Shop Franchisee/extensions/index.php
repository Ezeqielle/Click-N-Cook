<?php
    require('../extensions/indexHeader.php');
?>

    <main>
        <div class="container-fluid" id="img">
            <img class="img-fluid background-image" src="../images/food.jpg">
        </div>
        <div class="row" style="width: 100%">
            <section style="border: none; background-color: unset" class="col-lg-4">
            </section>
            <article class="col-lg-4">
                <div class="container">
                    <div class="card card-container">
                        <img class="profile-img-card logo-image" src="../images/logo.png" alt="logo.png not found"/>
                        <?php
                        /*if(isset($_GET['change']) AND !empty($_GET['change']) AND $_GET['change'] == 'success') {
                            echo '<div class="alert alert-success">
						<strong>Success!</strong> Your change of password was successfully.
					</div>';
                        }*/
                        ?>
                        <form action="index.php" method="post" class="form-signin">
                            <input type="email" name="email" id="inputEmail" class="form-control" placeholder="Email" value="<?= isset($email) ? $email : ''; ?>" autofocus>
                            <input type="password" name="password" id="inputPassword" class="form-control" placeholder="Password">
                            <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit" name="signIn">Sign in</button>
                        </form>
                        <!--<a href="forgot.php" class="option">Forgot the password ?</a>
                        or
                        <a href="register.php" class="option">Create account.</a>-->
                        <?= isset($error) ? '<font color="red">' . $error . "</font><br>" : ''; ?>
                    </div>
                </div>
            </article>
            <section style="border: none; background-color: unset" class="col-lg-4">
            </section>
        </div>
    </main>
<?php
    include('../extensions/indexFooter.php');
?>