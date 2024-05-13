<?php
// ouverture de la session
session_start();

$requeteAffi = $conn->prepare("SELECT NOMSALARIE, PRENOMSALARIE, MATRICULE, RESPONSABLE FROM SALARIE WHERE MATRICULE=:mat;");
$requeteAffi->bindValue(':mat', $_SESSION['matricule'] , PDO::PARAM_STR);

$requeteAffi->execute();
$dataAffi = $requeteAffi->fetchALL(PDO::FETCH_ASSOC);

foreach ($dataAffi as $row) 
{
    $salarie = $_SESSION['matricule']." ".$row['NOMSALARIE']." ".$row['PRENOMSALARIE'];
    if (isset($dataAffi['RESPONSABLE']))
    {
        $responsable = $dataAffi['RESPONSABLE'];
    }
}

$titre = 'Bienvenue '.$salarie;
require "view/titre.php";

// vérification de première connexion et changement de la valeur de connexion
if (isset($_GET['firstConnect']))
{
    if (isset($_POST['pwd']) && isset($_POST['confirmPwd'])) {
        $pwd = htmlspecialchars($_POST['pwd']);
        $confirmPwd = htmlspecialchars($_POST['confirmPwd']);
        if ($pwd != $confirmPwd)
        {
            header("Location: main.php?page=confirm&errors=saisie");
        }
        else if ($pwd == $_SESSION['mdp'])
        {
            header("Location: main.php?page=confirm&errors=samePwd");
        } else {
            $requeteFirstConn = $conn->prepare("UPDATE LOGINS SET firstConnexion=1 AND MDPSALARIE=:nouvMdp WHERE MATRICULE=:mat;");
            $requeteFirstConn->bindValue(':mat', $_SESSION['matricule'] , PDO::PARAM_STR);
            $requeteFirstConn->bindValue(':nouvMdp', $_POST['pwd'] , PDO::PARAM_STR);
            $requeteFirstConn->execute();
        }
    }
}

$requeteCount = $conn->prepare("SELECT COUNT(*) AS TOTAL FROM REALISER WHERE IDSTATUTS = 2");

$requeteCount->execute();
$dataCount = $requeteCount->fetch();

$requeteNoteFrais = $conn->prepare("SELECT MATRICULE, IDNOTEFRAIS, sn.IDSTATUTS NUMSTATUS, LIBELLESTATUTS, DATEEDITNOTE FROM REALISER r 
                                    INNER JOIN STATUS_NOTE sn ON r.IDSTATUTS = sn.IDSTATUTS WHERE MATRICULE=:mat;");
$requeteNoteFrais->bindValue(':mat', $_SESSION['matricule'], PDO::PARAM_STR);

$requeteNoteFrais->execute();
$dataNoteFrais = $requeteNoteFrais->fetchALL(PDO::FETCH_ASSOC);

if ($_GET['complement'] == 'noteFrais')
{
    echo '<a class="bouton btn_ajout" onClick="location.replace(\'main.php?page=home&complement=ajouterNote\');">Ajouter</a>'; // classe css avec un flex ou une grid pour bien mettre le bouton
    if (isset($responsable))
    {
        echo 'commercial tout court';
    } else {
        echo "Il y a ".$dataCount['TOTAL']." note de frais à traiter.";
    }
}