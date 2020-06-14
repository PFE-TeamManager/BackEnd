<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200614135452 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE user_image DROP FOREIGN KEY FK_27FFFF073DA5256D');
        $this->addSql('DROP TABLE image');
        $this->addSql('DROP TABLE user_image');
        $this->addSql('ALTER TABLE task CHANGE user_id user_id INT DEFAULT NULL, CHANGE to_do to_do TINYINT(1) DEFAULT NULL, CHANGE to_do_date to_do_date DATETIME DEFAULT NULL, CHANGE doing doing TINYINT(1) DEFAULT NULL, CHANGE datedoing datedoing DATETIME DEFAULT NULL, CHANGE done done TINYINT(1) DEFAULT NULL, CHANGE datedone datedone DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE team CHANGE project_id project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE teams_id teams_id INT DEFAULT NULL, CHANGE phone phone VARCHAR(14) DEFAULT NULL, CHANGE roles roles JSON NOT NULL, CHANGE date_embauchement date_embauchement DATETIME DEFAULT NULL, CHANGE date_resignation date_resignation DATETIME DEFAULT NULL, CHANGE password_change_date password_change_date INT DEFAULT NULL, CHANGE confirmation_token confirmation_token VARCHAR(40) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, url VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE user_image (user_id INT NOT NULL, image_id INT NOT NULL, INDEX IDX_27FFFF073DA5256D (image_id), INDEX IDX_27FFFF07A76ED395 (user_id), PRIMARY KEY(user_id, image_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE user_image ADD CONSTRAINT FK_27FFFF073DA5256D FOREIGN KEY (image_id) REFERENCES image (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_image ADD CONSTRAINT FK_27FFFF07A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE task CHANGE user_id user_id INT DEFAULT NULL, CHANGE to_do to_do TINYINT(1) DEFAULT \'NULL\', CHANGE to_do_date to_do_date DATETIME DEFAULT \'NULL\', CHANGE doing doing TINYINT(1) DEFAULT \'NULL\', CHANGE datedoing datedoing DATETIME DEFAULT \'NULL\', CHANGE done done TINYINT(1) DEFAULT \'NULL\', CHANGE datedone datedone DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE team CHANGE project_id project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE teams_id teams_id INT DEFAULT NULL, CHANGE phone phone VARCHAR(14) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE date_embauchement date_embauchement DATETIME DEFAULT \'NULL\', CHANGE password_change_date password_change_date INT DEFAULT NULL, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE date_resignation date_resignation DATETIME DEFAULT \'NULL\', CHANGE confirmation_token confirmation_token VARCHAR(40) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
