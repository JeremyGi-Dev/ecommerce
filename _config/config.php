<?php
require("_librairies/define.php");

/* Attempt to connect to MySQL database */
try{
    $pdo = new PDO("mysql:host=" . DB_SERVER . ";dbname=" . DB_NAME, DB_USERNAME, DB_PASSWORD);
    // Set the PDO error mode to exception
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e){
    die("ERROR: Could not connect. " . $e->getMessage());
}

// Inclus automatiquement les fichiers de définition de classe lors de leur utilisation
spl_autoload_register(function ($classname){
    if (is_readable('_librairies/class_'.$classname.'.php'))
        include '_librairies/class_'.$classname.'.php';
});

// liste des requires
require("_librairies/fonctions.php");
require("_librairies/TCPDF/tcpdf.php");