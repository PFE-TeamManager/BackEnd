<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200615070406 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE bug (id INT AUTO_INCREMENT NOT NULL, id_task_id INT NOT NULL, created_by_id INT NOT NULL, bug_title VARCHAR(255) NOT NULL, bug_description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_358CBF14532BA8F6 (id_task_id), INDEX IDX_358CBF14B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bug ADD CONSTRAINT FK_358CBF14532BA8F6 FOREIGN KEY (id_task_id) REFERENCES task (id)');
        $this->addSql('ALTER TABLE bug ADD CONSTRAINT FK_358CBF14B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE task CHANGE user_id user_id INT DEFAULT NULL, CHANGE to_do to_do TINYINT(1) DEFAULT NULL, CHANGE to_do_date to_do_date DATETIME DEFAULT NULL, CHANGE doing doing TINYINT(1) DEFAULT NULL, CHANGE datedoing datedoing DATETIME DEFAULT NULL, CHANGE done done TINYINT(1) DEFAULT NULL, CHANGE datedone datedone DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE team CHANGE project_id project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE teams_id teams_id INT DEFAULT NULL, CHANGE phone phone VARCHAR(14) DEFAULT NULL, CHANGE roles roles JSON NOT NULL, CHANGE date_embauchement date_embauchement DATETIME DEFAULT NULL, CHANGE date_resignation date_resignation DATETIME DEFAULT NULL, CHANGE password_change_date password_change_date INT DEFAULT NULL, CHANGE confirmation_token confirmation_token VARCHAR(40) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE bug');
        $this->addSql('ALTER TABLE task CHANGE user_id user_id INT DEFAULT NULL, CHANGE to_do to_do TINYINT(1) DEFAULT \'NULL\', CHANGE to_do_date to_do_date DATETIME DEFAULT \'NULL\', CHANGE doing doing TINYINT(1) DEFAULT \'NULL\', CHANGE datedoing datedoing DATETIME DEFAULT \'NULL\', CHANGE done done TINYINT(1) DEFAULT \'NULL\', CHANGE datedone datedone DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE team CHANGE project_id project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE teams_id teams_id INT DEFAULT NULL, CHANGE phone phone VARCHAR(14) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE date_embauchement date_embauchement DATETIME DEFAULT \'NULL\', CHANGE password_change_date password_change_date INT DEFAULT NULL, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE date_resignation date_resignation DATETIME DEFAULT \'NULL\', CHANGE confirmation_token confirmation_token VARCHAR(40) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
