<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20210828223708 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE author (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, email VARCHAR(255) DEFAULT NULL, ldap_cn VARCHAR(511) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE author_document (id INT AUTO_INCREMENT NOT NULL, author_id INT NOT NULL, document_id INT NOT NULL, type VARCHAR(50) DEFAULT NULL, INDEX IDX_37F9A0C3F675F31B (author_id), INDEX IDX_37F9A0C3C33F7837 (document_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE document (id INT AUTO_INCREMENT NOT NULL, title VARCHAR(255) NOT NULL, docid VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE metadata (id INT AUTO_INCREMENT NOT NULL, version_id INT NOT NULL, name VARCHAR(255) NOT NULL, data JSON NOT NULL, INDEX IDX_4F1434144BBC2705 (version_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE source (id INT AUTO_INCREMENT NOT NULL, version_id INT NOT NULL, type VARCHAR(20) NOT NULL, url LONGTEXT NOT NULL, INDEX IDX_5F8A7F734BBC2705 (version_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE version (id INT AUTO_INCREMENT NOT NULL, document_id INT NOT NULL, number VARCHAR(30) NOT NULL, variant LONGTEXT NOT NULL COMMENT \'(DC2Type:simple_array)\', public TINYINT(1) NOT NULL, date DATETIME NOT NULL, INDEX IDX_BF1CD3C3C33F7837 (document_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE author_document ADD CONSTRAINT FK_37F9A0C3F675F31B FOREIGN KEY (author_id) REFERENCES author (id)');
        $this->addSql('ALTER TABLE author_document ADD CONSTRAINT FK_37F9A0C3C33F7837 FOREIGN KEY (document_id) REFERENCES document (id)');
        $this->addSql('ALTER TABLE metadata ADD CONSTRAINT FK_4F1434144BBC2705 FOREIGN KEY (version_id) REFERENCES version (id)');
        $this->addSql('ALTER TABLE source ADD CONSTRAINT FK_5F8A7F734BBC2705 FOREIGN KEY (version_id) REFERENCES version (id)');
        $this->addSql('ALTER TABLE version ADD CONSTRAINT FK_BF1CD3C3C33F7837 FOREIGN KEY (document_id) REFERENCES document (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE author_document DROP FOREIGN KEY FK_37F9A0C3F675F31B');
        $this->addSql('ALTER TABLE author_document DROP FOREIGN KEY FK_37F9A0C3C33F7837');
        $this->addSql('ALTER TABLE version DROP FOREIGN KEY FK_BF1CD3C3C33F7837');
        $this->addSql('ALTER TABLE metadata DROP FOREIGN KEY FK_4F1434144BBC2705');
        $this->addSql('ALTER TABLE source DROP FOREIGN KEY FK_5F8A7F734BBC2705');
        $this->addSql('DROP TABLE author');
        $this->addSql('DROP TABLE author_document');
        $this->addSql('DROP TABLE document');
        $this->addSql('DROP TABLE metadata');
        $this->addSql('DROP TABLE source');
        $this->addSql('DROP TABLE version');
    }
}
