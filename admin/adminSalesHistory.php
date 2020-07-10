<?php
session_start();
if(isset($_SESSION['id']) AND !empty($_SESSION['id'])) {
    require_once "../bdd/connection.php";
    $db = connectDB();
    
    include('../lang/lang.php');

    if($_SESSION['lang'] == 'FR') {
        include('../lang/fr-lang.php');
    } else {
        include('../lang/en-lang.php');
    }

    require('../fpdf/fpdf.php');

    $pdf = new FPDF( 'P', 'mm', 'A4' );

    function conv($chains) {
        return iconv('UTF-8', 'windows-1252', $chains);
    }

    //$var_id_facture = $_GET['id_param'];

    // on sup les 2 cm en bas
    $pdf->SetAutoPagebreak(False);
    $pdf->SetMargins(0,0,0);

    // nb de page pour le multi-page : 18 lignes
    $reqQuantityPurchase = $db->query('SELECT count(*) FROM CONTAINSIN');
    $row_client = $reqQuantityPurchase->fetch();

    $nbPage = 1; $minLimit = 0; $maxLimit = 18;
    $nbPageMax = (int)($row_client[0] / $maxLimit) + 1;
    $priceTTC = 0;
    $priceHT = 0;

    While ($nbPage <= $nbPageMax) {
        $pdf->AddPage();

        // logo : 80 de largeur et 55 de hauteur
        $pdf->Image('../assets/imgs/logo.png', 10, 10, 80, 55);

        // n° page en haute à droite
        $pdf->SetXY( 120, 5 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 160, 8, $nbPage . '/' . $nbPageMax, 0, 0, 'C');

        $year = date("d-m-Y");
        $nbBill = conv("INVOICE N° " . $year);
        $pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(120, 15, 85, 8, "DF");
        $pdf->SetXY( 120, 15 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 85, 8, $nbBill, 0, 0, 'C');

        // nom du fichier final
        $file = conv("fact_" . $year . ".pdf");

        // date facture
        $date_fact = conv(date('d/m/Y'));
        $pdf->SetFont('Arial','',11); $pdf->SetXY( 122, 30 );
        $pdf->Cell( 60, 8, $date_fact, 0, 0, '');

        // ***********************
        // le cadre des articles
        // ***********************
        // cadre avec 18 lignes max ! et 118 de hauteur --> 95 + 118 = 213 pour les traits verticaux
        $pdf->SetLineWidth(0.1); $pdf->Rect(5, 95, 200, 118, "D");
        // cadre titre des colonnes
        $pdf->Line(5, 105, 205, 105);
        // les traits verticaux colonnes
        $pdf->Line(50, 95, 50, 213); $pdf->Line(100, 95, 100, 213); $pdf->Line(133, 95, 133, 213); $pdf->Line(143, 95, 143, 213); $pdf->Line(163, 95, 163, 213); $pdf->Line(185, 95, 185, 213);
        // titre colonne
        $pdf->SetXY( 1, 96 ); $pdf->SetFont('Arial','B',8); $pdf->Cell( 25, 8, conv(TXT_SALESHISTORY_PURCHASE), 0, 0, 'C');
        $pdf->SetXY( 50, 96 ); $pdf->SetFont('Arial','B',8); $pdf->Cell( 15, 8, conv(TXT_SALESHISTORY_LABEL), 0, 0, 'C');
        $pdf->SetXY( 100, 96 ); $pdf->SetFont('Arial','B',8); $pdf->Cell( 10, 8, conv("Date"), 0, 0, 'C');
        $pdf->SetXY( 133, 96 ); $pdf->SetFont('Arial','B',8); $pdf->Cell( 10, 8, conv(TXT_SALESHISTORY_QTY), 0, 0, 'C');
        $pdf->SetXY( 153, 94 ); $pdf->SetFont('Arial','B',8); $pdf->Cell( 1, 8, conv(TXT_SALESHISTORY_UP), 0, 0, 'C');
        $pdf->SetXY( 153, 98 ); $pdf->SetFont('Arial','B',8); $pdf->Cell( 1, 8, conv(TXT_SALESHISTORY_ET), 0, 0, 'C');
        $pdf->SetXY( 165, 96 ); $pdf->SetFont('Arial','B',8); $pdf->Cell( 18, 8, conv(TXT_SALESHISTORY_ST), 0, 0, 'C');
        $pdf->SetXY( 185, 94 ); $pdf->SetFont('Arial','B',8); $pdf->Cell( 22, 8, conv("TOTAL"), 0, 0, 'C');
        $pdf->SetXY( 185, 98 ); $pdf->SetFont('Arial','B',8); $pdf->Cell( 22, 8, conv(TXT_SALESHISTORY_ET), 0, 0, 'C');

        // les articles
        $pdf->SetFont('Arial','',8);
        $y = 97;
        // 1ere page = LIMIT 0,18 ;  2eme page = LIMIT 18,36 etc...
        $reqIdItem = $db->query('SELECT * FROM CONTAINSIN ORDER BY idPurchase LIMIT ' . $minLimit . ' , ' . $maxLimit);
        while($idItem = $reqIdItem->fetch()) {
            $reqPurchaseOrderr = $db->query('SELECT * FROM PURCHASE WHERE bill_number = ' . $idItem['idPurchase']);
            while($purchaseOrderrData = $reqPurchaseOrderr->fetch()) {
                $reqNameFranchisee = $db->query('SELECT * FROM FRANCHISEE WHERE id = ' .$purchaseOrderrData["idFranchisee"]);
                $nameFranchisee = $reqNameFranchisee->fetch();
                $reqDataItem = $db->query('SELECT * FROM ITEM WHERE id = ' .$idItem["iditem"]);
                $dataItem = $reqDataItem->fetch();

                // name
                $pdf->SetXY( 7, $y+9 ); $pdf->Cell( 74, 5, conv($nameFranchisee['lastName'] . ' ' . $nameFranchisee['firstName']), 0, 0, 'L');
                // libelle
                $pdf->SetXY( 51, $y+9 ); $pdf->Cell( 74, 5, conv($dataItem['name']), 0, 0, 'L');
                // date
                $pdf->SetXY( 107, $y+9 ); $pdf->Cell( 26, 5, strrev(wordwrap(strrev(conv($purchaseOrderrData['date'])), 3, ' ', true)), 0, 0, 'R');
                // qte
                $pdf->SetXY( 130, $y+9 ); $pdf->Cell( 13, 5, strrev(wordwrap(strrev($idItem['quantity']), 3, ' ', true)), 0, 0, 'R');
                // PU
                $nbFormat = number_format($dataItem['price'], 2, ',', ' ');
                $pdf->SetXY( 153, $y+9 ); $pdf->Cell( 10, 5, $nbFormat, 0, 0, 'R');
                // TVA
                $nbFormat = number_format($dataItem['price'] + (($dataItem['price'] * 10) / 100), 2, ',', ' ');
                $priceTTC += ($dataItem['price'] + (($dataItem['price'] * 10) / 100)) * $idItem['quantity'];
                $pdf->SetXY( 175, $y+9 ); $pdf->Cell( 10, 5, $nbFormat, 0, 0, 'R');
                // total
                $nbFormat = number_format(($dataItem['price'] + (($dataItem['price'] * 10) / 100)) * $idItem['quantity'], 2, ',', ' ');
                $priceHT += ($dataItem['price'] * $idItem['quantity']);
                $pdf->SetXY( 183, $y+9 ); $pdf->Cell( 22, 5, $nbFormat, 0, 0, 'R');

                $pdf->Line(5, $y+14, 205, $y+14);

                $y += 6;
            }

        }

        // si derniere page alors afficher cadre des TVA
        if ($nbPage == $nbPageMax) {
            // les totaux, on n'affiche que le HT. le cadre après les lignes, demarre a 213
            $pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(5, 213, 90, 8, "DF");
            // HT, la TVA et TTC sont calculés après
            $nbFormat = conv(TXT_SALESHISTORY_TET . number_format($priceHT, 2, ',', ' ') . TXT_CLIENT_MONNEY);
            $pdf->SetFont('Arial','',10); $pdf->SetXY( 95, 213 ); $pdf->Cell( 120, 8, $nbFormat, 0, 0, 'C');

            // trait vertical cadre totaux, 8 de hauteur -> 213 + 8 = 221
            $pdf->Rect(5, 213, 200, 8, "D"); $pdf->Line(95, 213, 95, 221);
            $nbFormat = conv(TXT_SALESHISTORY_NPIT . number_format($priceTTC, 2, ',', ' ') . TXT_CLIENT_MONNEY);
            $pdf->SetFont('Arial','B',12); $pdf->SetXY( 5, 213 ); $pdf->Cell( 90, 8, $nbFormat, 0, 0, 'C');
        }

        $y1 = 270;
        //Positionnement en bas et tout centrer

        $pdf->SetFont('Arial','',10);

        $pdf->SetXY( 1, $y1 + 4 );
        $pdf->Cell( $pdf->GetPageWidth(), 5, conv("Click'N Cook"), 0, 0, 'C');

        $pdf->SetXY( 1, $y1 + 8 );
        $pdf->Cell( $pdf->GetPageWidth(), 5, conv("242 rue du Faubourg Saint-Antoine Paris 75012"), 0, 0, 'C');

        $pdf->SetXY( 1, $y1 + 12 );
        $pdf->Cell( $pdf->GetPageWidth(), 5, conv("01 42 42 42 42 - ClickNCook@gmail.com"), 0, 0, 'C');

        $pdf->SetXY( 1, $y1 + 16 );
        $pdf->Cell( $pdf->GetPageWidth(), 5, conv("www.clickncook.ovh"), 0, 0, 'C');

        // par page de 18 lignes
        $nbPage++; $minLimit += 18; $maxLimit += 18;
    }

    $pdf->Output($file, 'download' ? 'D':'I');
} else {
    echo '<img src="https://http.cat/401" alt="not found">';
    header('Location: /login/index.php');
    exit;
}
?>