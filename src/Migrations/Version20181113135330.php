<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181113135330 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE has DROP FOREIGN KEY FK_C6F39EA5E3DE477');
        $this->addSql('ALTER TABLE has ADD CONSTRAINT FK_C6F39EA5E3DE477 FOREIGN KEY (skill) REFERENCES skill (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE has DROP FOREIGN KEY FK_C6F39EA5E3DE477');
        $this->addSql('ALTER TABLE has ADD CONSTRAINT FK_C6F39EA5E3DE477 FOREIGN KEY (skill) REFERENCES skill (id) ON UPDATE CASCADE ON DELETE CASCADE');
    }
}
