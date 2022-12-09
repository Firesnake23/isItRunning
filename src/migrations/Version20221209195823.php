<?php

declare(strict_types=1);

namespace firesnake\isItRunning\migrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221209195823 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE IF NOT EXISTS checks (id VARCHAR(255) NOT NULL, owner_id VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, checker VARCHAR(255) NOT NULL, runnerConfig VARCHAR(4096) NOT NULL, useHeaders TINYINT(1) NOT NULL, UNIQUE INDEX UNIQ_9F8C00795E237E06 (name), INDEX IDX_9F8C00797E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB;');
        $this->addSql('CREATE TABLE IF NOT EXISTS environments (id VARCHAR(255) NOT NULL, owner_id VARCHAR(255) DEFAULT NULL, name VARCHAR(255) NOT NULL, samplingRate VARCHAR(255) NOT NULL, INDEX IDX_CE28A8317E3C61F9 (owner_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB;');
        $this->addSql('CREATE TABLE IF NOT EXISTS environment_checks (checkableenvironment_id VARCHAR(255) NOT NULL, check_id VARCHAR(255) NOT NULL, INDEX IDX_E6576F55563B9312 (checkableenvironment_id), INDEX IDX_E6576F55709385E7 (check_id), PRIMARY KEY(checkableenvironment_id, check_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB;');
        $this->addSql('CREATE TABLE IF NOT EXISTS check_result (id VARCHAR(255) NOT NULL, environment_result VARCHAR(255) DEFAULT NULL, check_id VARCHAR(255) DEFAULT NULL, passed TINYINT(1) NOT NULL, comment VARCHAR(255) DEFAULT NULL, INDEX IDX_669A38BC6AB1AE3F (environment_result), INDEX IDX_669A38BC709385E7 (check_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB;');
        $this->addSql('CREATE TABLE IF NOT EXISTS environment_result (id VARCHAR(255) NOT NULL, environment_id VARCHAR(255) DEFAULT NULL, performed DATETIME NOT NULL, INDEX IDX_6AB1AE3F903E3A94 (environment_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB;');
        $this->addSql('CREATE TABLE IF NOT EXISTS environment_variables (id VARCHAR(255) NOT NULL, environment VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL, INDEX IDX_4AF8BED84626DE22 (environment), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB;');
        $this->addSql('CREATE TABLE IF NOT EXISTS users (id VARCHAR(255) NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_1483A5E9F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB;');
        $this->addSql('CREATE TABLE IF NOT EXISTS user_settings (id VARCHAR(255) NOT NULL, user_id VARCHAR(255) DEFAULT NULL, `key` VARCHAR(255) NOT NULL, value VARCHAR(255) NOT NULL, INDEX IDX_5C844C5A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB;');
        $this->addSql('ALTER TABLE checks ADD CONSTRAINT FK_9F8C00797E3C61F9 FOREIGN KEY (owner_id) REFERENCES users (id);');
        $this->addSql('ALTER TABLE environments ADD CONSTRAINT FK_CE28A8317E3C61F9 FOREIGN KEY (owner_id) REFERENCES users (id);');
        $this->addSql('ALTER TABLE environment_checks ADD CONSTRAINT FK_E6576F55563B9312 FOREIGN KEY (checkableenvironment_id) REFERENCES environments (id) ON DELETE CASCADE;');
        $this->addSql('ALTER TABLE environment_checks ADD CONSTRAINT FK_E6576F55709385E7 FOREIGN KEY (check_id) REFERENCES checks (id) ON DELETE CASCADE;');
        $this->addSql('ALTER TABLE check_result ADD CONSTRAINT FK_669A38BC6AB1AE3F FOREIGN KEY (environment_result) REFERENCES environment_result (id) ON DELETE CASCADE;');
        $this->addSql('ALTER TABLE check_result ADD CONSTRAINT FK_669A38BC709385E7 FOREIGN KEY (check_id) REFERENCES checks (id);');
        $this->addSql('ALTER TABLE environment_result ADD CONSTRAINT FK_6AB1AE3F903E3A94 FOREIGN KEY (environment_id) REFERENCES environments (id);');
        $this->addSql('ALTER TABLE environment_variables ADD CONSTRAINT FK_4AF8BED84626DE22 FOREIGN KEY (environment) REFERENCES environments (id);');
        $this->addSql('ALTER TABLE user_settings ADD CONSTRAINT FK_5C844C5A76ED395 FOREIGN KEY (user_id) REFERENCES users (id);');

    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs

    }
}
