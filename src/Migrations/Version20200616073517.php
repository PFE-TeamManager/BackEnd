<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200616073517 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment ADD bug_id INT DEFAULT NULL, CHANGE task_id task_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CFA3DB3D5 FOREIGN KEY (bug_id) REFERENCES bug (id)');
        $this->addSql('CREATE INDEX IDX_9474526CFA3DB3D5 ON comment (bug_id)');
        $this->addSql('ALTER TABLE task CHANGE user_id user_id INT DEFAULT NULL, CHANGE to_do to_do TINYINT(1) DEFAULT NULL, CHANGE to_do_date to_do_date DATETIME DEFAULT NULL, CHANGE doing doing TINYINT(1) DEFAULT NULL, CHANGE datedoing datedoing DATETIME DEFAULT NULL, CHANGE done done TINYINT(1) DEFAULT NULL, CHANGE datedone datedone DATETIME DEFAULT NULL');
        $this->addSql('ALTER TABLE team CHANGE project_id project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE teams_id teams_id INT DEFAULT NULL, CHANGE phone phone VARCHAR(14) DEFAULT NULL, CHANGE roles roles JSON NOT NULL, CHANGE date_embauchement date_embauchement DATETIME DEFAULT NULL, CHANGE date_resignation date_resignation DATETIME DEFAULT NULL, CHANGE password_change_date password_change_date INT DEFAULT NULL, CHANGE confirmation_token confirmation_token VARCHAR(40) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CFA3DB3D5');
        $this->addSql('DROP INDEX IDX_9474526CFA3DB3D5 ON comment');
        $this->addSql('ALTER TABLE comment DROP bug_id, CHANGE task_id task_id INT NOT NULL');
        $this->addSql('ALTER TABLE task CHANGE user_id user_id INT DEFAULT NULL, CHANGE to_do to_do TINYINT(1) DEFAULT \'NULL\', CHANGE to_do_date to_do_date DATETIME DEFAULT \'NULL\', CHANGE doing doing TINYINT(1) DEFAULT \'NULL\', CHANGE datedoing datedoing DATETIME DEFAULT \'NULL\', CHANGE done done TINYINT(1) DEFAULT \'NULL\', CHANGE datedone datedone DATETIME DEFAULT \'NULL\'');
        $this->addSql('ALTER TABLE team CHANGE project_id project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE teams_id teams_id INT DEFAULT NULL, CHANGE phone phone VARCHAR(14) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE date_embauchement date_embauchement DATETIME DEFAULT \'NULL\', CHANGE password_change_date password_change_date INT DEFAULT NULL, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE date_resignation date_resignation DATETIME DEFAULT \'NULL\', CHANGE confirmation_token confirmation_token VARCHAR(40) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
