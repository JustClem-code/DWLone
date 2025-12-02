<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251202161806 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE package DROP CONSTRAINT fk_de6867952b0e8d99');
        $this->addSql('DROP INDEX idx_de6867952b0e8d99');
        $this->addSql('ALTER TABLE package DROP associate_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE package ADD associate_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE package ADD CONSTRAINT fk_de6867952b0e8d99 FOREIGN KEY (associate_id) REFERENCES associate (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_de6867952b0e8d99 ON package (associate_id)');
    }
}
