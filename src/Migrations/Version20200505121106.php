<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200505121106 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE membre DROP FOREIGN KEY FK_F6B4FB2989E8BDC');
        $this->addSql('DROP TABLE role');
        $this->addSql('ALTER TABLE equipe CHANGE edited_at edited_at DATETIME DEFAULT NULL, CHANGE edited_by edited_by INT DEFAULT NULL');
        $this->addSql('DROP INDEX UNIQ_F6B4FB2989E8BDC ON membre');
        $this->addSql('ALTER TABLE membre ADD roles JSON NOT NULL, DROP id_role_id, CHANGE date_resignation date_resignation DATETIME DEFAULT NULL, CHANGE edited_at edited_at DATETIME DEFAULT NULL, CHANGE edited_by edited_by DATETIME DEFAULT NULL, CHANGE phone phone VARCHAR(14) DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, role VARCHAR(70) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE equipe CHANGE edited_at edited_at DATETIME DEFAULT \'NULL\', CHANGE edited_by edited_by INT DEFAULT NULL');
        $this->addSql('ALTER TABLE membre ADD id_role_id INT NOT NULL, DROP roles, CHANGE date_resignation date_resignation DATETIME DEFAULT \'NULL\', CHANGE edited_at edited_at DATETIME DEFAULT \'NULL\', CHANGE edited_by edited_by DATETIME DEFAULT \'NULL\', CHANGE phone phone VARCHAR(14) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE membre ADD CONSTRAINT FK_F6B4FB2989E8BDC FOREIGN KEY (id_role_id) REFERENCES role (id)');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_F6B4FB2989E8BDC ON membre (id_role_id)');
    }
}
