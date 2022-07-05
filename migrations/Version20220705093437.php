<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220705093437 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE image (id INT AUTO_INCREMENT NOT NULL, effectif_id INT DEFAULT NULL, q1 TINYINT(1) DEFAULT NULL, q2 TINYINT(1) DEFAULT NULL, q3 TINYINT(1) DEFAULT NULL, q4 TINYINT(1) DEFAULT NULL, q5 TINYINT(1) DEFAULT NULL, q6 TINYINT(1) DEFAULT NULL, q7 TINYINT(1) DEFAULT NULL, q8 TINYINT(1) DEFAULT NULL, q9 TINYINT(1) DEFAULT NULL, q10 TINYINT(1) DEFAULT NULL, q11 TINYINT(1) DEFAULT NULL, q12 TINYINT(1) DEFAULT NULL, flag INT DEFAULT NULL, UNIQUE INDEX UNIQ_C53D045F180D113E (effectif_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE image ADD CONSTRAINT FK_C53D045F180D113E FOREIGN KEY (effectif_id) REFERENCES effectif (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE image');
    }
}
