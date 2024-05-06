<?php // FINI TA GROSSE MERDE QUE TU FAIS LÀ (c'est quand tu modifie la note plutôt que de la créer)
echo "<br />Ajouter Note ";

// Sélection de la note de frais ciblée  dans la BDD
$requeteSelectionNoteFrais = $conn->prepare("SELECT IDNOTEFRAIS, DATE_FRAIS FROM NOTE_FRAIS WHERE IDNOTEFRAIS = /* l'id de la note de frais visée' */;");
$requeteSelectionNoteFrais->execute();

$dataIdNote = $requeteIdNote->fetchALL(PDO::FETCH_ASSOC);
$lastIdNote = count($dataIdNote)-1;
$_SESSION['idNoteModif'] = $dataIdNote[$lastIdNote]['IDNOTEFRAIS'];

// Vérification des données de la note de frais créée
if (isset($_GET['ajoutNote']))
{
    if (isset($_POST['typeFrais']) && isset($_POST['qteFrais']) && isset($_POST['dateFrais']))
    {
        if ($_POST['typeFrais'] != 0 && $_POST['qteFrais'] > 0)
        {
            $typeFrais = htmlspecialchars($_POST['typeFrais']);
            $qteFrais = htmlspecialchars($_POST['qteFrais']);
            $dateFrais = htmlspecialchars($_POST['dateFrais']);

            $requetePrix = $conn->prepare("SELECT CODEFRAIS, PRIXFRAIS FROM FRAIS;");
            $dataPrix = $requetePrix->fetchALL(PDO::FETCH_ASSOC);

            $requeteAjoutFrais = $conn->prepare("INSERT INTO AJOUTER (CODEFRAIS, QUANTITE, PRIXHISTORIQUE, DATEFRAIS) VALUES (:codeFrais, :quantite, :prix, :dateFrais);");
            $requeteAjoutFrais->bindValue(':codeFrais', $typeFrais , PDO::PARAM_STR);
            $requeteAjoutFrais->bindValue(':quantite', $qteFrais , PDO::PARAM_STR);
            $requeteAjoutFrais->bindValue(':dateFrais', $dateFrais , PDO::PARAM_STR);
            $requeteAjoutFrais->bindValue(':prix', ($dataPrix['PRIXFRAIS'][$typeFrais-1]*$qteFrais) , PDO::PARAM_STR);

            $requeteAjoutFrais->execute();
        } else {
            header("Location: main.php?page=home&complement=ajouterNote&errors=donnee");
        }
    }
}
?>

<form action='main.php?page=home&complement=ajouterNote&ajoutNote=true' method='post'>
    <select class="form-control" name="typeFrais">
        <option value="0" selected hidden><i>choisir un frais...</i></option>
        <option value="1">Kilométrique</option>
        <option value="2">Repas midi</option>
        <option value="3">Repas soir</option>
        <option value="4">Nuitée hors Paris</option>
        <option value="5">Nuitée Paris</option>
    </select>
    <input placeholder="quantiteFrais" class="form-control" value=0 name="qteFrais">
    <input type="date" name="dateFrais" value="1900-01-01" min="1900-01-01"/>
    <input type='submit' class='bouton connexion' value='Ajouter frais'/> 
</form>

<div class='wrapper'>
    <a class='bouton btn_ajout btn1' onClick='location.replace("main.php?page=home&complement=noteFrais&actionNote=cancel");'>Retour</a>
    <a class='bouton btn_ajout btn2' onClick='location.replace("main.php?page=home&complement=noteFrais&actionNote=draft");'>Enregistrer en brouillon</a>
    <a class='bouton btn_ajout btn3' onClick='location.replace("main.php?page=home&complement=noteFrais&actionNote=add");'>Valider</a>
</div>

<!-- À FAIRE : 
+ Les frais ajoutés doivent être affichés et mis dans la BDD 
+ On doit pouvoir annuler toute modification
+ on doit pouvoir valider une note de frais 

--> 