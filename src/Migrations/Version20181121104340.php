<?php declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20181121104340 extends AbstractMigration
{
    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('CREATE TABLE role (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(190) DEFAULT NULL, UNIQUE INDEX UNIQ_57698A6A5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE skill (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(190) NOT NULL, UNIQUE INDEX UNIQ_5E3DE4775E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(190) DEFAULT NULL, UNIQUE INDEX UNIQ_7B00651C5E237E06 (name), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, status INT DEFAULT NULL, username VARCHAR(190) NOT NULL, password VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, surname VARCHAR(255) NOT NULL, birthday DATETIME DEFAULT NULL, country VARCHAR(10) DEFAULT NULL, link VARCHAR(255) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), INDEX IDX_8D93D6497B00651C (status), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE has (user INT NOT NULL, skill INT NOT NULL, INDEX IDX_C6F39EA8D93D649 (user), INDEX IDX_C6F39EA5E3DE477 (skill), PRIMARY KEY(user, skill)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('CREATE TABLE owns (user INT NOT NULL, role INT NOT NULL, INDEX IDX_21CD20338D93D649 (user), INDEX IDX_21CD203357698A6A (role), PRIMARY KEY(user, role)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE user ADD CONSTRAINT FK_8D93D6497B00651C FOREIGN KEY (status) REFERENCES status (id) ON DELETE SET NULL');
        $this->addSql('ALTER TABLE has ADD CONSTRAINT FK_C6F39EA8D93D649 FOREIGN KEY (user) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE has ADD CONSTRAINT FK_C6F39EA5E3DE477 FOREIGN KEY (skill) REFERENCES skill (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE owns ADD CONSTRAINT FK_21CD20338D93D649 FOREIGN KEY (user) REFERENCES user (id)');
        $this->addSql('ALTER TABLE owns ADD CONSTRAINT FK_21CD203357698A6A FOREIGN KEY (role) REFERENCES role (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE owns DROP FOREIGN KEY FK_21CD203357698A6A');
        $this->addSql('ALTER TABLE has DROP FOREIGN KEY FK_C6F39EA5E3DE477');
        $this->addSql('ALTER TABLE user DROP FOREIGN KEY FK_8D93D6497B00651C');
        $this->addSql('ALTER TABLE has DROP FOREIGN KEY FK_C6F39EA8D93D649');
        $this->addSql('ALTER TABLE owns DROP FOREIGN KEY FK_21CD20338D93D649');
        $this->addSql('DROP TABLE role');
        $this->addSql('DROP TABLE skill');
        $this->addSql('DROP TABLE status');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE has');
        $this->addSql('DROP TABLE owns');
    }
}
