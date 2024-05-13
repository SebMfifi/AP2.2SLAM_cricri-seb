<?php
echo "<br />Ajouter Note ";

// Sélection de la note de frais ciblée  dans la BDD
if (!isset($_SESSION['idModifNote']))
{
    $requeteSelectionNoteFrais = $conn->prepare("SELECT IDNOTEFRAIS, DATE_FRAIS FROM NOTE_FRAIS WHERE IDNOTEFRAIS = /* l'id de la note de frais visée' */;");
    $requeteSelectionNoteFrais->execute();

    $dataIdNote = $requeteIdNote->fetchALL(PDO::FETCH_ASSOC);
    $lastIdNote = count($dataIdNote)-1;
    $_SESSION['idModifNote'] = $dataIdNote[$lastIdNote]['IDNOTEFRAIS'];
}

include ('ajouterFrais.php');
?>

<div class='wrapper'>
    <a class='bouton btn_ajout btn1' onClick='location.replace("main.php?page=home&complement=noteFrais&actionNote=delete");'>Retour</a>
    <a class='bouton btn_ajout btn2' onClick='location.replace("main.php?page=home&complement=noteFrais&actionNote=draft");'>Enregistrer en brouillon</a>
    <a class='bouton btn_ajout btn3' onClick='location.replace("main.php?page=home&complement=noteFrais&actionNote=add");'>Valider</a>
</div>

<!-- À FAIRE : 
+ Les frais ajoutés doivent être affichés

--> 