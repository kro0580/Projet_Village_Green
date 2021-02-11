<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201202100510 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client ADD role VARCHAR(255) NOT NULL');
        $this->addSql('ALTER TABLE passe RENAME INDEX passe_cli_id TO IDX_D317EE5F5A8FBB7A');
        $this->addSql('ALTER TABLE envoie DROP env_qte');
        $this->addSql('ALTER TABLE envoie RENAME INDEX env_pro_id TO IDX_4BC1C0028D32B924');
        $this->addSql('ALTER TABLE livraison CHANGE liv_cmd_id liv_cmd_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produit CHANGE pro_s_rub_id pro_s_rub_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE contient DROP contient_liv_qte');
        $this->addSql('ALTER TABLE contient RENAME INDEX contient_liv_id TO IDX_DC302E56C4B17933');
        $this->addSql('ALTER TABLE se_compose_de DROP se_compose_de_cmd_nb_produits, DROP se_compose_de_pro_prix_vente, DROP se_compose_de_cmd_prix_tot');
        $this->addSql('ALTER TABLE se_compose_de RENAME INDEX se_compose_de_cmd_id TO IDX_5DF0822D6369EF3');
        $this->addSql('ALTER TABLE sous_rubrique CHANGE s_rub_rub_id s_rub_rub_id INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE client DROP role');
        $this->addSql('ALTER TABLE contient ADD contient_liv_qte INT DEFAULT NULL');
        $this->addSql('ALTER TABLE contient RENAME INDEX idx_dc302e56c4b17933 TO contient_liv_id');
        $this->addSql('ALTER TABLE envoie ADD env_qte INT NOT NULL');
        $this->addSql('ALTER TABLE envoie RENAME INDEX idx_4bc1c0028d32b924 TO env_pro_id');
        $this->addSql('ALTER TABLE livraison CHANGE liv_cmd_id liv_cmd_id INT NOT NULL');
        $this->addSql('ALTER TABLE passe RENAME INDEX idx_d317ee5f5a8fbb7a TO passe_cli_id');
        $this->addSql('ALTER TABLE produit CHANGE pro_s_rub_id pro_s_rub_id INT NOT NULL');
        $this->addSql('ALTER TABLE se_compose_de ADD se_compose_de_cmd_nb_produits INT DEFAULT NULL, ADD se_compose_de_pro_prix_vente NUMERIC(10, 2) DEFAULT NULL, ADD se_compose_de_cmd_prix_tot NUMERIC(10, 2) DEFAULT NULL');
        $this->addSql('ALTER TABLE se_compose_de RENAME INDEX idx_5df0822d6369ef3 TO se_compose_de_cmd_id');
        $this->addSql('ALTER TABLE sous_rubrique CHANGE s_rub_rub_id s_rub_rub_id INT NOT NULL');
    }
}
