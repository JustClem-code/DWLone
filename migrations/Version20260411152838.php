<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260411152838 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bag DROP CONSTRAINT fk_1b226841bf8a8f89');
        $this->addSql('DROP INDEX idx_1b226841bf8a8f89');
        $this->addSql('ALTER TABLE bag RENAME COLUMN road_part_id_id TO road_part_id');
        $this->addSql('ALTER TABLE bag ADD CONSTRAINT FK_1B22684112AB71E4 FOREIGN KEY (road_part_id) REFERENCES road_part (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_1B22684112AB71E4 ON bag (road_part_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE bag DROP CONSTRAINT FK_1B22684112AB71E4');
        $this->addSql('DROP INDEX IDX_1B22684112AB71E4');
        $this->addSql('ALTER TABLE bag RENAME COLUMN road_part_id TO road_part_id_id');
        $this->addSql('ALTER TABLE bag ADD CONSTRAINT fk_1b226841bf8a8f89 FOREIGN KEY (road_part_id_id) REFERENCES road_part (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_1b226841bf8a8f89 ON bag (road_part_id_id)');
    }
}
