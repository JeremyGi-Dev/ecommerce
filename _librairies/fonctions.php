<?php

//clean_field
// nettoyage des données post
/**
 * - enleve les espaces au début et à la fin
 * - enleve les tags html
 */
function clean_field($value){
    $value = trim($value);
    $value = strip_tags($value);

    return $value;
}

/**
 * double sha1 avec le salt defini dans le config
 */
function pwd_crypt($pwd){
	$pwd = sha1(sha1($pwd._SHA1_SALT));
	return $pwd;
}

/**
 * Vérifie le type 'client' du visiteur et l'expulse le cas échéant
 */
function check_client(){
    if(!isset($_SESSION["loggedIn"]) || $_SESSION["loggedIn"] !== true){
        header("location: login.php");
        exit;
    }
}

/**
 * Ajout au panier du client
 */
function addToCart(){
    global $pdo;

    $quantite_produit = intval($_POST['produit_quantite']);
    $produit_prix = floatval($_POST['produit_prix']);
    $produit_id = intval($_POST['produit_id']);

    // gestion d'erreur
    if($quantite_produit == 0)
        $t_erreur = 1;
    
    if(empty($t_erreur)){

        $sql = "INSERT INTO `paniers` (`client_id`, `produit_id`, `quantite`, `total_panier`)
        VALUE (:client_id, :produit_id, :quantite, :total_panier)";

        if($request = $pdo->prepare($sql)){
            $request->bindParam(":client_id", $client_id, PDO::PARAM_STR);
            $request->bindParam(":produit_id", $produit_id, PDO::PARAM_STR);
            $request->bindParam(":quantite", $quantite_produit, PDO::PARAM_STR);
            $request->bindParam(":total_panier", $total_panier, PDO::PARAM_STR);

            $total_panier = $produit_prix*$quantite_produit;
            $client_id = $_SESSION['client_id'];

            // Attempt to execute the prepared statement
            if($request->execute()){
                FlashMsg::add('ok', _('Votre panier à bien été mis à jour.'));
                header('location: panier.php');
                exit;
            }
            else
                echo '<div class="alert alert-danger" role="alert">Une erreur est survenue durant la procédure</div>';
        }
        unset($request);
    } else {
        echo '<div class="alert alert-danger" role="alert">Vous devez choisir une quantité</div>';
    }
    unset($pdo);
}

/**
 * Récupére les ids des produits du panier du client
 */
function getPanier($id_client){
    global $pdo;

    // gestion d'erreur
    if($id_client == 0)
        return false;

    $sql = "SELECT `panier_id` FROM `paniers` WHERE `client_id` = :client_id";

    if($request = $pdo->prepare($sql)){
        $request->bindParam(":client_id", $id_client, PDO::PARAM_INT);

        if(isset($id_client))
            $id_client = intval($id_client);

        // Attempt to execute the prepared statement
        if($request->execute()){
            if($request->rowCount() > 0){
                if($result = $request->fetchAll()){
                   foreach($result as $id => $value){
                       $ids_client[$value['panier_id']] = $value['panier_id'];
                   }
                }
            }
        }
        else{
            echo '<div class="alert alert-danger" role="alert">Une erreur est survenue durant la procédure</div>';
        }
    }
    unset($request);

    return $ids_client;
}

/**
 * récupére les infos du client
 */
function getClient($id_client){
    global $pdo;

    // gestion d'erreur
    if($id_client == 0)
        return false;

    //récupére les infos du client
    $sql = "SELECT * FROM `clients` WHERE `client_id` = :client_id";
    if($request = $pdo->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $request->bindParam(":client_id", $id_client, PDO::PARAM_INT);

        if(isset($id_client))
            $id_client = intval($id_client);

        if($request->execute()){
            if($request->rowCount() == 1){
                if($result = $request->fetch()){
                    $client = $result;
                }
            }
        }
    } else {
        echo '<div class="alert alert-danger" role="alert">Une erreur est survenue durant la procédure</div>';
    }
    unset($request);

    return $client;
}