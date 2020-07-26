<?php
// Initialize the session
session_start();

require('_config/config.php');
 
check_client();

include_once('_includes/header.php');
?>
<div class="text-center">
    <h1>Bonjour, <b><?= htmlspecialchars($_SESSION["client_prenom"]); ?></b>. Bienvenue dans votre espace client.</h1>
</div>
<?php include_once('_includes/footer.php'); ?>