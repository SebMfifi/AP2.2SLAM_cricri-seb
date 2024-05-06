<?php
$servername = "192.168.10.16";
$dbname = "metier_christophe_SLAM_DUO";
$username = "seb_ap";
$password = " ";

// on établit la connexion avec la BDD
$conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
