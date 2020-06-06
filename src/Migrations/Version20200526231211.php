<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200526231211 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE task (id INT AUTO_INCREMENT NOT NULL, id_project_id INT DEFAULT NULL, created_by_id INT NOT NULL, task_title VARCHAR(255) NOT NULL, task_description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_527EDB25B3E79F4B (id_project_id), INDEX IDX_527EDB25B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25B3E79F4B FOREIGN KEY (id_project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE image CHANGE url url VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE team CHANGE project_id project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE phone phone VARCHAR(14) DEFAULT NULL, CHANGE roles roles JSON NOT NULL, CHANGE date_embauchement date_embauchement DATETIME DEFAULT NULL, CHANGE date_resignation date_resignation DATETIME DEFAULT NULL, CHANGE password_change_date password_change_date INT DEFAULT NULL, CHANGE confirmation_token confirmation_token VARCHAR(40) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE task');
        $this->addSql('ALTER TABLE image CHANGE url url VARCHAR(255) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE team CHANGE project_id project_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE user CHANGE phone phone VARCHAR(14) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE date_embauchement date_embauchement DATETIME DEFAULT \'NULL\', CHANGE password_change_date password_change_date INT DEFAULT NULL, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE date_resignation date_resignation DATETIME DEFAULT \'NULL\', CHANGE confirmation_token confirmation_token VARCHAR(40) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
    }
}
