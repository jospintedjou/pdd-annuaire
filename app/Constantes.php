<?php
/**
 * Created by PhpStorm.
 * User: LE BERANOL
 * Date: 20/03/2018
 * Time: 13:54
 */

namespace App;


/**
 *vendor/doctrine/dbal/lib/Doctrine/DBAL/Driver/PDOStatement.php
 *
 * public function errorInfo() {
 * parent::errorInfo();
 * }
 *
 * public function closeCursor() {
 * parent::closeCursor();
 * }
 *
 * public function columnCount() {
 * parent::columnCount();
 * } */
class Constantes
{
    //SEXE
    const SEXE_MASCULIN = 'M';
    const SEXE_FEMININ = 'F';

    //Apostolat
    const APOSTOLAT_ENFANTS = "APOSTOLAT DES ENFANTS";
    const APOSTOLAT_JEUNES = "APOSTOLAT DES JEUNES";
    const APOSTOLAT_MARIES = "APOSTOLAT DES MARIES";

    //Actif-Inactif
    const ETAT_ACTIF = 1;
    const ETAT_INACTIF = 0;

    //PAYS
    const PAYS_CAMEROUN = 'CAMEROUN';

    //VILLE
    const VILLE_YAOUNDE = 'YAOUNDE';

    //Role
    const ROLE_ADMIN = 'ADMIN';
    const ROLE_UTILISATEUR = 'UTILISATEUR';

    //Categories Sociales
    const ELEVE = 'ELEVE';
    const ETUDIANT_MINEUR = 'ETUDIANT MINEUR';
    const ETUDIANT_MAJEUR = 'ETUDIANT MAJEUR';
    const JEUNE_TRAVAILLEUR = 'JEUNE TRAVAILLEUR';
    const CATEGORIE_SOCIALES = array('ELEVE', 'ETUDIANT MINEUR', 'ETUDIANT MAJEUR', 'JEUNE TRAVAILLEUR',
        'ETUDIANT MAJEUR','JEUNE MARIE','ADULTE MARIE');

    //Responsable
    const SUPERVISEUR = 'SUPERVISEUR';
    const RESPONSABLE = 'RESPONSABALE';
    const VICE_RESPONSABLE = 'VICE RESPONSABALE';
    const PREMIER_ADJOINT_RESPONSABLE = '1er Adjoint au Responsable';
    const DEUXIEME_ADJOINT_RESPONSABLE = '2e Adjoint au Responsable';
    const RESPONSABILITES_ZONE = array('SUPERVISEUR', 'RESPONSABLE', '1er Adjoint au Responsable', '2e Adjoint au Responsable', '3e Adjoint au Responsable');
    const RESPONSABILITES_SOUS_ZONE = array('SUPERVISEUR', 'RESPONSABLE', 'Vice-Responsable');
    const RESPONSABILITES_GROUPE = array('SUPERVISEUR', 'RESPONSABLE', '1er Adjoint au Responsable', '2e Adjoint au Responsable ', 'EPAULEUR');

    //Niveau d'engagement
    const SIMPLE                      = 'SIMPLE';
    const REGULIER                    = 'REGULIER';
    const ACTIF_1                     = 'ACTIF_1';
    const ACTIF_1_ANCIEN              = 'A1A';
    const ACTIF_1_ANCIEN_ENCOURAGE    = 'A1AE';
    const ACTIF_2                     = 'ACTIF_2';
    const ACTIF_2_ANCIEN              = 'A2A';
    const ACTIF_2_ANCIEN_ENCOURAGE    = 'A2AE';
    const ACTIF_3                     = 'ACTIF_3';
    const ACTIF_3_ANCIEN              = 'A3A';
    const ACTIF_3_ANCIEN_ENCOURAGE    = 'A3AE';
    const ACTIF_3_TERMINAL            = 'A3T';
    const CANDIDAT_MEMBRE_PLEIN       = 'CMP';
    const MEMBRE_PLEIN                = 'MP';
    const ACCOMPAGNATEUR_EN_FORMATION = 'AEF';
    const ACCOMPAGNATEUR              = 'ACCOMPAGNATEUR';
    const INCARNATEUR                 = 'INCARNATEUR';
    const RESPONSABLE_GENERAL         = 'RESPONSABLE_GENERAL';

}
