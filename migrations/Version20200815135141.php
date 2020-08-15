<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200815135141 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE balance_transaction (id INT AUTO_INCREMENT NOT NULL, player_id INT DEFAULT NULL, amount DOUBLE PRECISION NOT NULL, amount_before DOUBLE PRECISION NOT NULL, INDEX IDX_A70FE73399E6F5DF (player_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bet (id INT AUTO_INCREMENT NOT NULL, stake_amount DOUBLE PRECISION NOT NULL, created_at DATETIME NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE bet_selections (id INT AUTO_INCREMENT NOT NULL, bet_id INT DEFAULT NULL, selection_id INT NOT NULL, odds DOUBLE PRECISION NOT NULL, INDEX IDX_837B8D61D871DC26 (bet_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE player (id INT AUTO_INCREMENT NOT NULL, balance DOUBLE PRECISION NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE balance_transaction ADD CONSTRAINT FK_A70FE73399E6F5DF FOREIGN KEY (player_id) REFERENCES player (id)');
        $this->addSql('ALTER TABLE bet_selections ADD CONSTRAINT FK_837B8D61D871DC26 FOREIGN KEY (bet_id) REFERENCES bet (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE bet_selections DROP FOREIGN KEY FK_837B8D61D871DC26');
        $this->addSql('ALTER TABLE balance_transaction DROP FOREIGN KEY FK_A70FE73399E6F5DF');
        $this->addSql('DROP TABLE balance_transaction');
        $this->addSql('DROP TABLE bet');
        $this->addSql('DROP TABLE bet_selections');
        $this->addSql('DROP TABLE player');
    }
}
