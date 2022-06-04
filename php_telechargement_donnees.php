<?php
$nom_serveur = "localhost";
$nom_utilisateur = "root";
$mot_de_passe = "";
$base_de_donnees="sncf_exercice";
$connexion= mysqli_connect($nom_serveur,$nom_utilisateur,$mot_de_passe,$base_de_donnees);
$resultat_requete_liste_trains=mysqli_query($connexion,"SELECT Train FROM table_sncf ORDER BY Train");

?>