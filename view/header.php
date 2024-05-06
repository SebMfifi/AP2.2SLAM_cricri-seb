<?php require "view/head.php"; ?>
<header>
    <img src="view/img/logo.jpg" class="logo">
    <div class="navBar">Bois du Roy</div>
<?php
    echo "<div class=\"bouton deconnexion\" onClick=\"location.replace('main.php?session=closed');\">Déconnexion</div>";
?>
</header>