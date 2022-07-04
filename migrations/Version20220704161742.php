<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220704161742 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE experience (id INT AUTO_INCREMENT NOT NULL, q1 TINYINT(1) DEFAULT NULL, q2 TINYINT(1) DEFAULT NULL, q3 TINYINT(1) DEFAULT NULL, q4 TINYINT(1) DEFAULT NULL, q5 TINYINT(1) DEFAULT NULL, q6 TINYINT(1) DEFAULT NULL, q7 TINYINT(1) DEFAULT NULL, q8 TINYINT(1) DEFAULT NULL, q9 TINYINT(1) DEFAULT NULL, q10 TINYINT(1) DEFAULT NULL, q11 TINYINT(1) DEFAULT NULL, q12 TINYINT(1) DEFAULT NULL, q13 TINYINT(1) DEFAULT NULL, flag INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE experience');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
