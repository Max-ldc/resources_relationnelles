<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240207235532 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_data_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, user_data_id INT DEFAULT NULL, user_name VARCHAR(255) NOT NULL, account_enabled BOOLEAN DEFAULT true NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_8D93D6496FF8BF36 ON "user" (user_data_id)');
        $this->addSql('CREATE UNIQUE INDEX user_name ON "user" (user_name)');
        $this->addSql('CREATE TABLE "user_data" (id INT NOT NULL, email_encrypted VARCHAR(255) NOT NULL, email_hash VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX email_hash ON "user_data" (email_hash)');
        $this->addSql('ALTER TABLE "user" ADD CONSTRAINT FK_8D93D6496FF8BF36 FOREIGN KEY (user_data_id) REFERENCES "user_data" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE "user_data_id_seq" CASCADE');
        $this->addSql('ALTER TABLE "user" DROP CONSTRAINT FK_8D93D6496FF8BF36');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE "user_data"');
    }
}
