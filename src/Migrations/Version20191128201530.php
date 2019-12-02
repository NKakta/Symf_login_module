<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191128201530 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE accounts ADD order_id CHAR(36) DEFAULT NULL COMMENT \'(DC2Type:guid)\'');
        $this->addSql('ALTER TABLE accounts ADD CONSTRAINT FK_CAC89EAC8D9F6D38 FOREIGN KEY (order_id) REFERENCES orders (id)');
        $this->addSql('CREATE INDEX IDX_CAC89EAC8D9F6D38 ON accounts (order_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE accounts DROP FOREIGN KEY FK_CAC89EAC8D9F6D38');
        $this->addSql('DROP INDEX IDX_CAC89EAC8D9F6D38 ON accounts');
        $this->addSql('ALTER TABLE accounts DROP order_id');
    }
}
