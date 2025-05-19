<?php
/**
 * Created by PhpStorm.
 * User: LE BERANOL
 * Date: 20/03/2018
 * Time: 13:54
 */

namespace App;

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
    const APOSTOLATS = array(SELF::APOSTOLAT_ENFANTS, SELF::APOSTOLAT_JEUNES, 
            SELF::APOSTOLAT_MARIES, SELF::APOSTOLAT_SINGLES);

    //Actif-Inactif
    const ETAT_ACTIF = 1;
    const ETAT_INACTIF = 0;

    //ZONES
    const ZONE_DOUALA = 'DOUALA';
    const ZONE_YAOUNDE = 'YAOUNDE';
    const ZONE_BAFOUSSAM = 'BAFOUSSAM';
    const ZONE_BAMENDA = 'BAMENDA';
    const ZONE_RESPONSABLE_GENERAL = 'RESPONSABLE GENERAL';
   
    //Villes
    const VILLE_DOUALA = 'DOUALA';
    const VILLE_YAOUNDE = 'YAOUNDE';
    const VILLE_BAFOUSSAM = 'BAFOUSSAM';
    const VILLE_BAMENDA = 'BAMENDA';
    const VILLE_PARIS = 'PARIS';
    const VILLE_AUTRE = 'AUTRE';

    //Sous Zones
    const SOUS_ZONE_EUROPE_ASIE = 'EUROPE ASIE';
    const SOUS_ZONE_AMERIQUE = 'AMERIQUE';
    const SOUS_ZONE_BIYEM_ASSI = 'BIYEM ASSI';
    const SOUS_ZONE_OMNISPORT = 'OMNISPORT';
    const SOUS_ZONE_MVOLYE = 'MVOLYE';
    const SOUS_ZONE_OLEMBE = 'OLEMBE';
    const SOUS_ZONE_INSITA = 'INSITA';
    const SOUS_ZONE_OBALA = 'OBALA';
    const SOUS_ZONE_GRAND_NORD = 'GRAND NORD';
   
    //Continents
    const CONTINENT_AFRIQUE = 'AFRIQUE';
    const CONTINENT_AMERIQUE = 'AMERIQUE';
    const CONTINENT_EUROPE = 'EUROPE';
    const CONTINENT_ASIE = 'ASIE';
    const CONTINENT_OCEANIE = 'OCEANIE';

    const CONTINENTS               = array(
        SELF::CONTINENT_AFRIQUE,
        SELF::CONTINENT_AMERIQUE,
        SELF::CONTINENT_EUROPE,
        SELF::CONTINENT_ASIE,
        SELF::CONTINENT_OCEANIE
    );

    //PAYS
    const PAYS_CAMEROUN = 'CAMEROUN';
    const PAYS_FRANCE = 'FRANCE';
    const PAYS_ALLEMAGNE = 'ALLEMAGNE';
    const PAYS_BELGIQUE = 'BELGIQUE';
    const PAYS_ANGLETERRE = 'ANGLETERRE';
    const PAYS_CHINE = 'CHINE';
    const PAYS_USA = 'USA';
    const PAYS_CANADA = 'CANADA';

    //Periodes
    const PERIODE_JOURNALIERE = 'Journalière';
    const PERIODE_HEBDOMADAIRE = 'Hebdomadaire';
    const PERIODE_MENSUELLE = 'Mensuelle';
    const PERIODE_TRIMESTRIELLE = 'Trimestrielle';
    const PERIODE_ANNUELLE = 'Annuelle';

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
    const RESPONSABILITES = array('SUPERVISEUR', 'RESPONSABLE', '1er Adjoint au Responsable', '2e Adjoint au Responsable', '3e Adjoint au Responsable', '4e Adjoint au Responsable', 'Conseiller', 'EPAULEUR');
    
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

    const NIVEAUX_ENGAGEMENT = array(SELF::SIMPLE, SELF::REGULIER, SELF::ACTIF_1, SELF::ACTIF_1_ANCIEN, SELF::ACTIF_1_ANCIEN_ENCOURAGE,
        SELF::ACTIF_2, SELF::ACTIF_2_ANCIEN, SELF::ACTIF_2_ANCIEN_ENCOURAGE, SELF::ACTIF_3, SELF::ACTIF_3_ANCIEN,
        SELF::ACTIF_3_ANCIEN_ENCOURAGE, SELF::ACTIF_3_TERMINAL, SELF::CANDIDAT_MEMBRE_PLEIN, SELF::MEMBRE_PLEIN,
        SELF::ACCOMPAGNATEUR_EN_FORMATION, SELF::ACCOMPAGNATEUR, SELF::INCARNATEUR, SELF::RESPONSABLE_GENERAL);

    //Types d'activité
    const ACTIVITE_REGIONALE          = 'Régionale';
    const ACTIVITE_ZONALE             = 'Zonale';
    const ACTIVITE_SOUS_ZONALE        = 'Sous-zonale';
    const ACTIVITE_GROUPE             = 'Groupe';
    const TYPE_ACTIVITE               = array(SELF::ACTIVITE_REGIONALE, SELF::ACTIVITE_ZONALE, SELF::ACTIVITE_SOUS_ZONALE,
                                            SELF::ACTIVITE_GROUPE);

}
