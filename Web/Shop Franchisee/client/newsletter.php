<?php
session_start();
require_once "../bdd/connection.php";
$db = connectDB();
include('../extensions/lang.php');

    $reqClient = $db->query('SELECT * FROM CLIENT');
    while($clientData = $reqClient->fetch()) {
        $reqAdvantageVerify = $db->prepare('SELECT * FROM ADVANTAGE WHERE idClient = :idClient');
        $reqAdvantageVerify->execute(array(
            'idClient' => $clientData['id']
        ));

        if($reqAdvantageVerify->rowCount() > 0) {
            $header = "Mime-Version: 1.0\r\n";
            $header .= 'From:"Click\'N Cook"<support@clickncook.ovh>' . "\n";
            $header .= 'Content-Type:text/html; charset="utf-8"' . "\n";
            $header .= 'Content-Transfer-Encoding: 8bit';


            $objet = "Newsletter";
            $message = "
                <html>
                    <head>
                       <title>Your advantage on this month :</title>
                       <style type='text/css'>
                            table {
                                border-spacing: 0;
                                border-collapse: collapse;
                            }
                              .table-bordered th,
                            .table-bordered td {
                                border: 1px solid #ddd !important;
                            }
                            .table-striped > tbody > tr:nth-of-type(odd) {
                                background-color: #f9f9f9;
                            }
                        </style>
                    </head>
                    <body>";
                    $message .= "
                        <p style='text-indent: 15px; font-weight:bold;'>Hello " . $clientData['firstName'] . " " . $clientData['lastName'] . " !</p>
                        <p>You have won a 15% discount at Food Truck : </p><br>";

                        $reqAdvantage = $db->prepare('SELECT * FROM ADVANTAGE WHERE idClient = :idClient');
                        $reqAdvantage->execute(array(
                            'idClient' => $clientData['id']
                        ));
                        while($advantageData = $reqAdvantage->fetch()) {
                            $reqFranchisee = $db->prepare('SELECT * FROM FRANCHISEE WHERE id = :idFranchisee');
                            $reqFranchisee->execute(array(
                                'idFranchisee' => $advantageData['idFranchisee']
                            ));
                            $franchiseeData = $reqFranchisee->fetch();

                            $message .= "<p>- " . $franchiseeData['nameFranchise'] . " : </p>";
                            $message .= "<p style='text-indent: 15px;'> " . $franchiseeData['description'] . "</p><br>";

                        }

                        echo "<p><i>This is an automatic email, please do not answer it</i></p>
                    </body>
                </html>";

            mail($clientData['email'], $objet, $message, $header);
        }
    }
?>