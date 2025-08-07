<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250805132217 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE size (id INT AUTO_INCREMENT NOT NULL, size VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE sweat_variant (id INT AUTO_INCREMENT NOT NULL, sweat_id INT NOT NULL, size_id INT NOT NULL, stock INT NOT NULL, INDEX IDX_A5878E7EF044C42 (sweat_id), INDEX IDX_A5878E7498DA827 (size_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE sweat_variant ADD CONSTRAINT FK_A5878E7EF044C42 FOREIGN KEY (sweat_id) REFERENCES sweat (id)');
        $this->addSql('ALTER TABLE sweat_variant ADD CONSTRAINT FK_A5878E7498DA827 FOREIGN KEY (size_id) REFERENCES size (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE sweat_variant DROP FOREIGN KEY FK_A5878E7EF044C42');
        $this->addSql('ALTER TABLE sweat_variant DROP FOREIGN KEY FK_A5878E7498DA827');
        $this->addSql('DROP TABLE size');
        $this->addSql('DROP TABLE sweat_variant');
    }
}
