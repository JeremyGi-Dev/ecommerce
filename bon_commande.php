<?php
session_start();

require('_config/config.php');

check_client();

// inititalisation
$total_panier = 0;
$client_id = $_SESSION['client_id'];

if(!empty($_SESSION['ids_panier'])){
	$ids_panier = $_SESSION['ids_panier'];
	$paniers = implode(",", $ids_panier);
}

//récupére les infos du panier grace au id
$sql = "SELECT `p`.*, `pr`.`produit_titre`,`pr`.`produit_prix`  
        FROM `paniers` AS `p`
            RIGHT JOIN `produits` AS `pr` ON `p`.`produit_id` = `pr`.`produit_id`
        WHERE `p`.`panier_id` IN(".$paniers.")";
if($request = $pdo->prepare($sql)){
	if($request->execute()){
        if($request->rowCount() > 0){
            if($all_paniers = $request->fetchAll()){
				foreach($all_paniers as $id => $value)
					$total_panier += $value['total_panier'];
            }
        }
    }
}
unset($request);

// récupére les infos du client
$client_id = $_SESSION['client_id'];
$client = getClient($client_id);

$prenom = $client['client_prenom'];
$nom = $client['client_nom'];
$adresse = $client['client_adresse'];
$cp = $client['client_cp'];
$ville = $client['client_ville'];
$telephone = $client['client_telephone'];

if(!empty($client) AND !empty($all_paniers) AND $total_panier > 0){

	// on insert dans le tableau des commandes
	$sql = "INSERT INTO `client_commandes` (`client_id`, `montant`, `facture`, `date_commande`, `statut_commande`)
			VALUES (:client_id, :montant, :facture, :date_commande, :statut_commande)";

	if($request = $pdo->prepare($sql)){
    	// Bind variables to the prepared statement as parameters
		$request->bindParam(":client_id", $client_id, PDO::PARAM_INT);
		$request->bindParam(":montant", $total_panier, PDO::PARAM_INT);
		$request->bindParam(":facture", $facture, PDO::PARAM_STR);
		$request->bindParam(":date_commande", $date_commande, PDO::PARAM_STR);
		$request->bindParam(":statut_commande", $statut_commande, PDO::PARAM_STR);

		$facture = $client_id.'_'.rand();
		$date_commande = date('d-m-Y');
		$statut_commande = 'En cours';

		if($request->execute()){

		} else {
			echo '<div class="alert alert-danger" role="alert">Une erreur est survenue durant la procédure.</div>';
		}
	}

	// génération du bon de commande
	class MYPDF extends TCPDF {
		public function Header() {
			$this->setJPEGQuality(90);
			$this->Image('_css/images/logo_gourmandises.png', 120, 10, 75, 0, 'PNG', '');
	
		}
		public function Footer() {
			$this->SetY(-15);
			$this->SetFont(PDF_FONT_NAME_MAIN, 'I', 8);
			$this->Cell(0, 10, 'Les Gourmandises de Nos artisans et Nos producteurs', 0, false, 'C');
		}
		public function CreateTextBox($textval, $x = 0, $y, $width = 0, $height = 10, $fontsize = 10, $fontstyle = '', $align = 'L') {
			$this->SetXY($x+20, $y); // 20 = margin left
			$this->SetFont(PDF_FONT_NAME_MAIN, $fontstyle, $fontsize);
			$this->Cell($width, $height, $textval, 0, false, $align);
		}
	}
	
	// create a PDF object
	$pdf = new MYPDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
	
	// set document (meta) information
	$pdf->SetCreator(PDF_CREATOR);
	$pdf->SetAuthor('Les gourmandises');
	$pdf->SetTitle('Bon de commande N°'.$facture);
	/*$pdf->SetSubject('TCPDF Tutorial');
	$pdf->SetKeywords('TCPDF, PDF, example, tutorial');*/
	
	// add a page
	$pdf->AddPage();
	
	// create address box
	$pdf->CreateTextBox($nom.' '.$prenom, 0, 60, 80, 10, 10);
	$pdf->CreateTextBox($adresse, 0, 65, 80, 10, 10);
	$pdf->CreateTextBox($cp.' '.$ville, 0, 70, 80, 10, 10);
	
	// invoice title / number
	$pdf->CreateTextBox('Facture N°'.$facture, 0, 90, 120, 20, 16);
	
	// date, order ref
	$pdf->CreateTextBox('Date: '.date('d-m-Y'), 0, 100, 0, 10, 10, '', 'R');
	
	// list headers
	$pdf->CreateTextBox('Quantité', 0, 120, 20, 10, 10, 'B', 'C');
	$pdf->CreateTextBox('Produit', 20, 120, 90, 10, 10, 'B');
	$pdf->CreateTextBox('Prix unité', 110, 120, 30, 10, 10, 'B', 'R');
	$pdf->CreateTextBox('Prix total', 140, 120, 30, 10, 10, 'B', 'R');
	
	$pdf->Line(20, 129, 195, 129);
	
	$currY = 128;
	foreach($all_paniers as $id => $value) {
		$pdf->CreateTextBox($value['quantite'], 0, $currY, 20, 10, 10, '', 'C');
		$pdf->CreateTextBox(utf8_encode($value['produit_titre']), 20, $currY, 90, 10, 10, '');
		$pdf->CreateTextBox($value['produit_prix'].' €', 110, $currY, 30, 10, 10, '', 'R');
		$pdf->CreateTextBox($value['total_panier'].' €', 140, $currY, 30, 10, 10, '', 'R');
		$currY = $currY+5;
	}
	$pdf->Line(20, $currY+4, 195, $currY+4);
	
	// output the total row
	$pdf->CreateTextBox('Total', 20, $currY+5, 135, 10, 10, 'B', 'R');
	$pdf->CreateTextBox($total_panier.' €', 140, $currY+5, 30, 10, 10, 'B', 'R');
	
	// some payment instructions or information
	/*
	$pdf->setXY(20, $currY+30);
	$pdf->SetFont(PDF_FONT_NAME_MAIN, '', 10);
	$pdf->MultiCell(175, 10, 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
	Vestibulum sagittis venenatis urna, in pellentesque ipsum pulvinar eu. In nec nulla libero, eu sagittis diam. Aenean egestas pharetra urna, et tristique metus egestas nec. Aliquam erat volutpat. Fusce pretium dapibus tellus.', 0, 'L', 0, 1, '', '', true, null, true);
	*/
	//Close and output PDF document
	$pdf->Output(__DIR__ .'/commandes/'.$facture.'.pdf', 'F');

	// fin de la génération du bon de commande

	// on supprime le panier lié au client après la génération du bon de commande et validation de la commande
	$sql = " DELETE FROM `paniers` WHERE `client_id` = :client_id";
	if($request = $pdo->prepare($sql)){
		$request->bindParam(":client_id", $client_id, PDO::PARAM_INT);
		if($request->execute()){
		} else {
			echo '<div class="alert alert-danger" role="alert">Une erreur est survenue durant la procédure.</div>';
		}
	}
	unset($request);
}
unset($pdo);

// si tout s'est bien passé, on redirige vers la page des commandes
FlashMsg::add('ok', _('Votre commande a été prise en compte.'));
header('location: commande.php');
exit;