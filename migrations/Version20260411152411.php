<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260411152411 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE road_part DROP CONSTRAINT fk_5d3bb0379d86650f');
        $this->addSql('ALTER TABLE road_part DROP CONSTRAINT fk_5d3bb037b5ea681d');
        $this->addSql('DROP INDEX idx_5d3bb0379d86650f');
        $this->addSql('DROP INDEX idx_5d3bb037b5ea681d');
        $this->addSql('ALTER TABLE road_part RENAME COLUMN road_id_id TO road_id');
        $this->addSql('ALTER TABLE road_part RENAME COLUMN user_id_id TO user_id');
        $this->addSql('ALTER TABLE road_part ADD CONSTRAINT FK_5D3BB037962F8178 FOREIGN KEY (road_id) REFERENCES road (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE road_part ADD CONSTRAINT FK_5D3BB037A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_5D3BB037962F8178 ON road_part (road_id)');
        $this->addSql('CREATE INDEX IDX_5D3BB037A76ED395 ON road_part (user_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE road_part DROP CONSTRAINT FK_5D3BB037962F8178');
        $this->addSql('ALTER TABLE road_part DROP CONSTRAINT FK_5D3BB037A76ED395');
        $this->addSql('DROP INDEX IDX_5D3BB037962F8178');
        $this->addSql('DROP INDEX IDX_5D3BB037A76ED395');
        $this->addSql('ALTER TABLE road_part RENAME COLUMN road_id TO road_id_id');
        $this->addSql('ALTER TABLE road_part RENAME COLUMN user_id TO user_id_id');
        $this->addSql('ALTER TABLE road_part ADD CONSTRAINT fk_5d3bb0379d86650f FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE road_part ADD CONSTRAINT fk_5d3bb037b5ea681d FOREIGN KEY (road_id_id) REFERENCES road (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_5d3bb0379d86650f ON road_part (user_id_id)');
        $this->addSql('CREATE INDEX idx_5d3bb037b5ea681d ON road_part (road_id_id)');
    }
}
