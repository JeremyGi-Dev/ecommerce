<?php
// Initialize the session
session_start();

// Include config file
require("_config/config.php");
 
// Check if the user is already logged in, if yes then redirect him to welcome page
if(isset($_SESSION["loggedIn"]) && $_SESSION["loggedIn"] === true){
    header("location: accueil_client.php");
    exit;
}

// Define variables and initialize with empty values
$email = "";
$password = "";
$t_erreur = array();
 
// Processing form data when form is submitted
if(isset($_POST['authentification']) AND $_POST['authentification'] == 1){

    $email = clean_field($_POST['email']);
    $password = clean_field($_POST['password']);
 
    if(empty($email) OR empty($password))
        $t_erreur = 'identifiant';
    
    // Validate credentials
    if(empty($t_erreur)){
        // Prepare a select statement
        $sql = "SELECT * FROM `clients` WHERE `client_email` = :email";
        
        if($request = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $request->bindParam(":email", $email, PDO::PARAM_STR);
            
            // Attempt to execute the prepared statement
            if($request->execute()){
                // Check if email exists, if yes then verify password
                if($request->rowCount() == 1){
                    if($result = $request->fetch()){

                        $id_client = $result['client_id'];
                        $email = $result['client_email'];
                        $hashed_password = $result['client_password'];
                        $prenom = $result['client_prenom'];
                        $nom = $result['client_nom'];

                        if($hashed_password === pwd_crypt($password)){

                            // Store data in session variables
                            $_SESSION['loggedIn'] = true;
                            $_SESSION['client_id'] = $id_client;
                            $_SESSION['client_email'] = $email;
                            $_SESSION['client_prenom'] = $prenom;
                            $_SESSION['client_nom'] = $nom;
                            
                            // Redirect user to welcome page
                            header("location: accueil_client.php");
                        } else{
                            // Display an error message if password is not valid
                            echo '<div class="alert alert-danger" role="alert">Mot de passe Invalide.</div>';
                        }
                    }
                } else{
                    // Display an error message if username doesn't exist
                    echo '<div class="alert alert-danger" role="alert">Aucun compte trouvé avec votre email.</div>';
                }
            } else{
                echo '<div class="alert alert-danger" role="alert">Une erreur est survenue durant la procédure.</div>';
            }

            // Close statement
            unset($request);
        }
    } else {
        echo '<div class="alert alert-danger" role="alert">Veuillez rentrer votre email et mot de passe pour vous connectez.</div>';
    }
    
    // Close connection
    unset($pdo);
}

include_once('_includes/header.php');
?>
<h2>Connectez-vous</h2>
<form role="form" action="<?=htmlspecialchars($_SERVER["PHP_SELF"]);?>" method="post" enctype="multipart/form-data">
    <input type="hidden" name="authentification" value="1" />
    <div class="form-group <?= (!empty($t_erreur)) ? 'has-error' : ''; ?>">
        <label>E-mail</label>
        <input type="email" name="email" class="form-control" value="<?=$email;?>" required>
    </div>    
    <div class="form-group <?= (!empty($t_erreur)) ? 'has-error' : ''; ?>">
        <label>Mot de passe</label>
        <input type="password" name="password" class="form-control" required>
    </div>
    <div class="form-group">
        <input type="submit" class="btn btn-primary" value="Connecter">
    </div>
    <p>Vous n'avez pas de compte ? <a href="register.php">Enregistrez-vous !</a>.</p>
</form>
<?php include_once('_includes/footer.php'); ?>