DROP DATABASE IF exists `sncf_exercice`;
create database IF not exists `sncf_exercice`;
USE `sncf_exercice`;
DROP TABLE IF exists `table_sncf`;
CREATE TABLE IF NOT exists `table_sncf`
(
	Date_deb DATE,
	Date_fin DATE,
	Train varchar(8),
	Lundi TINYINT,
	Mardi TINYINT,
	Mercredi TINYINT,
    Jeudi TINYINT,
	Vendredi TINYINT,
    Samedi TINYINT,
    Dimanche TINYINT
);
LOAD DATA INFILE 'C:\\Users\\Boubacar Pelage\\Documents\\test alternance sncf\\tableau MUST hiver 2022 V1.csv' INTO table `table_sncf`
FIELDS terminated by ';' LINES terminated by '\r\n' IGNORE 1 LINES;
-- Remplacer 'C:\\Users\\Boubacar Pelage\\Documents\\test alternance sncf\\tableau MUST hiver 2022 V1.csv'
-- par le  chemin d'accès de votre fichier CSV
create OR REPLACE VIEW circulation_jour_de_la_semaine_pour_chaque_train AS
SELECT Train,
	SUM(table_sncf.Lundi) AS Lundi,
	SUM(table_sncf.Mardi) AS Mardi,
    SUM(table_sncf.Mercredi) AS Mercredi,
    SUM(table_sncf.Jeudi) AS Jeudi,
    SUM(table_sncf.Vendredi) AS Vendredi,
    SUM(table_sncf.Samedi) AS Samedi,
    SUM(table_sncf.Dimanche) AS Dimanche
FROM table_sncf
GROUP BY Train;
create or replace view nombre_total_trains AS
SELECT
	SUM(table_sncf.Lundi) AS Lundi,
	SUM(table_sncf.Mardi) AS Mardi,
    SUM(table_sncf.Mercredi) AS Mercredi,
    SUM(table_sncf.Jeudi) AS Jeudi,
    SUM(table_sncf.Vendredi) AS Vendredi,
    SUM(table_sncf.Samedi) AS Samedi,
    SUM(table_sncf.Dimanche) AS Dimanche
FROM table_sncf;