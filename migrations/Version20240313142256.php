<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240313142256 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('ALTER TABLE "user" ADD role VARCHAR(255) CHECK(role IN (\'citoyen connecté\', \'modérateur\', \'administrateur\', \'super administrateur\')) DEFAULT \'citoyen connecté\' NOT NULL');
        $this->addSql('COMMENT ON COLUMN "user".role IS \'(DC2Type:userRoleType)\'');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE "user" DROP role');
    }
}
