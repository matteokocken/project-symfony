<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220426083631 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE im22_panier (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, quantity INTEGER NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E3BB1FCAA76ED395 ON im22_panier (user_id)');
        $this->addSql('DROP INDEX IDX_FB8FCB21F77D927C');
        $this->addSql('CREATE TEMPORARY TABLE __temp__im22_product AS SELECT id, panier_id, libelle, prix, en_stock FROM im22_product');
        $this->addSql('DROP TABLE im22_product');
        $this->addSql('CREATE TABLE im22_product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, panier_id INTEGER DEFAULT NULL, libelle VARCHAR(255) NOT NULL, prix INTEGER NOT NULL, en_stock INTEGER NOT NULL, CONSTRAINT FK_FB8FCB21F77D927C FOREIGN KEY (panier_id) REFERENCES im22_panier (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO im22_product (id, panier_id, libelle, prix, en_stock) SELECT id, panier_id, libelle, prix, en_stock FROM __temp__im22_product');
        $this->addSql('DROP TABLE __temp__im22_product');
        $this->addSql('CREATE INDEX IDX_FB8FCB21F77D927C ON im22_product (panier_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE im22_panier');
    }
}
