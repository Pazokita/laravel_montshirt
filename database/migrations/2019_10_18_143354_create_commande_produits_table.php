<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCommandeProduitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('commande_produits', function (Blueprint $table) {
            //on met exceptionnellement un id car on peut avoir une commande avec un produit de differents tailles
            $table->bigIncrements('id');
            $table->unsignedBigInteger('commande_id');
            $table->foreign('commande_id')
                ->references('id')
                ->on('commandes')
                //cascade car je ne peux pas avoir des produits sans commande et vis et versa
                ->onDelete('cascade');

            $table->unsignedBigInteger('produit_id')->nullable();
            $table->foreign('produit_id')
                ->references('id')
                ->on('produits')
                ->onDelete('set null');

            $table->unsignedBigInteger('taille_id')->nullable();
            $table->foreign('taille_id')
                ->references('id')
                ->on('tailles')
                ->onDelete('set null');

            $table->integer('qte');
            $table->float('prix_unitaire_ttc');
            $table->float('prix_unitaire_ht');
            $table->float('prix_total_ht');
            $table->float('prix_total_ttc');
            $table->timestamps();
            Schema::enableForeignKeyConstraints();

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('commande_produits');
    }
}
