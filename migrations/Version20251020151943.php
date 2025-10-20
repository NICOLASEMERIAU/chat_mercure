<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251020151943 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE conversation ADD sender_id INT NOT NULL');
        $this->addSql('ALTER TABLE conversation ADD recipient_id INT NOT NULL');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E9F624B39D FOREIGN KEY (sender_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE conversation ADD CONSTRAINT FK_8A8E26E9E92F8F78 FOREIGN KEY (recipient_id) REFERENCES "user" (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_8A8E26E9F624B39D ON conversation (sender_id)');
        $this->addSql('CREATE INDEX IDX_8A8E26E9E92F8F78 ON conversation (recipient_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE SCHEMA public');
        $this->addSql('ALTER TABLE conversation DROP CONSTRAINT FK_8A8E26E9F624B39D');
        $this->addSql('ALTER TABLE conversation DROP CONSTRAINT FK_8A8E26E9E92F8F78');
        $this->addSql('DROP INDEX IDX_8A8E26E9F624B39D');
        $this->addSql('DROP INDEX IDX_8A8E26E9E92F8F78');
        $this->addSql('ALTER TABLE conversation DROP sender_id');
        $this->addSql('ALTER TABLE conversation DROP recipient_id');
    }
}
