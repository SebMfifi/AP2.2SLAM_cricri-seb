<?php
// V�rification des donn�es de la note de frais cr��e
if (isset($_GET['ajoutNote']))
{
    if (isset($_POST['typeFrais']) && isset($_POST['qteFrais']) && isset($_POST['dateFrais']))
    {
        if ($_POST['typeFrais'] != 0 && $_POST['qteFrais'] > 0)
        {
            $typeFrais = htmlspecialchars($_POST['typeFrais']);
            $qteFrais = htmlspecialchars($_POST['qteFrais']);
            $dateFrais = htmlspecialchars($_POST['dateFrais']);

            $requetePrix = $conn->prepare("SELECT CODEFRAIS, PRIXFRAIS FROM FRAIS WHERE CODEFRAIS = :codeFrais;");
            $requetePrix->bindValue(':codeFrais', $typeFrais , PDO::PARAM_STR);
            $requetePrix->execute();

            $dataPrix = $requetePrix->fetchALL(PDO::FETCH_ASSOC);
            foreach ($dataPrix as $row) { $prixFrais = $row['PRIXFRAIS'];}

            try 
            {
                $requeteAjoutFrais = $conn->prepare("INSERT INTO AJOUTER (IDNOTEFRAIS, CODEFRAIS, QUANTITE, PRIXHISTORIQUE, DATEFRAIS) VALUES (".$_SESSION['idNouvelleNote'].", :codeFrais, :quantite, :prix, :dateFrais);");
                $requeteAjoutFrais->bindValue(':codeFrais', $typeFrais , PDO::PARAM_STR);
                $requeteAjoutFrais->bindValue(':quantite', $qteFrais , PDO::PARAM_STR);
                $requeteAjoutFrais->bindValue(':dateFrais', $dateFrais , PDO::PARAM_STR);
                $requeteAjoutFrais->bindValue(':prix', ($prixFrais*$qteFrais) , PDO::PARAM_STR);
                $requeteAjoutFrais->execute();
            } catch(PDOException $err) {
                    echo "";
            }

        } else {
            header("Location: main.php?page=home&complement=ajouterNote&errors=donnee");
        }
    } else {
        header("Location: main.php?page=home&complement=ajouterNote&errors=donnee");
    }
}
?>

<form action='main.php?page=home&complement=ajouterNote&ajoutNote=true' method='post'>
    <select class="form-control" name="typeFrais">
        <option value="0" selected hidden><i>choisir un frais...</i></option>
        <option value="1">Kilom�trique</option>
        <option value="2">Repas midi</option>
        <option value="3">Repas soir</option>
        <option value="4">Nuit�e hors Paris</option>
        <option value="5">Nuit�e Paris</option>
    </select>
    <input placeholder="quantiteFrais" class="form-control" value=0 name="qteFrais">
    <input type="date" name="dateFrais" value="1900-01-01" min="1900-01-01"/>
    <input type='submit' class='bouton connexion' value='Ajouter frais'/> 
</form>

<?php // affichage des frais de la note
if (isset($dataAjoutFrais))
{
    $requeteAffiFrais = $conn->prepare("SELECT IDNOTEFRAIS, TYPEFRAIS, QUANTITE, PRIXHISTORIQUE, DATEFRAIS FROM AJOUTER a INNER JOIN FRAIS f ON a.CODEFRAIS = f.CODEFRAIS WHERE IDNOTEFRAIS = :idNoteFrais;");
    $requeteAffiFrais->bindValue(":idNoteFrais", $_SESSION['idNouvelleNote'], PDO::PARAM_STR);
    $requeteAffiFrais->execute();

    $dataAffiFrais = $requeteAffiFrais->fetchALL(PDO::FETCH_ASSOC);
    echo "<div class:'scrollable'>";
    foreach ($dataAffiFrais as $row)
    {// L'affichage ne se fait pas, va savoir pourquoi
        echo $row['TYPEFRAIS']." : ".$row['PRIXHISTORIQUE']."� - ".$row['DATEFRAIS'];
    }
}
?>