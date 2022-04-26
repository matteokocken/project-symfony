<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220425094013 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_FB8FCB21A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__im22_product AS SELECT id, user_id, libelle, prix, en_stock FROM im22_product');
        $this->addSql('DROP TABLE im22_product');
        $this->addSql('CREATE TABLE im22_product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER DEFAULT NULL, libelle VARCHAR(255) NOT NULL, prix INTEGER NOT NULL, en_stock INTEGER NOT NULL, CONSTRAINT FK_FB8FCB21A76ED395 FOREIGN KEY (user_id) REFERENCES im22_users (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO im22_product (id, user_id, libelle, prix, en_stock) SELECT id, user_id, libelle, prix, en_stock FROM __temp__im22_product');
        $this->addSql('DROP TABLE __temp__im22_product');
        $this->addSql('CREATE INDEX IDX_FB8FCB21A76ED395 ON im22_product (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_FB8FCB21A76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__im22_product AS SELECT id, user_id, libelle, prix, en_stock FROM im22_product');
        $this->addSql('DROP TABLE im22_product');
        $this->addSql('CREATE TABLE im22_product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, libelle VARCHAR(255) NOT NULL, prix INTEGER NOT NULL, en_stock INTEGER NOT NULL)');
        $this->addSql('INSERT INTO im22_product (id, user_id, libelle, prix, en_stock) SELECT id, user_id, libelle, prix, en_stock FROM __temp__im22_product');
        $this->addSql('DROP TABLE __temp__im22_product');
        $this->addSql('CREATE INDEX IDX_FB8FCB21A76ED395 ON im22_product (user_id)');
    }
}
