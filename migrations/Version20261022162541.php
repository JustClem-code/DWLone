<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20261022162541 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE address (id SERIAL NOT NULL, customer_id INT NOT NULL, city VARCHAR(255) NOT NULL, postcode VARCHAR(255) NOT NULL, street_address VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_D4E6F819395C3F3 ON address (customer_id)');
        $this->addSql('CREATE TABLE aisle (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, enabled BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE associate (id SERIAL NOT NULL, role_id INT NOT NULL, username VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CCE5D25D60322AC ON associate (role_id)');
        $this->addSql('CREATE TABLE bag (id SERIAL NOT NULL, road_part_id INT DEFAULT NULL, location_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, damaged BOOLEAN NOT NULL, picked BOOLEAN DEFAULT NULL, loaded BOOLEAN DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_1B22684112AB71E4 ON bag (road_part_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_1B22684164D218E ON bag (location_id)');
        $this->addSql('CREATE TABLE cart (id SERIAL NOT NULL, stagging_id INT DEFAULT NULL, road_part_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BA388B71ACAAAF4 ON cart (stagging_id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_BA388B712AB71E4 ON cart (road_part_id)');
        $this->addSql('CREATE TABLE customer (id SERIAL NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE delivery_company (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE delivery_person (id SERIAL NOT NULL, company_id INT NOT NULL, username VARCHAR(255) NOT NULL, firstname VARCHAR(255) NOT NULL, lastname VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_2ACD28F8979B1AD6 ON delivery_person (company_id)');
        $this->addSql('CREATE TABLE dock (id SERIAL NOT NULL, truck_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_423BB3E1C6957CCE ON dock (truck_id)');
        $this->addSql('CREATE TABLE group_postcodes (id SERIAL NOT NULL, aisle_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_B16E8ABC23C1884A ON group_postcodes (aisle_id)');
        $this->addSql('CREATE TABLE location (id SERIAL NOT NULL, aisle_id INT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5E9E89CB23C1884A ON location (aisle_id)');
        $this->addSql('CREATE TABLE "order" (id SERIAL NOT NULL, address_id INT NOT NULL, customer_id INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_F5299398F5B7AF75 ON "order" (address_id)');
        $this->addSql('CREATE INDEX IDX_F52993989395C3F3 ON "order" (customer_id)');
        $this->addSql('CREATE TABLE package (id SERIAL NOT NULL, packaging_id INT DEFAULT NULL, order_id_id INT NOT NULL, location_id INT DEFAULT NULL, bag_id INT DEFAULT NULL, pallet_id INT DEFAULT NULL, user_stow_id INT DEFAULT NULL, weight INT NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_DE6867954E7B3801 ON package (packaging_id)');
        $this->addSql('CREATE INDEX IDX_DE686795FCDAEAAA ON package (order_id_id)');
        $this->addSql('CREATE INDEX IDX_DE68679564D218E ON package (location_id)');
        $this->addSql('CREATE INDEX IDX_DE6867956F5D8297 ON package (bag_id)');
        $this->addSql('CREATE INDEX IDX_DE68679515444D3A ON package (pallet_id)');
        $this->addSql('CREATE INDEX IDX_DE686795FCD8208E ON package (user_stow_id)');
        $this->addSql('CREATE TABLE packaging (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, weight INT NOT NULL, lenght INT NOT NULL, width INT NOT NULL, height INT NOT NULL, oversize BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE pallet (id SERIAL NOT NULL, truck_id INT DEFAULT NULL, user_id_id INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_616DA2A7C6957CCE ON pallet (truck_id)');
        $this->addSql('CREATE INDEX IDX_616DA2A79D86650F ON pallet (user_id_id)');
        $this->addSql('CREATE TABLE postcodes (id SERIAL NOT NULL, group_postcodes_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_71DDD65D5E237E06 ON postcodes (name)');
        $this->addSql('CREATE INDEX IDX_71DDD65DC163FE6B ON postcodes (group_postcodes_id)');
        $this->addSql('CREATE TABLE product (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, price DOUBLE PRECISION NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE road (id SERIAL NOT NULL, stagging_id INT DEFAULT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_95C0C4B11ACAAAF4 ON road (stagging_id)');
        $this->addSql('CREATE TABLE road_part (id SERIAL NOT NULL, road_id INT NOT NULL, user_id INT DEFAULT NULL, number INT NOT NULL, stagged BOOLEAN NOT NULL, picking_started_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, stagged_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, picking_duration_seconds INT DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_5D3BB037962F8178 ON road_part (road_id)');
        $this->addSql('CREATE INDEX IDX_5D3BB037A76ED395 ON road_part (user_id)');
        $this->addSql('COMMENT ON COLUMN road_part.picking_started_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN road_part.stagged_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE role (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE stagging (id SERIAL NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE truck (id SERIAL NOT NULL, user_del_date_id INT DEFAULT NULL, user_dep_date_id INT DEFAULT NULL, name VARCHAR(7) NOT NULL, expected_date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, delivery_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, departure_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_CDCCF30A6F52838 ON truck (user_del_date_id)');
        $this->addSql('CREATE INDEX IDX_CDCCF30A4C01995D ON truck (user_dep_date_id)');
        $this->addSql('CREATE TABLE "user" (id SERIAL NOT NULL, username VARCHAR(180) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_IDENTIFIER_USERNAME ON "user" (username)');
        $this->addSql('ALTER TABLE address ADD CONSTRAINT FK_D4E6F819395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE associate ADD CONSTRAINT FK_CCE5D25D60322AC FOREIGN KEY (role_id) REFERENCES role (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE bag ADD CONSTRAINT FK_1B22684112AB71E4 FOREIGN KEY (road_part_id) REFERENCES road_part (id) ON DELETE SET NULL NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE bag ADD CONSTRAINT FK_1B22684164D218E FOREIGN KEY (location_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B71ACAAAF4 FOREIGN KEY (stagging_id) REFERENCES stagging (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE cart ADD CONSTRAINT FK_BA388B712AB71E4 FOREIGN KEY (road_part_id) REFERENCES road_part (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE delivery_person ADD CONSTRAINT FK_2ACD28F8979B1AD6 FOREIGN KEY (company_id) REFERENCES delivery_company (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE dock ADD CONSTRAINT FK_423BB3E1C6957CCE FOREIGN KEY (truck_id) REFERENCES truck (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE group_postcodes ADD CONSTRAINT FK_B16E8ABC23C1884A FOREIGN KEY (aisle_id) REFERENCES aisle (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE location ADD CONSTRAINT FK_5E9E89CB23C1884A FOREIGN KEY (aisle_id) REFERENCES aisle (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F5299398F5B7AF75 FOREIGN KEY (address_id) REFERENCES address (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "order" ADD CONSTRAINT FK_F52993989395C3F3 FOREIGN KEY (customer_id) REFERENCES customer (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE package ADD CONSTRAINT FK_DE6867954E7B3801 FOREIGN KEY (packaging_id) REFERENCES packaging (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE package ADD CONSTRAINT FK_DE686795FCDAEAAA FOREIGN KEY (order_id_id) REFERENCES "order" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE package ADD CONSTRAINT FK_DE68679564D218E FOREIGN KEY (location_id) REFERENCES location (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE package ADD CONSTRAINT FK_DE6867956F5D8297 FOREIGN KEY (bag_id) REFERENCES bag (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE package ADD CONSTRAINT FK_DE68679515444D3A FOREIGN KEY (pallet_id) REFERENCES pallet (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE package ADD CONSTRAINT FK_DE686795FCD8208E FOREIGN KEY (user_stow_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pallet ADD CONSTRAINT FK_616DA2A7C6957CCE FOREIGN KEY (truck_id) REFERENCES truck (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE pallet ADD CONSTRAINT FK_616DA2A79D86650F FOREIGN KEY (user_id_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE postcodes ADD CONSTRAINT FK_71DDD65DC163FE6B FOREIGN KEY (group_postcodes_id) REFERENCES group_postcodes (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE road ADD CONSTRAINT FK_95C0C4B11ACAAAF4 FOREIGN KEY (stagging_id) REFERENCES stagging (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE road_part ADD CONSTRAINT FK_5D3BB037962F8178 FOREIGN KEY (road_id) REFERENCES road (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE road_part ADD CONSTRAINT FK_5D3BB037A76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE truck ADD CONSTRAINT FK_CDCCF30A6F52838 FOREIGN KEY (user_del_date_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE truck ADD CONSTRAINT FK_CDCCF30A4C01995D FOREIGN KEY (user_dep_date_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE address DROP CONSTRAINT FK_D4E6F819395C3F3');
        $this->addSql('ALTER TABLE associate DROP CONSTRAINT FK_CCE5D25D60322AC');
        $this->addSql('ALTER TABLE bag DROP CONSTRAINT FK_1B22684112AB71E4');
        $this->addSql('ALTER TABLE bag DROP CONSTRAINT FK_1B22684164D218E');
        $this->addSql('ALTER TABLE cart DROP CONSTRAINT FK_BA388B71ACAAAF4');
        $this->addSql('ALTER TABLE cart DROP CONSTRAINT FK_BA388B712AB71E4');
        $this->addSql('ALTER TABLE delivery_person DROP CONSTRAINT FK_2ACD28F8979B1AD6');
        $this->addSql('ALTER TABLE dock DROP CONSTRAINT FK_423BB3E1C6957CCE');
        $this->addSql('ALTER TABLE group_postcodes DROP CONSTRAINT FK_B16E8ABC23C1884A');
        $this->addSql('ALTER TABLE location DROP CONSTRAINT FK_5E9E89CB23C1884A');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F5299398F5B7AF75');
        $this->addSql('ALTER TABLE "order" DROP CONSTRAINT FK_F52993989395C3F3');
        $this->addSql('ALTER TABLE package DROP CONSTRAINT FK_DE6867954E7B3801');
        $this->addSql('ALTER TABLE package DROP CONSTRAINT FK_DE686795FCDAEAAA');
        $this->addSql('ALTER TABLE package DROP CONSTRAINT FK_DE68679564D218E');
        $this->addSql('ALTER TABLE package DROP CONSTRAINT FK_DE6867956F5D8297');
        $this->addSql('ALTER TABLE package DROP CONSTRAINT FK_DE68679515444D3A');
        $this->addSql('ALTER TABLE package DROP CONSTRAINT FK_DE686795FCD8208E');
        $this->addSql('ALTER TABLE pallet DROP CONSTRAINT FK_616DA2A7C6957CCE');
        $this->addSql('ALTER TABLE pallet DROP CONSTRAINT FK_616DA2A79D86650F');
        $this->addSql('ALTER TABLE postcodes DROP CONSTRAINT FK_71DDD65DC163FE6B');
        $this->addSql('ALTER TABLE road DROP CONSTRAINT FK_95C0C4B11ACAAAF4');
        $this->addSql('ALTER TABLE road_part DROP CONSTRAINT FK_5D3BB037962F8178');
        $this->addSql('ALTER TABLE road_part DROP CONSTRAINT FK_5D3BB037A76ED395');
        $this->addSql('ALTER TABLE truck DROP CONSTRAINT FK_CDCCF30A6F52838');
        $this->addSql('ALTER TABLE truck DROP CONSTRAINT FK_CDCCF30A4C01995D');
        $this->addSql('DROP TABLE address');
        $this->addSql('DROP TABLE aisle');
        $this->addSql('DROP TABLE associate');
        $this->addSql('DROP TABLE bag');
        $this->addSql('DROP TABLE cart');
        $this->addSql('DROP TABLE customer');
        $this->addSql('DROP TABLE delivery_company');
        $this->addSql('DROP TABLE delivery_person');
        $this->addSql('DROP TABLE dock');
        $this->addSql('DROP TABLE group_postcodes');
        $this->addSql('DROP TABLE location');
        $this->addSql('DROP TABLE "order"');
        $this->addSql('DROP TABLE package');
        $this->addSql('DROP TABLE packaging');
        $this->addSql('DROP TABLE pallet');
        $this->addSql('DROP TABLE postcodes');
        $this->addSql('DROP TABLE product');
        $this->addSql('DROP TABLE road');
        $this->addSql('DROP TABLE road_part');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE stagging');
        $this->addSql('DROP TABLE truck');
        $this->addSql('DROP TABLE "user"');
    }
}
