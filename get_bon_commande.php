<?php
// Initialize the session
session_start();

require('_config/config.php');
 
check_client();

$filename = "";

if(isset($_GET['facture']))
    $filename = $_GET['facture'];

// Fetch the file info.
$filePath = $_SERVER['DOCUMENT_ROOT']."ecommerce/commandes/".$filename.'.pdf';

if(file_exists($filePath) && is_readable($filePath)) {
    header('Content-Type: application/pdf');
    header("Content-Disposition: attachment; filename=\"$filename.pdf\"");
    readfile($filePath);
} else {
    location('Location: commande.php');
}