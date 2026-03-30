<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260330150832 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart DROP CONSTRAINT fk_ba388b72b0e8d99');
        $this->addSql('DROP INDEX idx_ba388b72b0e8d99');
        $this->addSql('ALTER TABLE cart DROP associate_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE cart ADD associate_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT fk_ba388b72b0e8d99 FOREIGN KEY (associate_id) REFERENCES associate (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_ba388b72b0e8d99 ON cart (associate_id)');
    }
}
