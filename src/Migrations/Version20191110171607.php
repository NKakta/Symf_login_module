<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191110171607 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE contests (id INT AUTO_INCREMENT NOT NULL, topic VARCHAR(191) NOT NULL, date_from DATETIME NOT NULL, date_to DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pictures (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(191) NOT NULL, description VARCHAR(255) NOT NULL, url VARCHAR(255) NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE picture_contest (picture_id INT NOT NULL, contest_id INT NOT NULL, INDEX IDX_1C8FA867EE45BDBF (picture_id), INDEX IDX_1C8FA8671CD0F0DE (contest_id), PRIMARY KEY(picture_id, contest_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE comments (id INT AUTO_INCREMENT NOT NULL, picture_id INT DEFAULT NULL, content VARCHAR(191) NOT NULL, created_at DATETIME NOT NULL, INDEX IDX_5F9E962AEE45BDBF (picture_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE picture_contest ADD CONSTRAINT FK_1C8FA867EE45BDBF FOREIGN KEY (picture_id) REFERENCES pictures (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE picture_contest ADD CONSTRAINT FK_1C8FA8671CD0F0DE FOREIGN KEY (contest_id) REFERENCES contests (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE comments ADD CONSTRAINT FK_5F9E962AEE45BDBF FOREIGN KEY (picture_id) REFERENCES pictures (id)');
        $this->addSql('ALTER TABLE users CHANGE username username VARCHAR(191) NOT NULL, CHANGE email email VARCHAR(191) NOT NULL, CHANGE password password VARCHAR(191) NOT NULL, CHANGE roles roles LONGTEXT NOT NULL COMMENT \'(DC2Type:array)\'');
        $this->addSql('ALTER TABLE users RENAME INDEX uniq_8d93d649f85e0677 TO UNIQ_1483A5E9F85E0677');
        $this->addSql('ALTER TABLE users RENAME INDEX uniq_8d93d64935c246d5 TO UNIQ_1483A5E935C246D5');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE picture_contest DROP FOREIGN KEY FK_1C8FA8671CD0F0DE');
        $this->addSql('ALTER TABLE picture_contest DROP FOREIGN KEY FK_1C8FA867EE45BDBF');
        $this->addSql('ALTER TABLE comments DROP FOREIGN KEY FK_5F9E962AEE45BDBF');
        $this->addSql('DROP TABLE contests');
        $this->addSql('DROP TABLE pictures');
        $this->addSql('DROP TABLE picture_contest');
        $this->addSql('DROP TABLE comments');
        $this->addSql('ALTER TABLE users CHANGE username username VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE email email VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE password password VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE roles roles JSON NOT NULL');
        $this->addSql('ALTER TABLE users RENAME INDEX uniq_1483a5e935c246d5 TO UNIQ_8D93D64935C246D5');
        $this->addSql('ALTER TABLE users RENAME INDEX uniq_1483a5e9f85e0677 TO UNIQ_8D93D649F85E0677');
    }
}
