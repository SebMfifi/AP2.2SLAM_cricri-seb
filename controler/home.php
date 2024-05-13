<?php
// ouverture de la session
session_start();

$requeteAffi = $conn->prepare("SELECT NOMSALARIE, PRENOMSALARIE, MATRICULE, RESPONSABLE FROM SALARIE WHERE MATRICULE=:mat;");
$requeteAffi->bindValue(':mat', $_SESSION['matricule'] , PDO::PARAM_STR);

$requeteAffi->execute();
$dataAffi = $requeteAffi->fetchALL(PDO::FETCH_ASSOC);


$requeteCount = $conn->prepare("SELECT COUNT(*) AS TOTAL FROM REALISER WHERE IDSTATUTS = 2");

$requeteCount->execute();
$dataCount = $requeteCount->fetch();

foreach ($dataAffi as $row) 
{
    $salarie = $_SESSION['matricule']." ".$row['NOMSALARIE']." ".$row['PRENOMSALARIE'];
    if (isset($dataAffi['RESPONSABLE']))
    {
        $responsable = $dataAffi['RESPONSABLE'];
    }
}

// Actions de note de frais (suppression, brouillon, création)
$noteFraisActionList = array('delete', 'draft', 'add');
if (isset($_GET['actionNote']))
{
    if (in_array($_GET['actionNote'], $noteFraisActionList)) 
    {
        switch ($_GET['actionNote'])  // AJOUTER DANS CHAQUE CASE SI LA NOTE EST NOUVELLE OU MODIFIÉE
        {
            case "cancel":
                echo "annulation";
                break;
            case "delete":
                $requeteSupprNote = $conn->prepare("DELETE FROM NOTE_FRAIS WHERE IDNOTEFRAIS = :id_note;"); // la ligne est pas supprimée pour 0 raison envie de caner.
                $requeteSupprNote->bindValue(':id_note', $_SESSION['idNouvelleNote'] , PDO::PARAM_STR);
                $requeteSupprNote->execute();
                break;
            case "draft":
                try // vérifie si la note de frais n'existe pas déjà (empêche les erreurs via un rafraichissement de la page)
                {
                    $requeteBrouillonNote = $conn->prepare("INSERT INTO REALISER (MATRICULE, IDNOTEFRAIS, IDSTATUTS ,DATEEDITNOTE) VALUES (:mat, :id_note, 4, CURRENT_DATE);");
                    $requeteBrouillonNote->bindValue(':mat', $_SESSION['matricule'] , PDO::PARAM_STR);
                    $requeteBrouillonNote->bindValue(':id_note', $_SESSION['idNouvelleNote'] , PDO::PARAM_STR);
                    $requeteBrouillonNote->execute();
                } catch(PDOException $err) {
                    echo "";
                }
                break;
            case "add":
                try
                    {
                    $requeteAjoutNote = $conn->prepare("INSERT INTO REALISER (MATRICULE, IDNOTEFRAIS, IDSTATUTS ,DATEEDITNOTE) VALUES (:mat, :id_note, 2, CURRENT_DATE);");
                    $requeteAjoutNote->bindValue(':mat', $_SESSION['matricule'] , PDO::PARAM_STR);
                    $requeteAjoutNote->bindValue(':id_note', $_SESSION['idNouvelleNote'] , PDO::PARAM_STR);
                    $requeteAjoutNote->execute();
                } catch(PDOException $err) {
                    echo "";
                }
                break;
        }
    }
}

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
            $requeteFirstConn = $conn->prepare("UPDATE LOGINS SET firstConnexion=1 AND MDPSALARIE=:nouvMdp, MDPSALARIE=:mdp WHERE MATRICULE=:mat");
            $requeteFirstConn->bindValue(':mat', $_SESSION['matricule'] , PDO::PARAM_STR);
            $requeteFirstConn->bindValue(':mdp', $_SESSION['mdp'] , PDO::PARAM_STR);
            $requeteFirstConn->bindValue(':nouvMdp', $_POST['confirmPwd'] , PDO::PARAM_STR);
            $requeteFirstConn->execute();
        }
    }
}

$titre = 'Bienvenue '.$salarie;
require "view/titre.php";
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