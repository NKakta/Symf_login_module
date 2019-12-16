<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191216132641 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE uzsakymas_product (uzsakymas_id INT NOT NULL, product_id INT NOT NULL, INDEX IDX_8A61ABBD8914E9BC (uzsakymas_id), INDEX IDX_8A61ABBD4584665A (product_id), PRIMARY KEY(uzsakymas_id, product_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE uzsakymas_product ADD CONSTRAINT FK_8A61ABBD8914E9BC FOREIGN KEY (uzsakymas_id) REFERENCES uzsakymas (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE uzsakymas_product ADD CONSTRAINT FK_8A61ABBD4584665A FOREIGN KEY (product_id) REFERENCES products (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('DROP TABLE uzsakymas_product');
    }
}
