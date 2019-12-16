<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191216160748 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE uzsakymas ADD user_orderer_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE uzsakymas ADD CONSTRAINT FK_72B5827C7875F7F5 FOREIGN KEY (user_orderer_id) REFERENCES users (id)');
        $this->addSql('CREATE INDEX IDX_72B5827C7875F7F5 ON uzsakymas (user_orderer_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE uzsakymas DROP FOREIGN KEY FK_72B5827C7875F7F5');
        $this->addSql('DROP INDEX IDX_72B5827C7875F7F5 ON uzsakymas');
        $this->addSql('ALTER TABLE uzsakymas DROP user_orderer_id');
    }
}
