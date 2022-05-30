<?php
$nom_serveur = "localhost";
$nom_utilisateur = "root";
$mot_de_passe = "";
$base_de_donnees="sncf_exercice";
if(isset($_POST["donnee_choix_train"])){    
    $donnee_train=$_POST["donnee_choix_train"];
}
if(isset($_POST["donnee_choix_jour"])){
    $donnee_train=$_POST["donnee_choix_jour"];
}
$connexion= mysqli_connect($nom_serveur,$nom_utilisateur,$mot_de_passe,$base_de_donnees);
$jours=["Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi","Dimanche"];
$resultat_requete_liste_trains=mysqli_query($connexion,"SELECT Train FROM table_sncf ORDER BY Train");
$trains=[];
while ($row = mysqli_fetch_assoc($resultat_requete_liste_trains)) {
    array_push($trains,$row["Train"]);
}
$donnees_json=["liste_trains"=>$trains,"liste_jours"=>$jours];
$circulation_trains=null;
$circulation_jours=null;
if (isset($donnee_train)){    
    $resultat_requete_circulation_trains=mysqli_query($connexion,"SELECT Lundi,Mardi,Mercredi,Jeudi,Vendredi,Samedi,Dimanche FROM circulation_jour_de_la_semaine_pour_chaque_train WHERE Train='".$donnee_train."'");
    while ($row = mysqli_fetch_assoc($resultat_requete_circulation_trains)) {
        $circulation_trains=$row;
    }
    $resultat_requete_circulation_jours=mysqli_query($connexion,"SELECT ".$donnee_train." FROM nombre_total_trains");
    while ($row = mysqli_fetch_assoc($resultat_requete_circulation_jours)) {
        $circulation_jours=$row;
    }
    $donnees_json["circulation_trains"]=$circulation_trains;
    $donnees_json["circulation_jours"]=$circulation_jours;
}
echo json_encode($donnees_json);
mysqli_close($connexion);
?>