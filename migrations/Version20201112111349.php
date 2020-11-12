<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201112111349 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE basic_user CHANGE id id INT NOT NULL, CHANGE username name VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE basic_user ADD CONSTRAINT FK_D9859A22BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE blogger CHANGE id id INT NOT NULL, CHANGE username name VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE blogger ADD CONSTRAINT FK_A8F930E2BF396750 FOREIGN KEY (id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user ADD type VARCHAR(255) NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE basic_user DROP FOREIGN KEY FK_D9859A22BF396750');
        $this->addSql('ALTER TABLE basic_user CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE name username VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE blogger DROP FOREIGN KEY FK_A8F930E2BF396750');
        $this->addSql('ALTER TABLE blogger CHANGE id id INT AUTO_INCREMENT NOT NULL, CHANGE name username VARCHAR(20) CHARACTER SET utf8mb4 NOT NULL COLLATE `utf8mb4_unicode_ci`');
        $this->addSql('ALTER TABLE user DROP type');
    }
}
