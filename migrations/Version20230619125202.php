<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230619125202 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE person ADD edited_by_id INT DEFAULT NULL, DROP edited_by');
        $this->addSql('ALTER TABLE person ADD CONSTRAINT FK_34DCD176DD7B2EBC FOREIGN KEY (edited_by_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_34DCD176DD7B2EBC ON person (edited_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE person DROP FOREIGN KEY FK_34DCD176DD7B2EBC');
        $this->addSql('DROP INDEX IDX_34DCD176DD7B2EBC ON person');
        $this->addSql('ALTER TABLE person ADD edited_by VARCHAR(255) DEFAULT NULL, DROP edited_by_id');
    }
}
