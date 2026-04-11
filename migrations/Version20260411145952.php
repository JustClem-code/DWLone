<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260411145952 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart DROP CONSTRAINT fk_ba388b7962f8178');
        $this->addSql('ALTER TABLE cart DROP CONSTRAINT fk_ba388b7a3b9e79c');
        $this->addSql('DROP INDEX idx_ba388b7962f8178');
        $this->addSql('DROP INDEX idx_ba388b7a3b9e79c');
        $this->addSql('ALTER TABLE cart ADD road_part_id_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cart DROP road_id');
        $this->addSql('ALTER TABLE cart DROP user_picking_id');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7BF8A8F89 FOREIGN KEY (road_part_id_id) REFERENCES road_part (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BA388B7BF8A8F89 ON cart (road_part_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE cart DROP CONSTRAINT FK_BA388B7BF8A8F89');
        $this->addSql('DROP INDEX UNIQ_BA388B7BF8A8F89');
        $this->addSql('ALTER TABLE cart ADD user_picking_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE cart RENAME COLUMN road_part_id_id TO road_id');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT fk_ba388b7962f8178 FOREIGN KEY (road_id) REFERENCES road (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT fk_ba388b7a3b9e79c FOREIGN KEY (user_picking_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX idx_ba388b7962f8178 ON cart (road_id)');
        $this->addSql('CREATE INDEX idx_ba388b7a3b9e79c ON cart (user_picking_id)');
    }
}
