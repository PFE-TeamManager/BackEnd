<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200502144309 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE equipe (id INT AUTO_INCREMENT NOT NULL, equipe_name VARCHAR(200) NOT NULL, created_by INT NOT NULL, created_at DATETIME NOT NULL, edited_at DATETIME DEFAULT NULL, edited_by INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE equipe_membre (equipe_id INT NOT NULL, membre_id INT NOT NULL, INDEX IDX_FB839D816D861B89 (equipe_id), INDEX IDX_FB839D816A99F74A (membre_id), PRIMARY KEY(equipe_id, membre_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE labels (id INT AUTO_INCREMENT NOT NULL, label_name VARCHAR(150) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE membre (id INT AUTO_INCREMENT NOT NULL, id_role_id INT NOT NULL, nom VARCHAR(150) NOT NULL, prenom VARCHAR(150) NOT NULL, date_embauchement DATETIME NOT NULL, date_resignation DATETIME DEFAULT NULL, created_at DATETIME NOT NULL, created_by INT NOT NULL, edited_at DATETIME DEFAULT NULL, edited_by DATETIME DEFAULT NULL, UNIQUE INDEX UNIQ_F6B4FB2989E8BDC (id_role_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, role VARCHAR(70) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE equipe_membre ADD CONSTRAINT FK_FB839D816D861B89 FOREIGN KEY (equipe_id) REFERENCES equipe (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE equipe_membre ADD CONSTRAINT FK_FB839D816A99F74A FOREIGN KEY (membre_id) REFERENCES membre (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE membre ADD CONSTRAINT FK_F6B4FB2989E8BDC FOREIGN KEY (id_role_id) REFERENCES role (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE equipe_membre DROP FOREIGN KEY FK_FB839D816D861B89');
        $this->addSql('ALTER TABLE equipe_membre DROP FOREIGN KEY FK_FB839D816A99F74A');
        $this->addSql('ALTER TABLE membre DROP FOREIGN KEY FK_F6B4FB2989E8BDC');
        $this->addSql('DROP TABLE equipe');
        $this->addSql('DROP TABLE equipe_membre');
        $this->addSql('DROP TABLE labels');
        $this->addSql('DROP TABLE membre');
        $this->addSql('DROP TABLE role');
    }
}
