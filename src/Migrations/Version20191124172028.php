<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191124172028 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE orders CHANGE name name VARCHAR(255) DEFAULT NULL, CHANGE sold sold TINYINT(1) DEFAULT NULL');
        $this->addSql('CREATE UNIQUE INDEX UNIQ_E52FFDEEC814A749 ON orders (payer_email)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP INDEX UNIQ_E52FFDEEC814A749 ON orders');
        $this->addSql('ALTER TABLE orders CHANGE name name VARCHAR(255) DEFAULT \'\' NOT NULL COLLATE utf8mb4_unicode_ci, CHANGE sold sold TINYINT(1) NOT NULL');
    }
}
