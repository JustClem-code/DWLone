<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251120162119 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE truck ADD user_dep_date_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE truck ADD CONSTRAINT FK_CDCCF30A4C01995D FOREIGN KEY (user_dep_date_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_CDCCF30A4C01995D ON truck (user_dep_date_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE truck DROP CONSTRAINT FK_CDCCF30A4C01995D');
        $this->addSql('DROP INDEX IDX_CDCCF30A4C01995D');
        $this->addSql('ALTER TABLE truck DROP user_dep_date_id');
    }
}
