<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220425093735 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE im22_product (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, user_id INTEGER NOT NULL, libelle VARCHAR(255) NOT NULL, prix INTEGER NOT NULL, en_stock INTEGER NOT NULL)');
        $this->addSql('CREATE INDEX IDX_FB8FCB21A76ED395 ON im22_product (user_id)');
        $this->addSql('CREATE TABLE im22_users (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, login VARCHAR(100) NOT NULL, first_name VARCHAR(100) NOT NULL, last_name VARCHAR(100) NOT NULL, date_of_birth DATETIME NOT NULL)');
        $this->addSql('CREATE UNIQUE INDEX fullname_unique_id ON im22_users (first_name, last_name)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE im22_product');
        $this->addSql('DROP TABLE im22_users');
    }
}
