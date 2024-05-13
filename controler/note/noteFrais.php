<?php
// Actions de note de frais (suppression, brouillon, création)
$noteFraisActionList = array('delete', 'draft', 'add');
if (isset($_GET['actionNote']))
{
    if (in_array($_GET['actionNote'], $noteFraisActionList)) 
    {
        switch ($_GET['actionNote'])
        {
            case "cancel":
                echo "annulation"; // FAIRE L'ANNULATION (tout ce qui a été ajouté est enlevé et tout ce qui a été retiré est remis)
                $_SESSION['idNouvelleNote'] = null;
                $_SESSION['idModifNote'] = null;
                break;
            case "delete":
                $requeteSupprNote = $conn->prepare("DELETE FROM NOTE_FRAIS WHERE IDNOTEFRAIS = :id_note;"); // la ligne est pas supprimée pour 0 raison envie de caner.
                $requeteSupprNote->bindValue(':id_note', $_SESSION['idNouvelleNote'] , PDO::PARAM_STR);
                $requeteSupprNote->execute();
                $_SESSION['idNouvelleNote'] = null;
                $_SESSION['idModifNote'] = null;
                break;
            case "draft":
                try // vérifie si la note de frais n'existe pas déjà (empêche les doublons via un rafraichissement de la page)
                {
                    $requeteBrouillonNote = $conn->prepare("INSERT INTO REALISER (MATRICULE, IDNOTEFRAIS, IDSTATUTS ,DATEEDITNOTE) VALUES (:mat, :id_note, 4, CURRENT_DATE);");
                    $requeteBrouillonNote->bindValue(':mat', $_SESSION['matricule'] , PDO::PARAM_STR);
                    $requeteBrouillonNote->bindValue(':id_note', $_SESSION['idNouvelleNote'] , PDO::PARAM_STR);
                    $requeteBrouillonNote->execute();
                } catch(PDOException $err) {
                    echo "";
                }
                $_SESSION['idNouvelleNote'] = null;
                $_SESSION['idModifNote'] = null;
                break;
            case "add":
                try // vérifie si la note de frais n'existe pas déjà (empêche les doublons via un rafraichissement de la page)
                {
                    $requeteAjoutNote = $conn->prepare("INSERT INTO REALISER (MATRICULE, IDNOTEFRAIS, IDSTATUTS ,DATEEDITNOTE) VALUES (:mat, :id_note, 2, CURRENT_DATE);");
                    $requeteAjoutNote->bindValue(':mat', $_SESSION['matricule'] , PDO::PARAM_STR);
                    $requeteAjoutNote->bindValue(':id_note', $_SESSION['idNouvelleNote'] , PDO::PARAM_STR);
                    $requeteAjoutNote->execute();
                } catch(PDOException $err) {
                    echo "";
                }
                $_SESSION['idNouvelleNote'] = null;
                $_SESSION['idModifNote'] = null;
                break;
        }
    }
}

$listeEtat = array(
    4 => "<i style='color:gray;'>Brouillon</i>",
    3 => "<b style='color:red;'>Refusée</b>",
    2 => "<b style='color:yellow;'>En cours de validation</b>",
    1 => "<b style='color:green;'>Payée</b>"
);

echo "<div class='affi_notes_frais scrollable'><ul style='list-style-type:none;'>";
foreach ($dataNoteFrais as $row)
{
    $etat = $listeEtat[$row['NUMSTATUS']];
    echo "<li class='note_frais'>Note de frais du ".$row['DATEEDITNOTE']." - État : ".$etat." &emsp;<i style='color:RGB(33, 84, 154);'><a class='modif_note' onClick=\"location.replace('main.php?page=home&complement=copierNote&noteCopie=".$row['IDNOTEFRAIS']."');\">Copier</a> ";
    if ($row['NUMSTATUS'] == 4)
    {
        echo "&emsp;<a class='modif_note' onClick=\"location.replace('main.php?page=home&complement=modifierNote');\">Éditer</a>";
    }
    echo "</i></li>";
}
echo "</ul>";