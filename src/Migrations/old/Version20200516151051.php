<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200516151051 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE equipe CHANGE edited_at edited_at DATETIME DEFAULT NULL, CHANGE edited_by edited_by INT DEFAULT NULL');
        $this->addSql('ALTER TABLE membre ADD username VARCHAR(255) NOT NULL, DROP nom, DROP prenom, CHANGE date_embauchement date_embauchement DATETIME DEFAULT NULL, CHANGE date_resignation date_resignation DATETIME DEFAULT NULL, CHANGE created_at created_at DATETIME DEFAULT NULL, CHANGE edited_at edited_at DATETIME DEFAULT NULL, CHANGE phone phone VARCHAR(14) DEFAULT NULL, CHANGE roles roles JSON NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE equipe CHANGE edited_at edited_at DATETIME DEFAULT \'NULL\', CHANGE edited_by edited_by INT DEFAULT NULL');
        $this->addSql('ALTER TABLE membre ADD nom VARCHAR(150) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, ADD prenom VARCHAR(150) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, DROP username, CHANGE phone phone VARCHAR(14) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, CHANGE roles roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, CHANGE date_embauchement date_embauchement DATETIME DEFAULT \'NULL\', CHANGE date_resignation date_resignation DATETIME DEFAULT \'NULL\', CHANGE created_at created_at DATETIME DEFAULT \'NULL\', CHANGE edited_at edited_at DATETIME DEFAULT \'NULL\'');
    }
}
