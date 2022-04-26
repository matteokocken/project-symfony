<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220426140451 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_E3BB1FCA4584665A');
        $this->addSql('DROP INDEX IDX_E3BB1FCAA76ED395');
        $this->addSql('CREATE TEMPORARY TABLE __temp__im22_panier AS SELECT id, user_id, product_id, quantity FROM im22_panier');
        $this->addSql('DROP TABLE im22_panier');
        $this->addSql('CREATE TABLE im22_panier (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER DEFAULT NULL, product_id INTEGER NOT NULL, quantity INTEGER NOT NULL, CONSTRAINT FK_E3BB1FCAA76ED395 FOREIGN KEY (user_id) REFERENCES im22_users (id) NOT DEFERRABLE INITIALLY IMMEDIATE, CONSTRAINT FK_E3BB1FCA4584665A FOREIGN KEY (product_id) REFERENCES im22_product (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO im22_panier (id, user_id, product_id, quantity) SELECT id, user_id, product_id, quantity FROM __temp__im22_panier');
        $this->addSql('DROP TABLE __temp__im22_panier');
        $this->addSql('CREATE INDEX IDX_E3BB1FCA4584665A ON im22_panier (product_id)');
        $this->addSql('CREATE INDEX IDX_E3BB1FCAA76ED395 ON im22_panier (user_id)');
        $this->addSql('DROP INDEX fullname_unique_id');
        $this->addSql('CREATE TEMPORARY TABLE __temp__im22_users AS SELECT id, login, first_name, last_name, date_of_birth FROM im22_users');
        $this->addSql('DROP TABLE im22_users');
        $this->addSql('CREATE TABLE im22_users (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, login VARCHAR(100) NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, date_of_birth DATETIME NOT NULL)');
        $this->addSql('INSERT INTO im22_users (id, login, first_name, last_name, date_of_birth) SELECT id, login, first_name, last_name, date_of_birth FROM __temp__im22_users');
        $this->addSql('DROP TABLE __temp__im22_users');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_E3BB1FCAA76ED395');
        $this->addSql('DROP INDEX IDX_E3BB1FCA4584665A');
        $this->addSql('CREATE TEMPORARY TABLE __temp__im22_panier AS SELECT id, user_id, product_id, quantity FROM im22_panier');
        $this->addSql('DROP TABLE im22_panier');
        $this->addSql('CREATE TABLE im22_panier (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER DEFAULT NULL, product_id INTEGER NOT NULL, quantity INTEGER NOT NULL)');
        $this->addSql('INSERT INTO im22_panier (id, user_id, product_id, quantity) SELECT id, user_id, product_id, quantity FROM __temp__im22_panier');
        $this->addSql('DROP TABLE __temp__im22_panier');
        $this->addSql('CREATE INDEX IDX_E3BB1FCAA76ED395 ON im22_panier (user_id)');
        $this->addSql('CREATE INDEX IDX_E3BB1FCA4584665A ON im22_panier (product_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__im22_users AS SELECT id, login, first_name, last_name, date_of_birth FROM im22_users');
        $this->addSql('DROP TABLE im22_users');
        $this->addSql('CREATE TABLE im22_users (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, login VARCHAR(100) NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, date_of_birth DATETIME NOT NULL)');
        $this->addSql('INSERT INTO im22_users (id, login, first_name, last_name, date_of_birth) SELECT id, login, first_name, last_name, date_of_birth FROM __temp__im22_users');
        $this->addSql('DROP TABLE __temp__im22_users');
        $this->addSql('CREATE UNIQUE INDEX fullname_unique_id ON im22_users (first_name, last_name)');
    }
}
