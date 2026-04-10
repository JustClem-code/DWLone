<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260410151116 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE road_part (id SERIAL NOT NULL, road_id_id INT NOT NULL, user_id_id INT DEFAULT NULL, number INT NOT NULL, stagged BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5D3BB037B5EA681D ON road_part (road_id_id)');
        $this->addSql('CREATE INDEX IDX_5D3BB0379D86650F ON road_part (user_id_id)');
        $this->addSql('ALTER TABLE road_part ADD CONSTRAINT FK_5D3BB037B5EA681D FOREIGN KEY (road_id_id) REFERENCES road (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE road_part ADD CONSTRAINT FK_5D3BB0379D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE road_part DROP CONSTRAINT FK_5D3BB037B5EA681D');
        $this->addSql('ALTER TABLE road_part DROP CONSTRAINT FK_5D3BB0379D86650F');
        $this->addSql('DROP TABLE road_part');
    }
}
