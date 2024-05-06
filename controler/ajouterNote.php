<?php
echo "<br />Ajouter Note ";

// Création d'une note de frais dans la BDD
$requeteCreationNoteFrais = $conn->prepare("INSERT INTO NOTE_FRAIS (DATE_FRAIS) VALUES (NOW());");
$requeteCreationNoteFrais->execute();

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

            $requeteAjoutFrais = $conn->prepare("INSERT INTO FRAIS (CODECATEGORIE, TYPEFRAIS, PRIXFRAIS) VALUES "); // fini la requête
            $requeteAjoutFrais->bindValue(':date', $_POST['dateFrais'] , PDO::PARAM_STR);

            $requeteAjoutFrais->execute();
            $dataRequeteAjoutFrais = $requeteAjoutFrais->fetchALL(PDO::FETCH_ASSOC);
            $_SESSION['idNouvelleNote'] = $dataRequeteAjoutFrais['IDNOTEFRAIS'];
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
    <a class='bouton btn_ajout btn1' onClick='location.replace("main.php?page=home&complement=noteFrais&actionNote=delete");'>Retour</a>
    <a class='bouton btn_ajout btn2' onClick='location.replace("main.php?page=home&complement=noteFrais&actionNote=draft");'>Enregistrer en brouillon</a>
    <a class='bouton btn_ajout btn3' onClick='location.replace("main.php?page=home&complement=noteFrais&actionNote=add");'>Valider</a>
</div>

<!-- À FAIRE :
+ Les frais ajoutés doivent être affichés et mis dans la BDD
+ On doit pouvoir mettre une note de frais en brouillon
+ On doit pouvoir annuler toute modification OU création d'une note de frais
+ on doit pouvoir valider une note de frais


-->