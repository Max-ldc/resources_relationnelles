<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240311140314 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        $this->addSql('CREATE SEQUENCE "relation_type_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "resource_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE "resource_metadata_id_seq" INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE "relation_type" (id INT NOT NULL, parent_id INT DEFAULT NULL, type VARCHAR(255) NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_3BF454A4727ACA70 ON "relation_type" (parent_id)');
        $this->addSql('CREATE TABLE "resource" (id INT NOT NULL, user_data_id INT NOT NULL, file_name VARCHAR(255) NOT NULL, shared_status VARCHAR(255) CHECK(shared_status IN (\'public\', \'shared\', \'private\')) NOT NULL, category VARCHAR(255) CHECK(category IN (\'communication\', \'culture\', \'developpement_personnel\', \'intelligence_emotionnelle\', \'loisirs\', \'monde_professionnel\', \'parentalite\', \'qualite_de_vie\', \'recherche_de_sens\', \'sante_physique\', \'sante_psychique\', \'spiritualite\', \'vie_affective\')) NOT NULL, type VARCHAR(255) CHECK(type IN (\'article\', \'carte_defi\', \'cours_pdf\', \'excercice\', \'fiche_lecture\', \'video\', \'audio\', \'game\')) NOT NULL, creation_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, modification_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_BC91F4166FF8BF36 ON "resource" (user_data_id)');
        $this->addSql('COMMENT ON COLUMN "resource".shared_status IS \'(DC2Type:resourceSharedStatusType)\'');
        $this->addSql('COMMENT ON COLUMN "resource".category IS \'(DC2Type:resourceCategoryType)\'');
        $this->addSql('COMMENT ON COLUMN "resource".type IS \'(DC2Type:resourceTypeType)\'');
        $this->addSql('COMMENT ON COLUMN "resource".creation_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE resource_relation_type (resource_id INT NOT NULL, relation_type_id INT NOT NULL, PRIMARY KEY(resource_id, relation_type_id))');
        $this->addSql('CREATE INDEX IDX_7218726289329D25 ON resource_relation_type (resource_id)');
        $this->addSql('CREATE INDEX IDX_72187262DC379EE2 ON resource_relation_type (relation_type_id)');
        $this->addSql('CREATE TABLE "resource_metadata" (id INT NOT NULL, resource_id INT NOT NULL, title VARCHAR(255) NOT NULL, duration INT DEFAULT NULL, format VARCHAR(255) DEFAULT NULL, author VARCHAR(255) DEFAULT NULL, album VARCHAR(255) DEFAULT NULL, genre VARCHAR(255) DEFAULT NULL, release_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, creation_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP NOT NULL, modification_date TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT CURRENT_TIMESTAMP, PRIMARY KEY(id))');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E198FEB989329D25 ON "resource_metadata" (resource_id)');
        $this->addSql('COMMENT ON COLUMN "resource_metadata".creation_date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE "relation_type" ADD CONSTRAINT FK_3BF454A4727ACA70 FOREIGN KEY (parent_id) REFERENCES "relation_type" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "resource" ADD CONSTRAINT FK_BC91F4166FF8BF36 FOREIGN KEY (user_data_id) REFERENCES "user_data" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE resource_relation_type ADD CONSTRAINT FK_7218726289329D25 FOREIGN KEY (resource_id) REFERENCES "resource" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE resource_relation_type ADD CONSTRAINT FK_72187262DC379EE2 FOREIGN KEY (relation_type_id) REFERENCES "relation_type" (id) ON DELETE CASCADE NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE "resource_metadata" ADD CONSTRAINT FK_E198FEB989329D25 FOREIGN KEY (resource_id) REFERENCES "resource" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('DROP SEQUENCE "relation_type_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE "resource_id_seq" CASCADE');
        $this->addSql('DROP SEQUENCE "resource_metadata_id_seq" CASCADE');
        $this->addSql('ALTER TABLE "relation_type" DROP CONSTRAINT FK_3BF454A4727ACA70');
        $this->addSql('ALTER TABLE "resource" DROP CONSTRAINT FK_BC91F4166FF8BF36');
        $this->addSql('ALTER TABLE resource_relation_type DROP CONSTRAINT FK_7218726289329D25');
        $this->addSql('ALTER TABLE resource_relation_type DROP CONSTRAINT FK_72187262DC379EE2');
        $this->addSql('ALTER TABLE "resource_metadata" DROP CONSTRAINT FK_E198FEB989329D25');
        $this->addSql('DROP TABLE "relation_type"');
        $this->addSql('DROP TABLE "resource"');
        $this->addSql('DROP TABLE resource_relation_type');
        $this->addSql('DROP TABLE "resource_metadata"');
    }
}
