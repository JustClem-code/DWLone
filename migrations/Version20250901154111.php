<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250901154111 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cart (id SERIAL NOT NULL, road_id INT DEFAULT NULL, stagging_id INT DEFAULT NULL, associate_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BA388B7962F8178 ON cart (road_id)');
        $this->addSql('CREATE INDEX IDX_BA388B71ACAAAF4 ON cart (stagging_id)');
        $this->addSql('CREATE INDEX IDX_BA388B72B0E8D99 ON cart (associate_id)');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B7962F8178 FOREIGN KEY (road_id) REFERENCES road (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B71ACAAAF4 FOREIGN KEY (stagging_id) REFERENCES stagging (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B72B0E8D99 FOREIGN KEY (associate_id) REFERENCES associate (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE cart DROP CONSTRAINT FK_BA388B7962F8178');
        $this->addSql('ALTER TABLE cart DROP CONSTRAINT FK_BA388B71ACAAAF4');
        $this->addSql('ALTER TABLE cart DROP CONSTRAINT FK_BA388B72B0E8D99');
        $this->addSql('DROP TABLE cart');
    }
}
