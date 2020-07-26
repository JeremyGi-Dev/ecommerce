
  <!-- Navigation -->
  <nav class="navbar navbar-expand-lg fixed-top navbar-custom">
    <div class="container">
      <div class="hidden-xs col-sm-3"><img  src="_css/images/logo_gourmandises.png" height="80" width="200"/></div>
      <button class="navbar-toggler white" type="button" data-toggle="collapse" data-target="#navbarResponsive" aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      <div class="collapse navbar-collapse col-sm-9" id="navbarResponsive">
        <ul class="navbar-nav ml-auto">
          <?php if(isset($_SESSION["loggedIn"])) : ?>
            <li class="nav-item ml-4"><a href="accueil_client.php">Accueil</a></li>
            <li class="nav-item ml-4"><a href="compte_client.php">Mon compte</a></li>
            <li class="nav-item ml-4"><a href="panier.php">Mon panier</a></li>
            <li class="nav-item ml-4"><a href="commande.php">Mes commandes</a></li>
            <li class="nav-item ml-4"><a href="magasin.php">Tous les produits</a></li>
            <li class="nav-item ml-4"><a href="logout.php">Déconnexion</a></li>
          <?php else : ?>
            <li class="nav-item ml-4"><a href="index.php">Accueil</a></li>
            <li class="nav-item ml-4"><a href="register.php">Inscription</a></li>
            <li class="nav-item ml-4"><a href="magasin.php">Tous les produits</a></li>
            <li class="nav-item ml-4"><a href="login.php">Connexion</a></li>
          <?php endif; ?>
        </ul>
      </div>
    </div>
  </nav>

 
        
        