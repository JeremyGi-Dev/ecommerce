<?php 
session_start();
require('_config/config.php');

// on récupére l'id du produit
if(isset($_GET['id_produit'])){
    unset($_SESSION['produit_id']);
    $produit_id = intval($_GET['id_produit']);
    $_SESSION['produit_id'] = intval($_GET['id_produit']);
} else {
    $produit_id = $_SESSION['produit_id'];
}

// tableau des erreurs
$t_erreur = array();

//récupére les infos du produit
$sql = "SELECT * FROM `produits` WHERE `produit_id` = :produit_id";
if($request = $pdo->prepare($sql)){
    // Bind variables to the prepared statement as parameters
    $request->bindParam(":produit_id", $produit_id, PDO::PARAM_STR);

    if($request->execute()){
        if($request->rowCount() == 1){
            if($result = $request->fetch()){
                $produit_id         = $result['produit_id'];
                $categorie_id       = $result['categorie_id'];
                $produit_titre      = utf8_encode($result['produit_titre']);
                $produit_img        = $result['produit_img1'];
                $produit_prix       = $result['produit_prix'];
                $produit_size       = $result['produit_size'];
                $produit_description= $result['produit_description'];
                $produit_nombre     = $result['produit_nombre'];
            }
        }
    }
    unset($request);
}
// fin de récupération produits

// ajout au panier
if(isset($_POST['ajout_panier']) AND $_POST['ajout_panier'] == 1)
    addToCart();

include_once('_includes/header.php');
?>
<!-- carte produit -->
<div class="card mt-4">
    <img class="card-img-top img-fluid" src="http://placehold.it/900x400" alt="">
    <div class="card-body">
        <h3 class="card-title"><?=$produit_titre?></h3>
        <h4><?=$produit_prix?> €</h4>
        <p class="card-text"><?=$produit_description?></p>
        <?php if(isset($_SESSION['loggedIn'])) : ?>
        <div class="form-group">
            <a href="#" class="add-to-panier btn btn-primary" data-toggle="modal" data-id="<?=$produit_id?>" data-val="<?=$produit_prix?>" data-target="#addToPanier">Ajouter au panier</a>
            <a href="magasin.php" class="btn btn-danger">Retour au magasin</a>
        </div>
        <?php endif; ?>
    </div>
</div>
<!-- fin carte produit -->

<!-- Review produit -->
<!--
<div class="card card-outline-secondary my-4">
    <div class="card-header">Product Reviews</div>
    <div class="card-body">
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis et enim aperiam inventore, similique necessitatibus neque non! Doloribus, modi sapiente laboriosam aperiam fugiat laborum. Sequi mollitia, necessitatibus quae sint natus.</p>
        <small class="text-muted">Posted by Anonymous on 3/1/17</small>
        <hr>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis et enim aperiam inventore, similique necessitatibus neque non! Doloribus, modi sapiente laboriosam aperiam fugiat laborum. Sequi mollitia, necessitatibus quae sint natus.</p>
        <small class="text-muted">Posted by Anonymous on 3/1/17</small>
        <hr>
        <p>Lorem ipsum dolor sit amet, consectetur adipisicing elit. Omnis et enim aperiam inventore, similique necessitatibus neque non! Doloribus, modi sapiente laboriosam aperiam fugiat laborum. Sequi mollitia, necessitatibus quae sint natus.</p>
        <small class="text-muted">Posted by Anonymous on 3/1/17</small>
        <hr>
        <a href="#" class="btn btn-success">Leave a Review</a>
    </div>
</div>
-->
<!-- Fin review produit -->

<?php include('modals/add_to_panier.php'); ?>
<?php include_once('_includes/footer.php'); ?>