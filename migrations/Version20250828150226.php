<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250828150226 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE pallet (id SERIAL NOT NULL, truck_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_616DA2A7C6957CCE ON pallet (truck_id)');
        $this->addSql('ALTER TABLE pallet ADD CONSTRAINT FK_616DA2A7C6957CCE FOREIGN KEY (truck_id) REFERENCES truck (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE pallet DROP CONSTRAINT FK_616DA2A7C6957CCE');
        $this->addSql('DROP TABLE pallet');
    }
}
