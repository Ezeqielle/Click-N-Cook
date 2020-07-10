<?php
session_start();
require_once "/var/www/clickNCoock/bdd/connection.php";
$db = connectDB();
include('/var/www/clickNCoock/lang/lang.php');




    $reqClient = $db->query('SELECT * FROM CLIENT');
    while($clientData = $reqClient->fetch()) {


        if($clientData['lang'] == 0) {
            include('/var/www/clickNCoock/lang/fr-lang.php');
        } else {
            include('/var/www/clickNCoock/lang/en-lang.php');
        }

        $header = "Mime-Version: 1.0\r\n";
        $header .= 'From:"Click\'N Cook"<support@clickncook.ovh>' . "\n";
        $header .= 'Content-Type:text/html; charset="utf-8"' . "\n";
        $header .= 'Content-Transfer-Encoding: 8bit';


        $objet = TXT_MAIL_NEWS;
        $message = "
            <html>
                <head>
                   <title>" . TXT_MAIL_ADVANTAGE . "</title>
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
                <body>
                    <p style='text-indent: 15px; font-weight:bold;'>" . TXT_MAIL_HELLO . $clientData['firstName'] . " " . $clientData['lastName'] . " !</p>";

                    if($clientData['advantage'] == 0) {
                        $message .= "<p>" . TXT_MAIL_YHAVE . $clientData['advantage'] . TXT_MAIL_DISCOUNT . "<br>
                        " . TXT_MAIL_MORE . " </p><br><br>";
                    } else if($clientData['advantage'] < 15) {
                        $message .= "<p>" . TXT_MAIL_YHAVE . $clientData['advantage'] . TXT_MAIL_DISCOUNT . "<br>
                         " . TXT_MAIL_MORE . " </p><br><br>";
                    } else {
                        $message .= "<p>" . TXT_MAIL_YHAVE . $clientData['advantage'] . TXT_MAIL_DISCOUNT . "<br>
                         " . TXT_MAIL_MAX . "</p><br><br>";
                    }

                $message .= "
                    <p><i>" . TXT_MAIL_AUTO . "</i></p>
                </body>
            </html>";

        mail($clientData['email'], $objet, $message, $header);

    }
?>