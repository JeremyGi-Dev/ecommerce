<?php
// Include config file
require("_config/config.php");

// initialisation des variables
$t_erreur = array();
$prenom = "";
$nom = "";
$adresse = "";
$telephone = "";
$email = "";
$password = "";
$ville = "";
$cp = "";
 
// Processing form data when form is submitted
if(isset($_POST['register']) AND $_POST['register'] == 1){

    // retrieve post data
    $prenom = clean_field($_POST['prenom']);
    $nom = clean_field($_POST['nom']);
    $adresse = clean_field($_POST['adresse']);
    $cp = clean_field($_POST['cp']);
    $ville = clean_field($_POST['ville']);
    $telephone = clean_field($_POST['telephone']);
    $email = clean_field($_POST['email']);
    $password = clean_field($_POST['password']);

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

    if(empty($email))
        $t_erreur['email'];

    if(empty($password))
        $t_erreur['password'];

    if(!empty($t_erreur))
        echo '<div class="alert alert-danger" role="alert">Veuillez remplir tous les champs</div>';

    // check if client already exists
    $sql = "SELECT `client_id` FROM `clients` WHERE `client_email` = :email";
    if($request = $pdo->prepare($sql)){
        // Bind variables to the prepared statement as parameters
        $request->bindParam(":email", $email, PDO::PARAM_STR);
        // Attempt to execute the prepared statement
        if($request->execute()){
            if($request->rowCount() == 1)
                echo '<div class="alert alert-danger" role="alert">L\'adresse email est déjà utilisée</div>';
        } else
            echo '<div class="alert alert-danger" role="alert">Une erreur est survenue durant la procédure</div>';

        // Close statement
        unset($request);

        // insert in database
        $sql = "INSERT INTO `clients` (`client_prenom`, `client_nom`, `client_adresse`, `client_cp`, `client_ville`, `client_telephone`, `client_email`, `client_password`) 
                VALUES (:prenom, :nom, :adresse, :cp, :ville, :telephone, :email, :password)";

        if($request = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $request->bindParam(":prenom", $prenom, PDO::PARAM_STR);
            $request->bindParam(":nom", $nom, PDO::PARAM_STR);
            $request->bindParam(":adresse", $adresse, PDO::PARAM_STR);
            $request->bindParam(":cp", $cp, PDO::PARAM_STR);
            $request->bindParam(":ville", $ville, PDO::PARAM_STR);
            $request->bindParam(":telephone", $telephone, PDO::PARAM_STR);
            $request->bindParam(":email", $email, PDO::PARAM_STR);
            $request->bindParam(":password", $password, PDO::PARAM_STR);

            $password = pwd_crypt($password);
            
            // Attempt to execute the prepared statement
            if($request->execute()){
                // on loggue le client
                $_SESSION['loggedIn'] = true;
                $_SESSION['client_id'] = $id_client;
                $_SESSION['client_email'] = $email;
                $_SESSION['client_prenom'] = $prenom;
                $_SESSION['client_nom'] = $nom;
                
                header("location: login.php");
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
<h2 class="m-3">Inscrivez-vous</h2>
<form role="form" action="<?=htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
    <input type="hidden" name="register" value="1" />
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
    <div class="form-group <?= (!empty($t_erreur['email'])) ? 'has-error' : ''; ?>">
        <label>E-mail</label>
        <input type="email" name="email" class="form-control" value="<?=$email;?>">
    </div>
    <div class="form-group <?= (!empty($t_erreur['password'])) ? 'has-error' : ''; ?>">
        <label>Mot de passe</label>
        <input type="password" name="password" class="form-control" value="<?=$password;?>">
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-primary" value="Valider">
        <input type="reset" class="btn btn-default" value="Annuler">
    </div>
    <p>Vous êtes déjà inscrit ? Vous identifiez <a href="login.php">ici</a>.</p>
</form>

<?php include_once('_includes/footer.php'); ?>