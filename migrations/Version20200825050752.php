<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200825050752 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE "order" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, terminal_id_id INTEGER NOT NULL, ticker VARCHAR(255) NOT NULL, magic_number VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, lots INTEGER NOT NULL, open_price NUMERIC(10, 2) NOT NULL, close_price NUMERIC(10, 2) DEFAULT NULL, sl NUMERIC(20, 10) DEFAULT NULL, tp NUMERIC(20, 10) DEFAULT NULL, swap NUMERIC(10, 2) DEFAULT NULL, profit NUMERIC(10, 2) DEFAULT NULL, status VARCHAR(255) NOT NULL, error_message VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('CREATE INDEX IDX_F5299398CF2FC8D0 ON "order" (terminal_id_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE "order"');
    }
}
