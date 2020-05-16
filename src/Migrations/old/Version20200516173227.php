<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200516173227 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE equipe_membre DROP FOREIGN KEY FK_FB839D816A99F74A');
        $this->addSql('CREATE TABLE equipe_member (equipe_id INT NOT NULL, member_id INT NOT NULL, INDEX IDX_7DD39CD06D861B89 (equipe_id), INDEX IDX_7DD39CD07597D3FE (member_id), PRIMARY KEY(equipe_id, member_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE member (id INT AUTO_INCREMENT NOT NULL, username VARCHAR(255) NOT NULL, password VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, phone VARCHAR(14) DEFAULT NULL, roles JSON NOT NULL, date_embauchement DATETIME DEFAULT NULL, date_resignation DATETIME DEFAULT NULL, created_at DATETIME DEFAULT NULL, edited_at DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_70E4FA78E7927C74 (email), UNIQUE INDEX UNIQ_70E4FA78444F97DD (phone), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE equipe_member ADD CONSTRAINT FK_7DD39CD06D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE equipe_member ADD CONSTRAINT FK_7DD39CD07597D3FE FOREIGN KEY (member_id) REFERENCES member (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE equipe_membre');
        $this->addSql('DROP TABLE membre');
        $this->addSql('ALTER TABLE equipe CHANGE edited_at edited_at DATETIME DEFAULT NULL, CHANGE edited_by edited_by INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE equipe_member DROP FOREIGN KEY FK_7DD39CD07597D3FE');
        $this->addSql('CREATE TABLE equipe_membre (equipe_id INT NOT NULL, membre_id INT NOT NULL, INDEX IDX_FB839D816D861B89 (equipe_id), INDEX IDX_FB839D816A99F74A (membre_id), PRIMARY KEY(equipe_id, membre_id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('CREATE TABLE membre (id INT AUTO_INCREMENT NOT NULL, date_embauchement DATETIME DEFAULT \'NULL\', date_resignation DATETIME DEFAULT \'NULL\', created_at DATETIME DEFAULT \'NULL\', edited_at DATETIME DEFAULT \'NULL\', email VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, phone VARCHAR(14) CHARACTER SET utf8mb4 DEFAULT \'NULL\' COLLATE `utf8mb4_unicode_ci`, password VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, roles LONGTEXT CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_bin`, username VARCHAR(255) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`, UNIQUE INDEX UNIQ_F6B4FB29E7927C74 (email), UNIQUE INDEX UNIQ_F6B4FB29444F97DD (phone), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE `utf8_unicode_ci` ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE equipe_membre ADD CONSTRAINT FK_FB839D816A99F74A FOREIGN KEY (membre_id) REFERENCES membre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE equipe_membre ADD CONSTRAINT FK_FB839D816D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id) ON DELETE CASCADE');
        $this->addSql('DROP TABLE equipe_member');
        $this->addSql('DROP TABLE member');
        $this->addSql('ALTER TABLE equipe CHANGE edited_at edited_at DATETIME DEFAULT \'NULL\', CHANGE edited_by edited_by INT DEFAULT NULL');
    }
}
