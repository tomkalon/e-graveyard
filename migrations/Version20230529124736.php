<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230529124736 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE grave ADD graveyard_id INT NOT NULL, ADD position_x VARCHAR(255) DEFAULT NULL, ADD position_y VARCHAR(255) DEFAULT NULL');
        $this->addSql('ALTER TABLE grave ADD CONSTRAINT FK_21AEDEE7B0C855CA FOREIGN KEY (graveyard_id) REFERENCES graveyard (id)');
        $this->addSql('CREATE INDEX IDX_21AEDEE7B0C855CA ON grave (graveyard_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE grave DROP FOREIGN KEY FK_21AEDEE7B0C855CA');
        $this->addSql('DROP INDEX IDX_21AEDEE7B0C855CA ON grave');
        $this->addSql('ALTER TABLE grave DROP graveyard_id, DROP position_x, DROP position_y');
    }
}
