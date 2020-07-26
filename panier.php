<?php
// Initialize the session
session_start();

require('_config/config.php');
 
check_client();

// inititalisation
$total_panier = 0;
$ids_panier = array();
$client_id = $_SESSION['client_id'];
unset($_SESSION['ids_panier']);

//récupére les infos du panier
$sql = "SELECT `p`.*, `pr`.`produit_titre` 
        FROM `paniers` AS `p`
            RIGHT JOIN `produits` AS `pr` ON `p`.`produit_id` = `pr`.`produit_id`
        WHERE `client_id` = :client_id";
if($request = $pdo->prepare($sql)){
    // Bind variables to the prepared statement as parameters
    $request->bindParam(":client_id", $client_id, PDO::PARAM_INT);
    // prepare le paramétre
    $client_id = $_SESSION['client_id'];
    if($request->execute()){
        if($request->rowCount() > 0){
            if($result = $request->fetchAll()){
            }
        }
    }
}

// recupére tous les ids du panier du client
if(!empty($result)){
    $ids_panier = getPanier($client_id);
    if(!empty($ids_panier))
        $_SESSION['ids_panier'] = $ids_panier;
}

// supprime un element du panier
if(isset($_POST['supprimer_panier']) AND $_POST['supprimer_panier'] == 1){
    $panier_id = intval($_POST['panier_id']);

    $sql="DELETE FROM `paniers` WHERE `panier_id` = :panier_id";
    if($request = $pdo->prepare($sql)){
        $request->bindParam(":panier_id", $panier_id, PDO::PARAM_INT);
        if($request->execute()){
            FlashMsg::add('ok', _('Cet article a été supprimé du panier.'));
            header('location: panier.php');
            exit;
        } else 
            echo '<div class="alert alert-danger" role="alert">Une erreur est survenue durant la procédure.</div>';
    } else 
        echo '<div class="alert alert-danger" role="alert">Une erreur est survenue durant la procédure.</div>';
}
unset($pdo);

include_once('_includes/header.php');
?>
<section class="text-center m-4">
    <div class="container">
        <h1>MON PANIER</h1>
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
                            <th scope="col"></th>
                            <th scope="col">Produit</th>
                            <th scope="col">Quantité</th>
                            <th scope="col">Prix</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach($result as $id => $panier) : 
                            $panier_id                  = $panier['panier_id'];
                            $panier_client_id           = $panier['client_id'];
                            $panier_produit_id          = $panier['produit_id'];
                            $panier_produit_quantite    = $panier['quantite'];
                            $panier_total_produit       = $panier['total_panier'];
                            $produit_titre              = $panier['produit_titre'];
                            $total_panier               += $panier['total_panier'];
                    ?>
                        <tr>
                            <td><img src="https://dummyimage.com/50x50/55595c/fff" /></td>
                            <td><?=utf8_encode($produit_titre)?></td>
                            <td ><?=$panier_produit_quantite?></td>
                            <td><?=$panier_total_produit?> €</td>
                            <td class="text-right">
                                <button class="btn btn-sm btn-danger delete-from-panier" data-toggle="modal" data-id="<?=$panier_id?>" data-target="#deleteFromPanier">
                                    <i class="fa fa-trash"></i>
                                </button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                        <tr>
                            <td></td>
                            <td></td>
                            <td></td>
                            <td><strong>Total</strong></td>
                            <td class="text-right"><strong><?=$total_panier?> €</strong></td>
                        </tr>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="col mb-2">
            <div class="row">
                <div class="col-sm-12  col-md-6">
                    <a href="magasin.php" class="btn btn-lg btn-block btn-light">Retour au magasin</a>
                </div>
                <div class="col-sm-12 col-md-6 text-right">
                    <a href="#" class="btn btn-lg btn-block btn-success text-uppercase genere-bon-commande">Valider ma commande</a>
                </div>
            </div>
        </div>
        <?php else : ?>
            <div class="alert alert-danger col-sm-12" role="alert">Votre panier est actuellement vide. <a href="magasin.php">aller au magasin</a></div>
        <?php endif; ?>
    </div>
</div>

<script type="text/javascript">
$('.genere-bon-commande').click(function(event){
    bootbox.dialog({
        title: 'Valider votre panier',
        message: 'Voulez-vous valider votre panier ?',
        buttons: {
            cancel: {
                label: 'Annuler / Fermer',
                className: 'btn-light',
                callback: function () {
                    bootbox.hideAll();
                }
            },
            confirm: {
                label: 'Valider',
                className: 'btn-success',
                callback: function () {
                    window.location.href = "bon_commande.php";
                }
            }
        }
    });
});
</script>

<?php include('modals/delete_produit.php'); ?>
<?php include_once('_includes/footer.php'); ?>