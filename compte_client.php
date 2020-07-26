<?php
// Initialize the session
session_start();

require('_config/config.php');

// check si logué
check_client();

// récupére les infos du client
$client_id = $_SESSION['client_id'];
$client = getClient($client_id);

$prenom = $client['client_prenom'];
$nom = $client['client_nom'];
$adresse = $client['client_adresse'];
$cp = $client['client_cp'];
$ville = $client['client_ville'];
$telephone = $client['client_telephone'];
$email = $client['client_email'];
$password = $client['client_password'];

// fin de récupération client

// update informations client
if(isset($_POST['account_update']) AND $_POST['account_update'] == 1){
    // retrieve post data
    $prenom = clean_field($_POST['prenom']);
    $nom = clean_field($_POST['nom']);
    $adresse = clean_field($_POST['adresse']);
    $cp = clean_field($_POST['cp']);
    $ville = clean_field($_POST['ville']);
    $telephone = clean_field($_POST['telephone']);

    // check if empty
    if(empty($prenom))
        $t_erreur['prenom'];

    if(empty($nom))
        $t_erreur['nom'];

    if(empty($adresse))
        $t_erreur['adresse'];

    if(empty($cp) OR empty($ville))
        $t_erreur['ville'];

    if(empty($telephone))
        $t_erreur['telephone'];

    if(!empty($t_erreur))
        echo '<div class="alert alert-danger" role="alert">Veuillez remplir tous les champs</div>';

    if(empty($t_erreur)){
        // insert in database
        $sql = "UPDATE `clients` SET 
                `client_prenom` = :prenom,
                `client_nom` = :nom,
                `client_adresse` = :adresse, 
                `client_cp` = :cp, 
                `client_ville` = :ville, 
                `client_telephone` = :telephone
                WHERE `client_id` = :client_id";

        if($request = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $request->bindParam(":prenom", $prenom, PDO::PARAM_STR);
            $request->bindParam(":nom", $nom, PDO::PARAM_STR);
            $request->bindParam(":adresse", $adresse, PDO::PARAM_STR);
            $request->bindParam(":cp", $cp, PDO::PARAM_STR);
            $request->bindParam(":ville", $ville, PDO::PARAM_STR);
            $request->bindParam(":telephone", $telephone, PDO::PARAM_STR);
            $request->bindParam(":client_id", $client_id, PDO::PARAM_STR);
            
            // on récupére l'id du client connecté
            $client_id = $_SESSION['client_id'];

            // Attempt to execute the prepared statement
            if($request->execute()){
                FlashMsg::add('ok', _('Vos informations personnelles sont à jour.'));
                header('location: compte_client.php');
                exit;
            }
            else
                echo '<div class="alert alert-danger" role="alert">Une erreur est survenue durant la procédure</div>';
            unset($request);
        }
        // Close connection
        unset($pdo);
    }
}
include_once('_includes/header.php');
?>
<h2 class="my-3">Vos informations personnelles</h2>
<form role="form" action="<?=htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
    <input type="hidden" name="account_update" value="1" />
    <div class="form-group <?= (!empty($t_erreur['prenom'])) ? 'has-error' : ''; ?>">
        <label>Prénom</label>
        <input type="text" name="prenom" class="form-control" value="<?=$prenom;?>">
    </div>
    <div class="form-group <?= (!empty($t_erreur['nom'])) ? 'has-error' : ''; ?>">
        <label>Nom</label>
        <input type="text" name="nom" class="form-control" value="<?=$nom;?>">
    </div>
    <div class="form-group <?= (!empty($t_erreur['adresse'])) ? 'has-error' : ''; ?>">
        <label>Adresse</label>
        <input type="text" name="adresse" class="form-control" value="<?=$adresse;?>">
    </div>
    <div class="form-group <?= (!empty($t_erreur['cp'])) ? 'has-error' : ''; ?>">
        <label>Code postal</label>
        <input type="text" name="cp" class="form-control" value="<?=$cp;?>">
    </div>
    <div class="form-group <?= (!empty($t_erreur['ville'])) ? 'has-error' : ''; ?>">
        <label>Ville</label>
        <input type="text" name="ville" class="form-control" value="<?=$ville;?>">
    </div>
    <div class="form-group <?= (!empty($t_erreur['telephone'])) ? 'has-error' : ''; ?>">
        <label>Téléphone ( Mobile ou Fixe )</label>
        <input type="text" name="telephone" class="form-control" value="<?=$telephone;?>">
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-primary" value="Valider">
        <input type="reset" class="btn btn-default" value="Annuler">
    </div>
</form>
<?php include_once('_includes/footer.php'); ?>