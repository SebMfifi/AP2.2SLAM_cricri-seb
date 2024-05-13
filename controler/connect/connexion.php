<?php $titre = "Bois du Roy";
require("view/titre.php"); ?>

<h3 class='sous-titre'>Connexion :</h3>

<form action='main.php?page=verifConnect' method='post' class='grid1 form'>
  <div class='mat grid2'>
    <label for='mat' class='mat'>Matricule : </label>
    <input type='text' name='mat' id='mat' required /><br /> 
  </div> 
  <div class='pwd grid2'> 
    <label for='pwd' class='pwd'>Mot de passe : </label>
    <input type='password' name='pwd' id='pwd' required /><br /> 
  </div> 
  <a href='./confirm.php'>
      <input type='submit' class='bouton connexion' value='Se connecter'/>
  </a>
</form>