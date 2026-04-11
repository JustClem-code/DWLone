<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260411152729 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE cart DROP CONSTRAINT fk_ba388b7bf8a8f89');
        $this->addSql('DROP INDEX uniq_ba388b7bf8a8f89');
        $this->addSql('ALTER TABLE cart RENAME COLUMN road_part_id_id TO road_part_id');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B712AB71E4 FOREIGN KEY (road_part_id) REFERENCES road_part (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BA388B712AB71E4 ON cart (road_part_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE cart DROP CONSTRAINT FK_BA388B712AB71E4');
        $this->addSql('DROP INDEX UNIQ_BA388B712AB71E4');
        $this->addSql('ALTER TABLE cart RENAME COLUMN road_part_id TO road_part_id_id');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT fk_ba388b7bf8a8f89 FOREIGN KEY (road_part_id_id) REFERENCES road_part (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE UNIQUE INDEX uniq_ba388b7bf8a8f89 ON cart (road_part_id_id)');
    }
}
