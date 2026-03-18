<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260318162404 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE group_postcodes (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE postcodes (id SERIAL NOT NULL, group_postcodes_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_71DDD65DC163FE6B ON postcodes (group_postcodes_id)');
        $this->addSql('ALTER TABLE postcodes ADD CONSTRAINT FK_71DDD65DC163FE6B FOREIGN KEY (group_postcodes_id) REFERENCES group_postcodes (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE postcodes DROP CONSTRAINT FK_71DDD65DC163FE6B');
        $this->addSql('DROP TABLE group_postcodes');
        $this->addSql('DROP TABLE postcodes');
    }
}
