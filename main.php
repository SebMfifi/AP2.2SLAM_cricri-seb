<?php

include('view/header.php');

require 'models/connectToBDD.php';
 
// mise en place d'une liste blanche
$whitelist = array('verifConnect', 'home', 'confirm');
$complementaryWl = array('ajouterNote', 'noteFrais', 'lireNote');
$errorList = array('saisie', 'samePwd', 'donnee');

// initialisation des valeurs de la session
$_SESSION['complement'] = null;
$_SESSION['matricule'] = null;
$_SESSION['mdp'] = null;
$_SESSION['idNouvelleNote'] = null;

// fermeture d'une session déjà existante
if (isset($_GET['session']))
{
  session_destroy();
}

echo "<div class='grid_home'>"; // div pour mise en page

// vérification de la page selectionnée en GET et affichage
if (isset($_GET['page']) && in_array($_GET['page'], $whitelist)) 
{
    if ($_GET['page'] == "home")
    {
        include('controler/home.php');
    } else {
        include("controler/connect/".$_GET['page'].'.php');
    }
} else {
  include('controler/connect/connexion.php');
}

// vérification de la présence d'une page complémentaire et affichage SI dans la page home
if (isset($_GET['page']))
{
  if ($_GET['page'] == 'home')
  {
    if ((isset($_GET['complement']) && in_array($_GET['complement'], $complementaryWl)))
    {
      include("controler/note/".$_GET['complement'].'.php');
    } else {
      include('controler/note/noteFrais.php');
    }
  }
}
echo "</div>"; // fermeture div mise en page

// vérification d'erreurs de saisie
if (isset($_GET['errors']) && in_array($_GET['errors'], $errorList)) 
{
  echo "<div class='errorText'>";
  switch ($_GET['errors']) 
  {
    case "saisie":
      echo "Erreur de saisie !";
        break;
    case "samePwd":
      echo "Le nouveau mot de passe ne peut pas être le même que l'ancien !";
      break;
    case "donnee":
      echo "Une ou plusieurs donnée(s) est/sont erronée(s) !";
  }
  echo "</div>";
}

include('view/footer.php');
?>