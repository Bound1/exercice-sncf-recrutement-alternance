<?php
$nom_serveur = "localhost";
$nom_utilisateur = "root";
$mot_de_passe = "";
$base_de_donnees="sncf_exercice";
$jours=["Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi","Dimanche"];
if(isset($_POST["mode"]) && $_POST["mode"]=="old"){
    if(isset($_POST["donnee_choix_train"])){    
        $donnee_train=$_POST["donnee_choix_train"];
    }
    if(isset($_POST["donnee_choix_jour_total"])){
        $donnee_train=$_POST["donnee_choix_jour_total"];
    }
    if(isset($_POST["donnee_choix_jour_liste_trains"])){
        $donnee_train=$_POST["donnee_choix_jour_liste_trains"];
    }
    $connexion= mysqli_connect($nom_serveur,$nom_utilisateur,$mot_de_passe,$base_de_donnees);
    $jours=["Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi","Dimanche"];
    $resultat_requete_liste_trains=mysqli_query($connexion,"SELECT DISTINCT Train FROM table_sncf ORDER BY Train");
    $trains=[];
    while ($row = mysqli_fetch_assoc($resultat_requete_liste_trains)) {
        array_push($trains,$row["Train"]);
    }
    $donnees_json=["liste_trains"=>$trains,"liste_jours"=>$jours];
    $circulation_trains=[];
    $circulation_jours=null;
    $liste_trains=[];
    $liste_trains_graphique=[];
    if (isset($donnee_train)){    
        $resultat_requete_circulation_trains=mysqli_query($connexion,"SELECT * FROM table_sncf WHERE Train='$donnee_train'");
        while ($row = mysqli_fetch_assoc($resultat_requete_circulation_trains)) {
            array_push($circulation_trains,$row);
        }
        $resultat_requete_circulation_jours=mysqli_query($connexion,"SELECT ".$donnee_train." FROM nombre_total_trains");
        while ($row = mysqli_fetch_assoc($resultat_requete_circulation_jours)) {
            $circulation_jours=$row;
        }
        if(isset($_POST["donnee_choix_jour_liste_trains"])){
            $resultat_requete_choix_jour_liste_trains=mysqli_query($connexion,"SELECT jour.Train,SUM(jour.$donnee_train) AS nombre_de_trains FROM (SELECT Train, $donnee_train FROM circulation_jour_de_la_semaine_pour_chaque_train WHERE $donnee_train!=0) AS jour GROUP BY jour.Train;");
            while($row = mysqli_fetch_assoc($resultat_requete_choix_jour_liste_trains)){
                array_push($liste_trains,$row);
            }
        }
        $donnees_json["circulation_trains"]=$circulation_trains;
        $donnees_json["circulation_jours"]=$circulation_jours;
        $donnees_json["liste_trains"]=$liste_trains;
        
    }
    $resultat_requete_graphique_trains=mysqli_query($connexion,"SELECT Lundi,Mardi,Mercredi,Jeudi,Vendredi,Samedi,Dimanche FROM nombre_total_trains");
    while($row = mysqli_fetch_assoc($resultat_requete_graphique_trains)){
        array_push($liste_trains_graphique,$row);
    }
    $donnees_json["liste_trains_graphique"]=$liste_trains_graphique;
    echo json_encode($donnees_json);
    mysqli_close($connexion);
}
if(isset($_POST["mode"]) && $_POST["mode"]=="ajout"){
    function transformer_tableau_en_requete(){
        $jours=["Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi","Dimanche"];
        $date_debut=$_POST["Date_deb"];
        $date_fin=$_POST["Date_fin"];
        $train=$_POST["train"];
        $requete_colonne="INSERT INTO table_sncf (Date_deb,Date_fin,Train,";   
        $requete_valeurs="'$date_debut','$date_fin','$train',";     
        foreach($jours as $indice=>$jour){ 
            if($indice!=sizeof($jours)-1){                
                $requete_colonne.="$jour,";
                $case_cochee=$_POST["tableau_jour_semaine"][$jour];
                $requete_valeurs.="'$case_cochee',";
            } 
            else{
                $requete_colonne.="$jour) \nVALUES (";
                $case_cochee=$_POST["tableau_jour_semaine"][$jour];
                $requete_valeurs.="'$case_cochee');";
            }          
        }
        $requete=$requete_colonne.$requete_valeurs;
        return $requete;
    }
    $tableau_jour_semaine=$_POST["tableau_jour_semaine"];    
    $connexion= mysqli_connect($nom_serveur,$nom_utilisateur,$mot_de_passe,$base_de_donnees);
    $resultat_requete_liste_trains=mysqli_query($connexion,transformer_tableau_en_requete());
    echo transformer_tableau_en_requete();
    mysqli_close($connexion);
}
if(isset($_POST["mode"]) && $_POST["mode"]=="modifier"){
    $donnees_recuperes=$_POST["donnees"];
    $Date_deb=$donnees_recuperes["Date_deb"];
    $Date_fin=$donnees_recuperes["Date_fin"];
    $Train=$donnees_recuperes["train"];
    $Lundi=$donnees_recuperes["Lundi"];
    $Mardi=$donnees_recuperes["Mardi"];
    $Mercredi=$donnees_recuperes["Mercredi"];
    $Jeudi=$donnees_recuperes["Jeudi"];
    $Vendredi=$donnees_recuperes["Vendredi"];
    $Samedi=$donnees_recuperes["Samedi"];
    $Dimanche=$donnees_recuperes["Dimanche"];
    $id=$_POST["id"];
    $requete="REPLACE INTO table_sncf VALUES ('$Date_deb','$Date_fin','$Train','$Lundi','$Mardi','$Mercredi','$Jeudi','$Vendredi','$Samedi','$Dimanche','$id')";
    $connexion= mysqli_connect($nom_serveur,$nom_utilisateur,$mot_de_passe,$base_de_donnees);
    $resultat_requete_liste_trains=mysqli_query($connexion,$requete);
}
if(isset($_POST["mode"]) && $_POST["mode"]=="supprimer"){
    $id=$_POST["id"];
    $requete="DELETE FROM table_sncf WHERE id='$id';";
    $connexion= mysqli_connect($nom_serveur,$nom_utilisateur,$mot_de_passe,$base_de_donnees);
    $resultat_requete_liste_trains=mysqli_query($connexion,$requete);
}
?>