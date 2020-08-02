<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200802113643 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE bug (id INT AUTO_INCREMENT NOT NULL, id_task_id INT NOT NULL, created_by_id INT NOT NULL, user_id INT DEFAULT NULL, id_project_id INT DEFAULT NULL, bug_title VARCHAR(255) NOT NULL, bug_description LONGTEXT DEFAULT NULL, enabled TINYINT(1) NOT NULL, to_do TINYINT(1) DEFAULT NULL, to_do_date DATETIME DEFAULT NULL, doing TINYINT(1) DEFAULT NULL, datedoing DATETIME DEFAULT NULL, done TINYINT(1) DEFAULT NULL, datedone DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_358CBF14532BA8F6 (id_task_id), INDEX IDX_358CBF14B03A8386 (created_by_id), INDEX IDX_358CBF14A76ED395 (user_id), INDEX IDX_358CBF14B3E79F4B (id_project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comment (id INT AUTO_INCREMENT NOT NULL, created_by_id INT NOT NULL, task_id INT DEFAULT NULL, bug_id INT DEFAULT NULL, content LONGTEXT NOT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_9474526CB03A8386 (created_by_id), INDEX IDX_9474526C8DB60186 (task_id), INDEX IDX_9474526CFA3DB3D5 (bug_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE labels (id INT AUTO_INCREMENT NOT NULL, created_by_id INT NOT NULL, label_name VARCHAR(150) NOT NULL, enabled TINYINT(1) NOT NULL, color VARCHAR(25) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_B5D10211921BEBCA (label_name), INDEX IDX_B5D10211B03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, created_by_id INT NOT NULL, project_name VARCHAR(255) NOT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_2FB3D0EE67B2B61E (project_name), INDEX IDX_2FB3D0EEB03A8386 (created_by_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task (id INT AUTO_INCREMENT NOT NULL, id_project_id INT NOT NULL, created_by_id INT NOT NULL, user_id INT DEFAULT NULL, task_title VARCHAR(255) NOT NULL, task_description LONGTEXT DEFAULT NULL, enabled TINYINT(1) NOT NULL, to_do TINYINT(1) DEFAULT NULL, to_do_date DATETIME DEFAULT NULL, doing TINYINT(1) DEFAULT NULL, datedoing DATETIME DEFAULT NULL, done TINYINT(1) DEFAULT NULL, datedone DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, INDEX IDX_527EDB25B3E79F4B (id_project_id), INDEX IDX_527EDB25B03A8386 (created_by_id), INDEX IDX_527EDB25A76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE task_labels (task_id INT NOT NULL, labels_id INT NOT NULL, INDEX IDX_8E7886C28DB60186 (task_id), INDEX IDX_8E7886C2B8478C02 (labels_id), PRIMARY KEY(task_id, labels_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE team (id INT AUTO_INCREMENT NOT NULL, created_by_id INT NOT NULL, project_id INT DEFAULT NULL, team_name VARCHAR(200) NOT NULL, enabled TINYINT(1) NOT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_C4E0A61F8FC28A7D (team_name), INDEX IDX_C4E0A61FB03A8386 (created_by_id), INDEX IDX_C4E0A61F166D1F9C (project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, teams_id INT DEFAULT NULL, username VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, phone VARCHAR(14) DEFAULT NULL, date_embauchement DATETIME DEFAULT NULL, password_change_date INT DEFAULT NULL, roles JSON NOT NULL, date_resignation DATETIME DEFAULT NULL, enabled TINYINT(1) NOT NULL, confirmation_token VARCHAR(40) DEFAULT NULL, description LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649444F97DD (phone), INDEX IDX_8D93D649D6365F12 (teams_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE bug ADD CONSTRAINT FK_358CBF14532BA8F6 FOREIGN KEY (id_task_id) REFERENCES task (id)');
        $this->addSql('ALTER TABLE bug ADD CONSTRAINT FK_358CBF14B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE bug ADD CONSTRAINT FK_358CBF14A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE bug ADD CONSTRAINT FK_358CBF14B3E79F4B FOREIGN KEY (id_project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526C8DB60186 FOREIGN KEY (task_id) REFERENCES task (id)');
        $this->addSql('ALTER TABLE comment ADD CONSTRAINT FK_9474526CFA3DB3D5 FOREIGN KEY (bug_id) REFERENCES bug (id)');
        $this->addSql('ALTER TABLE labels ADD CONSTRAINT FK_B5D10211B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE project ADD CONSTRAINT FK_2FB3D0EEB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25B3E79F4B FOREIGN KEY (id_project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25B03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE task ADD CONSTRAINT FK_527EDB25A76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE task_labels ADD CONSTRAINT FK_8E7886C28DB60186 FOREIGN KEY (task_id) REFERENCES task (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE task_labels ADD CONSTRAINT FK_8E7886C2B8478C02 FOREIGN KEY (labels_id) REFERENCES labels (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61FB03A8386 FOREIGN KEY (created_by_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE team ADD CONSTRAINT FK_C4E0A61F166D1F9C FOREIGN KEY (project_id) REFERENCES project (id)');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D649D6365F12 FOREIGN KEY (teams_id) REFERENCES team (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CFA3DB3D5');
        $this->addSql('ALTER TABLE task_labels DROP FOREIGN KEY FK_8E7886C2B8478C02');
        $this->addSql('ALTER TABLE bug DROP FOREIGN KEY FK_358CBF14B3E79F4B');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25B3E79F4B');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61F166D1F9C');
        $this->addSql('ALTER TABLE bug DROP FOREIGN KEY FK_358CBF14532BA8F6');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526C8DB60186');
        $this->addSql('ALTER TABLE task_labels DROP FOREIGN KEY FK_8E7886C28DB60186');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D649D6365F12');
        $this->addSql('ALTER TABLE bug DROP FOREIGN KEY FK_358CBF14B03A8386');
        $this->addSql('ALTER TABLE bug DROP FOREIGN KEY FK_358CBF14A76ED395');
        $this->addSql('ALTER TABLE comment DROP FOREIGN KEY FK_9474526CB03A8386');
        $this->addSql('ALTER TABLE labels DROP FOREIGN KEY FK_B5D10211B03A8386');
        $this->addSql('ALTER TABLE project DROP FOREIGN KEY FK_2FB3D0EEB03A8386');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25B03A8386');
        $this->addSql('ALTER TABLE task DROP FOREIGN KEY FK_527EDB25A76ED395');
        $this->addSql('ALTER TABLE team DROP FOREIGN KEY FK_C4E0A61FB03A8386');
        $this->addSql('DROP TABLE bug');
        $this->addSql('DROP TABLE comment');
        $this->addSql('DROP TABLE labels');
        $this->addSql('DROP TABLE project');
        $this->addSql('DROP TABLE task');
        $this->addSql('DROP TABLE task_labels');
        $this->addSql('DROP TABLE team');
        $this->addSql('DROP TABLE user');
    }
}
