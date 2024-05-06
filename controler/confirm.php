<?php $titre = "Bois du Roy";
require("view/titre.php"); ?>

<h3 class='sous-titre'>Veuillez changer votre mot de passe :</h3>

<form action='main.php?page=home&complement=noteFrais&firstConnect=true' method='post' class='grid1 form'>
  <div class='mat grid2'>
    <label for='pwd' class='mdp'>Nouveau mot de passe : </label>
    <input type='password' name='pwd' id='mat' required /><br />
  </div>
  <div class='pwd grid2'>
    <label for='confirmPwd' class='pwd'>Confirmer mot de passe : </label>
    <input type='password' name='confirmPwd' id='pwd' required /><br />
  </div>
  <div>
      <input type='submit' class='bouton connexion' value='Confirmer'/><button class='bouton' onClick="location.replace('main.php')">Annuler</button>
  </div>
</form>