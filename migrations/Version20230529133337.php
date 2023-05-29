<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230529133337 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE person ADD grave_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176E439654A FOREIGN KEY (grave_id) REFERENCES grave (id)');
        $this->addSql('CREATE INDEX IDX_34DCD176E439654A ON person (grave_id)');
        $this->addSql('ALTER TABLE user ADD username VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD176E439654A');
        $this->addSql('DROP INDEX IDX_34DCD176E439654A ON person');
        $this->addSql('ALTER TABLE person DROP grave_id');
        $this->addSql('ALTER TABLE `user` DROP username');
    }
}
