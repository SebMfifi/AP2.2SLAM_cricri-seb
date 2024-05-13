<?php

$requeteNoteFrais = $conn->prepare("SELECT MATRICULE, IDNOTEFRAIS, sn.IDSTATUTS, LIBELLESTATUTS, DATEEDITNOTE FROM REALISER r 
INNER JOIN STATUS_NOTE sn ON r.IDSTATUTS = sn.IDSTATUTS WHERE MATRICULE=:mat;");
$requeteNoteFrais->bindValue(':mat', $_SESSION['matricule'], PDO::PARAM_STR);

$requeteNoteFrais->execute();
$dataNoteFrais = $requeteNoteFrais->fetchALL(PDO::FETCH_ASSOC);

/*  MATRICULE  IDNOTEFRAIS  IDSTATUTS  DATEEDITNOTE  */
echo "<div class='grid_note_frais'><ul style='list-style-type:none;'>";
foreach ($dataNoteFrais as $row)
{
    echo "<li class='note_frais'>Note de frais du ".$row['DATEEDITNOTE']." - État : ".$row['LIBELLESTATUTS']." </li>";
}
echo "</ul>";