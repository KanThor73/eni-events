<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20230919164116 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE filter_event (id INT AUTO_INCREMENT NOT NULL, campus VARCHAR(100) DEFAULT NULL, event_name VARCHAR(255) DEFAULT NULL, begin_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', end_date DATETIME DEFAULT NULL COMMENT \'(DC2Type:datetime_immutable)\', is_organizer TINYINT(1) DEFAULT NULL, is_member TINYINT(1) DEFAULT NULL, is_not_member TINYINT(1) NOT NULL, passed TINYINT(1) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('DROP TABLE filter_event');
    }
}
