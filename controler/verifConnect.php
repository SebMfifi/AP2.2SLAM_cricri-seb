<?php

// présence des données
if (isset($_POST['mat']) && isset($_POST['pwd']))
{ 
  $mat = htmlspecialchars($_POST['mat']);
  $mdp = htmlspecialchars($_POST['pwd']);
}

// Import de la BDD
require "models/connectToBDD.php";

$requeteVerif = $conn->prepare("SELECT MATRICULE, MDPSALARIE, firstConnexion FROM LOGINS WHERE MATRICULE=:mat AND MDPSALARIE=:mdp;");
$requeteVerif->bindValue(':mat', $mat , PDO::PARAM_STR);
$requeteVerif->bindValue(':mdp', $mdp , PDO::PARAM_STR);

$requeteVerif->execute();
$dataRequeteVerif = $requeteVerif->fetchALL(PDO::FETCH_ASSOC);

if (count($dataRequeteVerif)) {
  // vérification données et redirection
  foreach ($dataRequeteVerif as $row) 
  {
    // création d'une session
    session_start();
    $_SESSION['matricule'] = $row['MATRICULE'];
    $_SESSION['mdp'] = $row['MDPSALARIE'];

    // vérification première connexion
    if ($row['firstConnexion'] == 0) {
      header("Location: main.php?page=confirm"); // première connexion
    } else {
      header("Location: main.php?page=home&complement=noteFrais"); // pas première connexion
    }
  }
}
else
{
  header("Location: main.php?errors=saisie"); // matricule ou mot de passe incorrect
}