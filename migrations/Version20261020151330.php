<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261020151330 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE group_postcodes ADD aisle_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE group_postcodes ADD CONSTRAINT FK_B16E8ABC23C1884A FOREIGN KEY (aisle_id) REFERENCES aisle (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_B16E8ABC23C1884A ON group_postcodes (aisle_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE group_postcodes DROP CONSTRAINT FK_B16E8ABC23C1884A');
        $this->addSql('DROP INDEX IDX_B16E8ABC23C1884A');
        $this->addSql('ALTER TABLE group_postcodes DROP aisle_id');
    }
}
