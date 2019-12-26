<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20191226212049 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE accounts ADD account_id VARCHAR(255) DEFAULT NULL, ADD summoner VARCHAR(255) DEFAULT NULL, ADD level INT DEFAULT NULL, ADD email_status VARCHAR(255) DEFAULT NULL, ADD rp_balance NUMERIC(19, 2) DEFAULT NULL, ADD ip_balance NUMERIC(19, 2) DEFAULT NULL, ADD rune_pages INT DEFAULT NULL, ADD refunds INT DEFAULT NULL, ADD previous_season_rank VARCHAR(255) DEFAULT NULL, ADD solo_q_rank VARCHAR(255) DEFAULT NULL, ADD last_play VARCHAR(255) DEFAULT NULL, ADD checked_time VARCHAR(255) DEFAULT NULL, ADD runes INT DEFAULT NULL, ADD state INT DEFAULT NULL, ADD region INT DEFAULT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE accounts DROP account_id, DROP summoner, DROP level, DROP email_status, DROP rp_balance, DROP ip_balance, DROP rune_pages, DROP refunds, DROP previous_season_rank, DROP solo_q_rank, DROP last_play, DROP checked_time, DROP runes, DROP state, DROP region');
    }
}
