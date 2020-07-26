<?php
// Initialize the session
session_start();

require('_config/config.php');
 
check_client();

// initialisation
$client_id = $_SESSION['client_id'];
$facture = "";
$prix_total = "";
$date_commande = "";
$statut_commande = "";

//récupére les infos du panier
$sql = "SELECT *
            FROM `client_commandes`
        WHERE `client_id` = :client_id";
if($request = $pdo->prepare($sql)){
    // Bind variables to the prepared statement as parameters
    $request->bindParam(":client_id", $client_id, PDO::PARAM_INT);
    if($request->execute()){
        if($request->rowCount() > 0){
            if($result = $request->fetchAll()){
            }
        }
    }
}
unset($request);
unset($pdo);
include_once('_includes/header.php');
?>
<section class="text-center m-4">
    <div class="container">
        <h1>MES COMMANDES</h1>
     </div>
</section>
<div class="container mb-4">
    <div class="row">
        <?php if(!empty($result)) : ?>
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-striped">
                    <thead>
                        <tr>
                            <th scope="col">N° facture</th>
                            <th scope="col">Prix total</th>
                            <th scope="col">Date</th>
                            <th scope="col">Statut de la commande</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($result as $id => $commande) : 
                        $facture = $commande['facture'];
                        $prix_total = $commande['montant'];
                        $date_commande = $commande['date_commande'];
                        $statut_commande = $commande['statut_commande'];
                    ?>
                        <tr>
                            <td>N° <?=$facture?></td>
                            <td><?=$prix_total?> €</td>
                            <td><?=$date_commande?></td>
                            <td><?=$statut_commande?></td>
                            <td class="text-right">
                                <a href="get_bon_commande.php?facture=<?=$facture?>" class="btn btn-sm btn-success">
                                    <i class="fa fa-download"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php else : ?>
            <div class="alert alert-danger col-sm-12" role="alert">Vous n'avez pas de commande. <a href="magasin.php">aller au magasin</a></div>
        <?php endif; ?>
    </div>
</div>
<?php include_once('_includes/footer.php'); ?>