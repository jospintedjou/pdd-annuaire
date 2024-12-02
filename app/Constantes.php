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
    //ROLE
    const ROLE_ADMIN = 'ADMIN';
    const ROLE_MEMBRE = 'MEMBRE';
    const ROLE_UTILISATEUR = 'UTILISATEUR';

    //Apostolat
    const APOSTOLAT_ENFANTS = "ENFANT";
    const APOSTOLAT_JEUNES = "JEUNE";
    const APOSTOLAT_MARIES = "MARIE(E)";
    const APOSTOLAT_SINGLES = "SINGLE";

    //Actif-Inactif
    const ETAT_ACTIF = 1;
    const ETAT_INACTIF = 0;

    //PAYS
    const PAYS_CAMEROUN = 'CAMEROUN';

    //Periodes
    const PERIODE_JOURNALIERE = 'Journalière';
    const PERIODE_HEBDOMADAIRE = 'Hebdomadaire';
    const PERIODE_MENSUELLE = 'Mensuelle';
    const PERIODE_TRIMESTRIELLE = 'Trimestrielle';
    const PERIODE_ANNUELLE = 'Annuelle';

    //VILLE
    const VILLE_YAOUNDE = 'YAOUNDE';

    //Categories Sociales
    const CATEGORIE_ADULTE_MARIE = 'ADULTE MARIE';
    const CATEGORIE_ADULTE_SINGLE = 'ADULTE SINGLE';
    const CATEGORIE_PRETRE = 'PRETRE';
    const CATEGORIE_JEUNE_MARIE = 'JEUNE MARIE';
    const CATEGORIE_JEUNE_TRAVAILLEUR_SENIOR = 'JEUNE TRAVAILLEUR SENIOR';
    const CATEGORIE_JEUNE_TRAVAILLEUR_MAJEUR = 'JEUNE TRAVAILLEUR MAJEUR';
    const CATEGORIE_JEUNE_TRAVAILLEUR = 'JEUNE TRAVAILLEUR';
    const CATEGORIE_UNIVERSITAIRE_MAJEUR = 'UNIVERSITAIRE MAJEUR';
    const CATEGORIE_UNIVERSITAIRE_DEBUTANT = 'UNIVERSITAIRE DEBUTANT';
    const CATEGORIE_SECONDAIRE_INTERMEDIAIRE = 'SECONDAIRE INTERMEDIAIRE';
    const CATEGORIE_SECONDAIRE_JUNIOR = 'SECONDAIRE JUNIOR';
    const CATEGORIE_GRAND_SEMINARISTE = 'Gd Seminariste';
    const CATEGORIE_RELIGIEUSE = 'RELIGIEUSE';
    const CATEGORIE_SOCIALES = array(SELF::CATEGORIE_ADULTE_MARIE, SELF::CATEGORIE_ADULTE_SINGLE, SELF::CATEGORIE_PRETRE,
            SELF::CATEGORIE_JEUNE_MARIE, SELF::CATEGORIE_JEUNE_TRAVAILLEUR_SENIOR,
            SELF::CATEGORIE_JEUNE_TRAVAILLEUR_MAJEUR, SELF::CATEGORIE_JEUNE_TRAVAILLEUR,
            SELF::CATEGORIE_UNIVERSITAIRE_MAJEUR, SELF::CATEGORIE_UNIVERSITAIRE_DEBUTANT,
            SELF::CATEGORIE_SECONDAIRE_INTERMEDIAIRE, SELF::CATEGORIE_SECONDAIRE_JUNIOR,
            SELF::CATEGORIE_GRAND_SEMINARISTE, SELF::CATEGORIE_RELIGIEUSE
        );

    //Responsable
    const SUPERVISEUR = 'SUPERVISEUR';
    const RESPONSABLE = 'RESPONSABALE';
    const VICE_RESPONSABLE = 'VICE RESPONSABALE';
    const PREMIER_ADJOINT_RESPONSABLE = '1er Adjoint au Responsable';
    const DEUXIEME_ADJOINT_RESPONSABLE = '2e Adjoint au Responsable';
    const TROISIEME_ADJOINT_RESPONSABLE = '3e Adjoint au Responsable';
    const QUATRIEME_ADJOINT_RESPONSABLE = '4e Adjoint au Responsable';
    const RESPONSABILITES_ZONE = array('SUPERVISEUR', 'RESPONSABLE', '1er Adjoint au Responsable', '2e Adjoint au Responsable', '3e Adjoint au Responsable', '4e Adjoint au Responsable', 'EPAULEUR');
    const RESPONSABILITES_SOUS_ZONE = array('SUPERVISEUR', 'RESPONSABLE', '1er Adjoint au Responsable', '2e Adjoint au Responsable', '3e Adjoint au Responsable', '4e Adjoint au Responsable', 'EPAULEUR');
    const RESPONSABILITES_GROUPE = array('SUPERVISEUR', 'RESPONSABLE', '1er Adjoint au Responsable', '2e Adjoint au Responsable', '3e Adjoint au Responsable', '4e Adjoint au Responsable', 'EPAULEUR');

    //Niveau d'engagement
    const SIMPLE                      = 'SIMPLE';
    const REGULIER                    = 'REGULIER';
    const ACTIF_1                     = 'Actif 1 N';
    const ACTIF_1_ANCIEN              = 'Actif 1 A';
    const ACTIF_1_ANCIEN_ENCOURAGE    = 'Actif 1 AE';
    const ACTIF_2                     = 'Actif 2 N';
    const ACTIF_2_ANCIEN              = 'Actif 2 A';
    const ACTIF_2_ANCIEN_ENCOURAGE    = 'Actif 2 AE';
    const ACTIF_3                     = 'Actif 3 N';
    const ACTIF_3_ANCIEN              = 'Actif 3 A';
    const ACTIF_3_ANCIEN_ENCOURAGE    = 'Actif 3 AE';
    const ACTIF_3_TERMINAL            = 'Actif 3 T';
    const CANDIDAT_MEMBRE_PLEIN       = 'CMP';
    const MEMBRE_PLEIN                = 'MP';
    const ACCOMPAGNATEUR_EN_FORMATION = 'AEF';
    const ACCOMPAGNATEUR              = 'Accompagnateur';
    const INCARNATEUR                 = 'Incarnateur';
    const RESPONSABLE_GENERAL         = 'RESPONSABLE_GENERAL';

    //Types d'activité
    const ACTIVITE_REGIONALE          = 'Régionale';
    const ACTIVITE_ZONALE             = 'Zonale';
    const ACTIVITE_SOUS_ZONALE        = 'Sous-zonale';
    const ACTIVITE_GROUPE             = 'Groupe';
    const TYPE_ACTIVITE               = array(SELF::ACTIVITE_REGIONALE, SELF::ACTIVITE_ZONALE, SELF::ACTIVITE_SOUS_ZONALE,
                                            SELF::ACTIVITE_GROUPE);

}
