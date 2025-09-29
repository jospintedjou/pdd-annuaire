<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table) {
            // Add indexes for user filtering
            $table->index(['id', 'nom', 'prenom'], 'users_name_search_idx');
            $table->index('categorie_sociale', 'users_categorie_sociale_idx');
            $table->index('niveau_engagement_id', 'users_niveau_engagement_idx');
        });

        Schema::table('groupe_user', function (Blueprint $table) {
            // Composite indexes for user-group relationships
            $table->index(['user_id', 'groupe_id', 'actif'], 'groupe_user_user_groupe_actif_idx');
            $table->index(['groupe_id', 'actif'], 'groupe_user_groupe_actif_idx');
        });

        Schema::table('groupes', function (Blueprint $table) {
            // Indexes for group filtering
            $table->index('sous_zone_id', 'groupes_sous_zone_idx');
            $table->index('nom_groupe', 'groupes_nom_idx');
        });

        Schema::table('sous_zones', function (Blueprint $table) {
            // Index for zone filtering
            $table->index('zone_id', 'sous_zones_zone_idx');
        });

        Schema::table('participations', function (Blueprint $table) {
            // Composite index for participation lookups
            $table->index(['activite_id', 'user_id'], 'participations_activite_user_idx');
            $table->index('activite_id', 'participations_activite_idx');
        });

        Schema::table('activites', function (Blueprint $table) {
            // Indexes for activity filtering
            $table->index(['type_activite', 'groupe_id'], 'activites_type_groupe_idx');
            $table->index(['type_activite', 'sous_zone_id'], 'activites_type_sous_zone_idx');
            $table->index(['type_activite', 'zone_id'], 'activites_type_zone_idx');
            $table->index('categorie_activite_id', 'activites_categorie_idx');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropIndex('users_name_search_idx');
            $table->dropIndex('users_categorie_sociale_idx');
            $table->dropIndex('users_niveau_engagement_idx');
        });

        Schema::table('groupe_user', function (Blueprint $table) {
            $table->dropIndex('groupe_user_user_groupe_actif_idx');
            $table->dropIndex('groupe_user_groupe_actif_idx');
        });

        Schema::table('groupes', function (Blueprint $table) {
            $table->dropIndex('groupes_sous_zone_idx');
            $table->dropIndex('groupes_nom_idx');
        });

        Schema::table('sous_zones', function (Blueprint $table) {
            $table->dropIndex('sous_zones_zone_idx');
        });

        Schema::table('participations', function (Blueprint $table) {
            $table->dropIndex('participations_activite_user_idx');
            $table->dropIndex('participations_activite_idx');
        });

        Schema::table('activites', function (Blueprint $table) {
            $table->dropIndex('activites_type_groupe_idx');
            $table->dropIndex('activites_type_sous_zone_idx');
            $table->dropIndex('activites_type_zone_idx');
            $table->dropIndex('activites_categorie_idx');
        });
    }
};