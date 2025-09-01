<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250901160733 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE bag (id SERIAL NOT NULL, road_id INT DEFAULT NULL, location_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, damaged BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1B226841962F8178 ON bag (road_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1B22684164D218E ON bag (location_id)');
        $this->addSql('ALTER TABLE bag ADD CONSTRAINT FK_1B226841962F8178 FOREIGN KEY (road_id) REFERENCES road (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE bag ADD CONSTRAINT FK_1B22684164D218E FOREIGN KEY (location_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE package ADD location_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE package ADD bag_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE package ADD CONSTRAINT FK_DE68679564D218E FOREIGN KEY (location_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE package ADD CONSTRAINT FK_DE6867956F5D8297 FOREIGN KEY (bag_id) REFERENCES bag (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_DE68679564D218E ON package (location_id)');
        $this->addSql('CREATE INDEX IDX_DE6867956F5D8297 ON package (bag_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE package DROP CONSTRAINT FK_DE6867956F5D8297');
        $this->addSql('ALTER TABLE bag DROP CONSTRAINT FK_1B226841962F8178');
        $this->addSql('ALTER TABLE bag DROP CONSTRAINT FK_1B22684164D218E');
        $this->addSql('DROP TABLE bag');
        $this->addSql('ALTER TABLE package DROP CONSTRAINT FK_DE68679564D218E');
        $this->addSql('DROP INDEX IDX_DE68679564D218E');
        $this->addSql('DROP INDEX IDX_DE6867956F5D8297');
        $this->addSql('ALTER TABLE package DROP location_id');
        $this->addSql('ALTER TABLE package DROP bag_id');
    }
}
