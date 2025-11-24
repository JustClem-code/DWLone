<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251124162041 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pallet DROP CONSTRAINT fk_616da2a72b0e8d99');
        $this->addSql('DROP INDEX idx_616da2a72b0e8d99');
        $this->addSql('ALTER TABLE pallet DROP associate_id');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE pallet ADD associate_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE pallet ADD CONSTRAINT fk_616da2a72b0e8d99 FOREIGN KEY (associate_id) REFERENCES associate (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_616da2a72b0e8d99 ON pallet (associate_id)');
    }
}
