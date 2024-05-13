<?php
echo "<br />Ajouter Note ";

// Création d'une note de frais dans la BDD
if (!isset($_SESSION['idNouvelleNote']))
{
$requeteCreationNoteFrais = $conn->prepare("INSERT INTO NOTE_FRAIS (DATE_FRAIS) VALUES (CURRENT_DATE);");
$requeteCreationNoteFrais->execute();

$requeteIdNote = $conn->prepare('SELECT IDNOTEFRAIS FROM NOTE_FRAIS;');
$requeteIdNote->execute();

$dataIdNote = $requeteIdNote->fetchALL(PDO::FETCH_ASSOC);
$lastIdNote = count($dataIdNote)-1;
$_SESSION['idNouvelleNote'] = $dataIdNote[$lastIdNote]['IDNOTEFRAIS'];
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