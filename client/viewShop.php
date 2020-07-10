<?php
session_start();
require_once "../bdd/connection.php";
$db = connectDB();

if(isset($_POST['choose'])) {
    $_SESSION['idFranchisee'] = $_POST['id'];
    header('Location: shopClient.php');

}
if (isset($_SESSION['id']) AND !empty($_SESSION['id'])) {

    $reqClient = $db->query('SELECT * FROM CLIENT');
    while($clientData = $reqClient->fetch()) {
        $reqPurchaseClient = $db->query('SELECT COUNT(*) FROM PURCHASECLIENT WHERE idClient = ' . $clientData['id']);
        $purchaseClientData = $reqPurchaseClient->fetch();
        if ($purchaseClientData[0] > 10 AND $purchaseClientData[0] < 20) {
            $addAdvantage = $db->prepare('UPDATE CLIENT SET advantage = :advantage WHERE id = :idClient');
            $addAdvantage->execute(array(
                'advantage' => 5,
                'idClient' => $clientData['id']
            ));
        } else if ($purchaseClientData[0] > 20 AND $purchaseClientData[0] < 50) {
            $addAdvantage = $db->prepare('UPDATE CLIENT SET advantage = :advantage WHERE id = :idClient');
            $addAdvantage->execute(array(
                'advantage' => 10,
                'idClient' => $clientData['id']
            ));
        } else if($purchaseClientData[0] > 50) {
            $addAdvantage = $db->prepare('UPDATE CLIENT SET advantage = :advantage WHERE id = :idClient');
            $addAdvantage->execute(array(
                'advantage' => 15,
                'idClient' => $clientData['id']
            ));
        }
    }

    require('../extensions/header.php');
    ?>
    <main>
        <div class="row">
            <section class="col-lg-3">
            </section>
            <section class="col-lg-6">
                <div class="row">
                    <article class="col-lg-12 radius">
                    </article>
                </div>
                <?php
                $reqFranchisee = $db->query('SELECT * FROM FRANCHISEE WHERE active = TRUE AND entranceFee = TRUE AND admin = FALSE AND stripeKey IS NOT NULL');

                echo '<div class="row" id="result">';
                while($franchiseeData = $reqFranchisee->fetch()){
                    $reqLocationTruck = $db->query('SELECT location FROM TRUCK WHERE idFranchisee = ' .$franchiseeData["id"]);
                    $locationTruckData = $reqLocationTruck->fetch();

                    echo '<article name="franchisee" class="col-lg-4 radius">';
                    echo '<form action="viewShop.php" method="post" class="form-signin">';
                    echo '<label>Click\'N Cook</label><br>';
                    echo $franchiseeData['description'] . '<br>';
                    echo TXT_CLIENT_LOCATION . $locationTruckData['location'] . '<br>';
                    echo '<input type="hidden" name="id" value="' . $franchiseeData['id'] . '">';
                    echo '<button type="submit" name="choose" id="btn" class="btn btn-default btn-sm">' . TXT_CLIENT_CHOOSE . '</button>';
                    echo '</form>';
                    echo '</article>';
                }
                $reqFranchisee->closeCursor();
                echo '</div>';
                ?>
            </section>
        </div>


        <script>
            window.addEventListener("DOMContentLoaded", () => {
                const btn = document.querySelector('#btn');
                btn.addEventListener('click', event => {

                    const idFranchisee = document.getElementsByName('franchisee');
                    for(let i = 0; i < idFranchisee.length; i++){
                        const productsSelect = idFranchisee[i].lastChild;
                        const choice = productsSelect.selectedIndex;
                    }
                });
            });
        </script>
    </main>
    <?php
    include('../extensions/footer.php');

} else {
    echo '<img src="https://http.cat/401" alt="not found">';
    header('Location: ../login/index.php');
    exit;
}
?>