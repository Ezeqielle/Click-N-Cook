<?php
require('../fpdf/fpdf.php');

$pdf = new FPDF( 'P', 'mm', 'A4' );

require_once "../bdd/connection.php";
$db = connectDB();


//$var_id_facture = $_GET['id_param'];

// on sup les 2 cm en bas
$pdf->SetAutoPagebreak(False);
$pdf->SetMargins(0,0,0);

// nb de page pour le multi-page : 18 lignes
$reqQuantityPurchase = $db->query('SELECT count(*) FROM PURCHASE');
$row_client = $reqQuantityPurchase->fetch();
//$row_client = mysqli_fetch_row($result);
$nb_page = $row_client[0];

$num_page = 1; $limit_inf = 0; $limit_sup = 18;
While ($num_page <= $nb_page)
{
    $pdf->AddPage();

    // logo : 80 de largeur et 55 de hauteur
    $pdf->Image('../assets/imgs/logo.png', 10, 10, 80, 55);

    // n° page en haute à droite
    $pdf->SetXY( 120, 5 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 160, 8, $num_page . '/' . $nb_page, 0, 0, 'C');

    // n° facture, date echeance et reglement et obs
    $reqPurchase = $db->query('SELECT * FROM PURCHASE');
    $purchaseData = $reqPurchase->fetch();

    //echo $purchaseData('price');

    $annee = date("d-m-Y");
    $num_fact = "FACTURE N° " . $annee;
    $pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(120, 15, 85, 8, "DF");
    $pdf->SetXY( 120, 15 ); $pdf->SetFont( "Arial", "B", 12 ); $pdf->Cell( 85, 8, $num_fact, 0, 0, 'C');

    // nom du fichier final
    $nom_file = "fact_" . $annee . ".pdf";

    // date facture
    $date_fact = date('d/m/Y');
    $pdf->SetFont('Arial','',11); $pdf->SetXY( 122, 30 );
    $pdf->Cell( 60, 8, "Le " . $date_fact, 0, 0, '');

    // si derniere page alors afficher total
    if ($num_page == $nb_page) {
        $price = 0;
        while($purchaseDatas = $reqPurchase->fetch()) {
            $price = $purchaseData['price'] + $price;
        }

        // les totaux, on n'affiche que le HT. le cadre après les lignes, demarre a 213
        $pdf->SetLineWidth(0.1); $pdf->SetFillColor(192); $pdf->Rect(5, 213, 90, 8, "DF");
        // HT, la TVA et TTC sont calculés après
        $nombre_format_francais = "Total HT : " . number_format($price, 2, ',', ' ') . iconv('UTF-8', 'ISO-8859-15',"€");
        $pdf->SetFont('Arial','',10); $pdf->SetXY( 95, 213 ); $pdf->Cell( 120, 8, $nombre_format_francais, 0, 0, 'C');

        // trait vertical cadre totaux, 8 de hauteur -> 213 + 8 = 221
        $pdf->Rect(5, 213, 200, 8, "D"); $pdf->Line(95, 213, 95, 221);
    }

    // observations
    $pdf->SetFont( "Arial", "BU", 10 ); $pdf->SetXY( 5, 75 ) ; $pdf->Cell($pdf->GetStringWidth($nb_page), 0, $purchaseData['price'], 0, "L");
    //$pdf->SetFont( "Arial", "", 10 ); $pdf->SetXY( 5, 78 ) ; $pdf->MultiCell(190, 4, $row[5], 0, "L");

    // adr fact du client
    /*$pdf->SetFont('Arial','B',11); $x = 110 ; $y = 50;
    $pdf->SetXY( $x, $y ); $pdf->Cell( 100, 8, $row_client[0], 0, 0, ''); $y += 4;
    if ($row_client[1]) { $pdf->SetXY( $x, $y ); $pdf->Cell( 100, 8, $row_client[1], 0, 0, ''); $y += 4;}
    if ($row_client[2]) { $pdf->SetXY( $x, $y ); $pdf->Cell( 100, 8, $row_client[2], 0, 0, ''); $y += 4;}
    if ($row_client[3]) { $pdf->SetXY( $x, $y ); $pdf->Cell( 100, 8, $row_client[3], 0, 0, ''); $y += 4;}
    if ($row_client[4] || $row_client[5]) { $pdf->SetXY( $x, $y ); $pdf->Cell( 100, 8, $row_client[4] . ' ' .$row_client[5] , 0, 0, ''); $y += 4;}
    if ($row_client[6]) { $pdf->SetXY( $x, $y ); $pdf->Cell( 100, 8, 'N° TVA Intra : ' . $row_client[6], 0, 0, '');}*/

    // ***********************
    // le cadre des articles
    // ***********************
    // cadre avec 18 lignes max ! et 118 de hauteur --> 95 + 118 = 213 pour les traits verticaux
    $pdf->SetLineWidth(0.1); $pdf->Rect(5, 95, 200, 118, "D");
    // cadre titre des colonnes
    $pdf->Line(5, 105, 205, 105);
    // les traits verticaux colonnes
    $pdf->Line(20, 95, 20, 213); $pdf->Line(145, 95, 145, 213); $pdf->Line(158, 95, 158, 213); $pdf->Line(176, 95, 176, 213); $pdf->Line(187, 95, 187, 213);
    // titre colonne
    $pdf->SetXY( 1, 96 ); $pdf->SetFont('Arial','B',8); $pdf->Cell( 22, 8, "Acheteur", 0, 0, 'C');
    $pdf->SetXY( 23, 96 ); $pdf->SetFont('Arial','B',8); $pdf->Cell( 120, 8, "Libellé", 0, 0, 'C');
    $pdf->SetXY( 145, 96 ); $pdf->SetFont('Arial','B',8); $pdf->Cell( 13, 8, "Qté", 0, 0, 'C');
    $pdf->SetXY( 156, 96 ); $pdf->SetFont('Arial','B',8); $pdf->Cell( 22, 8, "PU HT", 0, 0, 'C');
    $pdf->SetXY( 177, 96 ); $pdf->SetFont('Arial','B',8); $pdf->Cell( 10, 8, "TVA", 0, 0, 'C');
    $pdf->SetXY( 185, 96 ); $pdf->SetFont('Arial','B',8); $pdf->Cell( 22, 8, "TOTAL HT", 0, 0, 'C');

    // les articles
    $pdf->SetFont('Arial','',8);
    $y = 97;
    // 1ere page = LIMIT 0,18 ;  2eme page = LIMIT 18,36 etc...
    while($purchaseDatas = $reqPurchase->fetch()) {
        $reqNameFranchisee = $db->query('SELECT last name, first name FROM FRANCHISEE WHERE id = ' .$purchaseDatas["idFranchisee"]);
        $nameFranchisee = $reqNameFranchisee->fetch();

        $reqIdItem = $db->query('SELECT idItem FROM CONTAINSIN WHERE idPurchase = ' .$purchaseDatas["bill_number"]);
        while($idItem = $reqIdItem->fetch()) {
            $reqDataItem = $db->query('SELECT * FROM ITEM WHERE id = ' .$idItem["idItem"]);
            $dataItem = $reqDataItem->fetch();

            // name
            $pdf->SetXY( 7, $y+9 ); $pdf->Cell( 140, 5, $nameFranchisee['last name'] . ' ' . $nameFranchisee['first name'], 0, 0, 'L');
            // libelle
            $pdf->SetXY( 23, $y+9 ); $pdf->Cell( 140, 5, $dataItem['name'], 0, 0, 'L');
            // qte
            $pdf->SetXY( 145, $y+9 ); $pdf->Cell( 13, 5, strrev(wordwrap(strrev($idItem['quantity']), 3, ' ', true)), 0, 0, 'R');
            // PU
            $nombre_format_francais = number_format($dataItem['price'], 2, ',', ' ');
            $pdf->SetXY( 158, $y+9 ); $pdf->Cell( 18, 5, $nombre_format_francais, 0, 0, 'R');
            // total
            $nombre_format_francais = number_format($dataItem['price'] * $idItem['quantity'], 2, ',', ' ');
            $pdf->SetXY( 187, $y+9 ); $pdf->Cell( 18, 5, $nombre_format_francais, 0, 0, 'R');

            $pdf->Line(5, $y+14, 205, $y+14);

            $y += 6;
        }

    }

    // si derniere page alors afficher cadre des TVA
    if ($num_page == $nb_page)
    {
        $price = 0;
        while($purchaseDatas = $reqPurchase->fetch()) {
            $price = $purchaseData['price'] + $price;
        }

        $nombre_format_francais = "Net à payer TTC : " . number_format($price, 2, ',', ' ') . " €";
        $pdf->SetFont('Arial','B',12); $pdf->SetXY( 5, 213 ); $pdf->Cell( 90, 8, $nombre_format_francais, 0, 0, 'C');
    }

    // **************************
    // pied de page
    // **************************
    $pdf->SetLineWidth(0.1); $pdf->Rect(5, 260, 200, 6, "D");
    $pdf->SetXY( 1, 260 ); $pdf->SetFont('Arial','',7);
    $pdf->Cell( $pdf->GetPageWidth(), 7, "Clause de réserve de propriété (loi 80.335 du 12 mai 1980) : Les marchandises vendues demeurent notre propriété jusqu'au paiement intégral de celles-ci.", 0, 0, 'C');

    $y1 = 270;
    //Positionnement en bas et tout centrer
    $pdf->SetXY( 1, $y1 ); $pdf->SetFont('Arial','B',10);
    $pdf->Cell( $pdf->GetPageWidth(), 5, "REF BANCAIRE : FR76 xxx - BIC : xxxx", 0, 0, 'C');

    $pdf->SetFont('Arial','',10);

    $pdf->SetXY( 1, $y1 + 4 );
    $pdf->Cell( $pdf->GetPageWidth(), 5, "NOM SOCIETE", 0, 0, 'C');

    $pdf->SetXY( 1, $y1 + 8 );
    $pdf->Cell( $pdf->GetPageWidth(), 5, "ADRESSE 1 + CP + VILLE", 0, 0, 'C');

    $pdf->SetXY( 1, $y1 + 12 );
    $pdf->Cell( $pdf->GetPageWidth(), 5, "Tel + Mail + SIRET", 0, 0, 'C');

    $pdf->SetXY( 1, $y1 + 16 );
    $pdf->Cell( $pdf->GetPageWidth(), 5, "Adresse web", 0, 0, 'C');

    // par page de 18 lignes
    $num_page++; $limit_inf += 18; $limit_sup += 18;
}

$pdf->Output("I", $nom_file);
?>