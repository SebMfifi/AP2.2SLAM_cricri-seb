<?php
echo "<br />Ajouter Note ";

// Sélection de la note de frais ciblée  dans la BDD
if (!isset($_SESSION['idModifNote']))
{
    $requeteCreationNoteFrais = $conn->prepare("INSERT INTO NOTE_FRAIS (DATE_FRAIS) VALUES (CURRENT_DATE);");
    $requeteCreationNoteFrais->execute();

    $requeteIdNote = $conn->prepare('SELECT IDNOTEFRAIS FROM NOTE_FRAIS;');
    $requeteIdNote->execute();

    $dataIdNote = $requeteIdNote->fetchALL(PDO::FETCH_ASSOC);
    $lastIdNote = count($dataIdNote)-1;
    $_SESSION['idNouvelleNote'] = $dataIdNote[$lastIdNote]['IDNOTEFRAIS'];
}

$requeteCopieFrais = $conn->prepare("SELECT IDNOTEFRAIS, CODEFRAIS, QUANTITE, PRIXHISTORIQUE, DATEFRAIS FROM AJOUTER WHERE IDNOTEFRAIS = :idNoteCopie;");
$requeteCopieFrais->bindValue(":idNoteCopie", $_GET['noteCopie'] , PDO::PARAM_STR);
$requeteCopieFrais->execute();

$dataCopieFrais = $requeteCopieFrais->fetchALL(PDO::FETCH_ASSOC);

foreach ($dataCopieFrais as $row)
{
    $requeteAjoutFrais = $conn->prepare("INSERT INTO AJOUTER (IDNOTEFRAIS, CODEFRAIS, QUANTITE, PRIXHISTORIQUE, DATEFRAIS) VALUES (".$_SESSION['idNouvelleNote'].", :codeFrais, :quantite, :prix, :dateFrais);");
    $requeteAjoutFrais->bindValue(':codeFrais', $row['CODEFRAIS'] , PDO::PARAM_STR);
    $requeteAjoutFrais->bindValue(':quantite', $row['QUANTITE'] , PDO::PARAM_STR);
    $requeteAjoutFrais->bindValue(':dateFrais', $row['DATEFRAIS'] , PDO::PARAM_STR);
    $requeteAjoutFrais->bindValue(':prix', $row['PRIXHISTORIQUE'] , PDO::PARAM_STR);
    $requeteAjoutFrais->execute();
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