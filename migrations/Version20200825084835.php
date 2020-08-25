<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200825084835 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_F5299398CF2FC8D0');
        $this->addSql('CREATE TEMPORARY TABLE __temp__order AS SELECT id, terminal_id_id, ticker, magic_number, type, lots, open_price, close_price, sl, tp, swap, profit, status, error_message, created_at, updated_at FROM "order"');
        $this->addSql('DROP TABLE "order"');
        $this->addSql('CREATE TABLE "order" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, terminal_id_id INTEGER NOT NULL, ticker VARCHAR(255) NOT NULL COLLATE BINARY, magic_number VARCHAR(255) NOT NULL COLLATE BINARY, type VARCHAR(255) NOT NULL COLLATE BINARY, lots INTEGER NOT NULL, open_price NUMERIC(10, 2) NOT NULL, close_price NUMERIC(10, 2) DEFAULT NULL, sl NUMERIC(20, 10) DEFAULT NULL, tp NUMERIC(20, 10) DEFAULT NULL, swap NUMERIC(10, 2) DEFAULT NULL, profit NUMERIC(10, 2) DEFAULT NULL, status VARCHAR(255) NOT NULL COLLATE BINARY, error_message VARCHAR(255) DEFAULT NULL COLLATE BINARY, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , CONSTRAINT FK_F5299398CF2FC8D0 FOREIGN KEY (terminal_id_id) REFERENCES terminal (id) NOT DEFERRABLE INITIALLY IMMEDIATE)');
        $this->addSql('INSERT INTO "order" (id, terminal_id_id, ticker, magic_number, type, lots, open_price, close_price, sl, tp, swap, profit, status, error_message, created_at, updated_at) SELECT id, terminal_id_id, ticker, magic_number, type, lots, open_price, close_price, sl, tp, swap, profit, status, error_message, created_at, updated_at FROM __temp__order');
        $this->addSql('DROP TABLE __temp__order');
        $this->addSql('CREATE INDEX IDX_F5299398CF2FC8D0 ON "order" (terminal_id_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__terminal AS SELECT id, code, description, last_sync_at, balance, free_margin, is_main, created_at, updated_at FROM terminal');
        $this->addSql('DROP TABLE terminal');
        $this->addSql('CREATE TABLE terminal (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, code VARCHAR(255) NOT NULL COLLATE BINARY, description CLOB DEFAULT NULL COLLATE BINARY, last_sync_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , is_main BOOLEAN NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , balance NUMERIC(10, 2) DEFAULT NULL, free_margin NUMERIC(10, 2) DEFAULT NULL)');
        $this->addSql('INSERT INTO terminal (id, code, description, last_sync_at, balance, free_margin, is_main, created_at, updated_at) SELECT id, code, description, last_sync_at, balance, free_margin, is_main, created_at, updated_at FROM __temp__terminal');
        $this->addSql('DROP TABLE __temp__terminal');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP INDEX IDX_F5299398CF2FC8D0');
        $this->addSql('CREATE TEMPORARY TABLE __temp__order AS SELECT id, terminal_id_id, ticker, magic_number, type, lots, open_price, close_price, sl, tp, swap, profit, status, error_message, created_at, updated_at FROM "order"');
        $this->addSql('DROP TABLE "order"');
        $this->addSql('CREATE TABLE "order" (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, terminal_id_id INTEGER NOT NULL, ticker VARCHAR(255) NOT NULL, magic_number VARCHAR(255) NOT NULL, type VARCHAR(255) NOT NULL, lots INTEGER NOT NULL, open_price NUMERIC(10, 2) NOT NULL, close_price NUMERIC(10, 2) DEFAULT NULL, sl NUMERIC(20, 10) DEFAULT NULL, tp NUMERIC(20, 10) DEFAULT NULL, swap NUMERIC(10, 2) DEFAULT NULL, profit NUMERIC(10, 2) DEFAULT NULL, status VARCHAR(255) NOT NULL, error_message VARCHAR(255) DEFAULT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        )');
        $this->addSql('INSERT INTO "order" (id, terminal_id_id, ticker, magic_number, type, lots, open_price, close_price, sl, tp, swap, profit, status, error_message, created_at, updated_at) SELECT id, terminal_id_id, ticker, magic_number, type, lots, open_price, close_price, sl, tp, swap, profit, status, error_message, created_at, updated_at FROM __temp__order');
        $this->addSql('DROP TABLE __temp__order');
        $this->addSql('CREATE INDEX IDX_F5299398CF2FC8D0 ON "order" (terminal_id_id)');
        $this->addSql('CREATE TEMPORARY TABLE __temp__terminal AS SELECT id, code, description, last_sync_at, balance, free_margin, is_main, created_at, updated_at FROM terminal');
        $this->addSql('DROP TABLE terminal');
        $this->addSql('CREATE TABLE terminal (id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL, code VARCHAR(255) NOT NULL, description CLOB DEFAULT NULL, last_sync_at DATETIME DEFAULT NULL --(DC2Type:datetime_immutable)
        , is_main BOOLEAN NOT NULL, created_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , updated_at DATETIME NOT NULL --(DC2Type:datetime_immutable)
        , balance NUMERIC(10, 2) NOT NULL, free_margin NUMERIC(10, 2) NOT NULL)');
        $this->addSql('INSERT INTO terminal (id, code, description, last_sync_at, balance, free_margin, is_main, created_at, updated_at) SELECT id, code, description, last_sync_at, balance, free_margin, is_main, created_at, updated_at FROM __temp__terminal');
        $this->addSql('DROP TABLE __temp__terminal');
    }
}
