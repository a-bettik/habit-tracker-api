<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20260405070749 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE habit (id UUID NOT NULL, label VARCHAR(255) NOT NULL, period VARCHAR(8) NOT NULL, target_count INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('COMMENT ON COLUMN habit.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN habit.created_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('COMMENT ON COLUMN habit.updated_at IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('CREATE TABLE habit_log (id UUID NOT NULL, habit_id UUID NOT NULL, date TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE INDEX IDX_C1637C45E7AEB3B2 ON habit_log (habit_id)');
        $this->addSql('COMMENT ON COLUMN habit_log.id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN habit_log.habit_id IS \'(DC2Type:uuid)\'');
        $this->addSql('COMMENT ON COLUMN habit_log.date IS \'(DC2Type:datetime_immutable)\'');
        $this->addSql('ALTER TABLE habit_log ADD CONSTRAINT FK_C1637C45E7AEB3B2 FOREIGN KEY (habit_id) REFERENCES habit (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE habit_log DROP CONSTRAINT FK_C1637C45E7AEB3B2');
        $this->addSql('DROP TABLE habit');
        $this->addSql('DROP TABLE habit_log');
    }
}
