<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230619124933 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE grave ADD edited_by_id INT DEFAULT NULL, DROP edited_by');
        $this->addSql('ALTER TABLE grave ADD CONSTRAINT FK_21AEDEE7DD7B2EBC FOREIGN KEY (edited_by_id) REFERENCES `user` (id)');
        $this->addSql('CREATE INDEX IDX_21AEDEE7DD7B2EBC ON grave (edited_by_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE grave DROP FOREIGN KEY FK_21AEDEE7DD7B2EBC');
        $this->addSql('DROP INDEX IDX_21AEDEE7DD7B2EBC ON grave');
        $this->addSql('ALTER TABLE grave ADD edited_by VARCHAR(255) DEFAULT NULL, DROP edited_by_id');
    }
}
