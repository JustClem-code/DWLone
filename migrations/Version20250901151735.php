<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250901151735 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE road (id SERIAL NOT NULL, stagging_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_95C0C4B11ACAAAF4 ON road (stagging_id)');
        $this->addSql('CREATE TABLE stagging (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('ALTER TABLE road ADD CONSTRAINT FK_95C0C4B11ACAAAF4 FOREIGN KEY (stagging_id) REFERENCES stagging (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE road DROP CONSTRAINT FK_95C0C4B11ACAAAF4');
        $this->addSql('DROP TABLE road');
        $this->addSql('DROP TABLE stagging');
    }
}
