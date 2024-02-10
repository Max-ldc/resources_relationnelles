<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240208222614 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE "user_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "user_data_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "user" (id INT NOT NULL, username VARCHAR(255) NOT NULL, account_enabled BOOLEAN DEFAULT true NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX username ON "user" (username)');
        $this->addSql('CREATE TABLE "user_data" (id INT NOT NULL, user_id INT NOT NULL, email_encrypted VARCHAR(255) NOT NULL, email_hash VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_D772BFAAA76ED395 ON "user_data" (user_id)');
        $this->addSql('CREATE UNIQUE INDEX email_hash ON "user_data" (email_hash)');
        $this->addSql('ALTER TABLE "user_data" ADD CONSTRAINT FK_D772BFAAA76ED395 FOREIGN KEY (user_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "user_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE "user_data_id_seq" CASCADE');
        $this->addSql('ALTER TABLE "user_data" DROP CONSTRAINT FK_D772BFAAA76ED395');
        $this->addSql('DROP TABLE "user"');
        $this->addSql('DROP TABLE "user_data"');
    }
}
