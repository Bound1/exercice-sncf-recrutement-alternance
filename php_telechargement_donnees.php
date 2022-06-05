<?php
$nom_serveur = "localhost";
$nom_utilisateur = "root";
$mot_de_passe = "";
$base_de_donnees="sncf_exercice";
$jours=["Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi","Dimanche"];
if(isset($_POST["mode"]) && $_POST["mode"]=="ajout"){
    function transformer_tableau_en_requete(){
        $jours=["Lundi","Mardi","Mercredi","Jeudi","Vendredi","Samedi","Dimanche"];
        $date_debut=$_POST["date_debut"];
        $date_fin=$_POST["date_fin"];
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
    try{
        
    $resultat_requete_liste_trains=mysqli_query($connexion,transformer_tableau_en_requete());
    }
    catch(mysqli_sql_exception $e){
        echo $e."\n";
        die(transformer_tableau_en_requete());
    }
    mysqli_close($connexion);
}

?>