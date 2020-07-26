<?php 
session_start();
require('_config/config.php');

//récupére les infos produits
$sql = "SELECT * FROM `produits`";
if($request = $pdo->prepare($sql))
    if($request->execute())
        if($request->rowCount() > 0)
            if($result = $request->fetchAll())
                $products = $result;

// ajout au panier
if(isset($_POST['ajout_panier']) AND $_POST['ajout_panier'] == 1)
    addToCart();

unset($pdo);
// fin de récupération produits
include_once('_includes/header.php'); 
?>
<div class="row">
    <?php if(!empty($products)) : ?>
        <?php foreach ($products as $id_produit => $product) :
            $produit_id             = $product['produit_id'];
            $produit_titre          = utf8_encode($product['produit_titre']);
            $produit_img            = $product['produit_img1'];
            $produit_prix           = $product['produit_prix'];
            $prix_size              = $product['produit_size'];
            $produit_description    = $product['produit_description'];
        ?>
        <div class="col-lg-4 col-md-6 mb-4">
            <div class="card h-100">
            <a href="produit.php?id_produit=<?=$produit_id?>"><img class="card-img-top" src="http://placehold.it/700x400" alt=""></a>
            <div class="card-body">
                <h4 class="card-title"><a href="produit.php?id_produit=<?=$produit_id?>"><?=$produit_titre?></a></h4>
                <h5><?=$produit_prix?> €</h5>
                <p class="card-text"><?=substr($produit_description, 0, 150)?></p>
                <?php if(isset($_SESSION['loggedIn'])) : ?>
                <a href="#" class="add-to-panier btn btn-primary" data-toggle="modal" data-id="<?=$produit_id?>" data-val="<?=$produit_prix?>" data-target="#addToPanier">Ajouter au panier</a>
                <?php endif; ?>
            </div>
            </div>
        </div>
        <?php endforeach; ?>
    <?php endif; ?>
</div>
<?php include('modals/add_to_panier.php'); ?>
<?php include_once('_includes/footer.php'); ?>