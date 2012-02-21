<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration,
    Doctrine\DBAL\Schema\Schema;

/**
 * Auto-generated Migration: Please modify to your need!
 */
class Version20120221094827 extends AbstractMigration
{
    public function up(Schema $schema)
    {
        // this up() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("CREATE TABLE sponsors (id INT AUTO_INCREMENT NOT NULL, slug VARCHAR(255) NOT NULL, name VARCHAR(255) NOT NULL, site VARCHAR(255) DEFAULT NULL, logo VARCHAR(255) DEFAULT NULL, about LONGTEXT DEFAULT NULL, created_at DATETIME NOT NULL, updated_at DATETIME NOT NULL, PRIMARY KEY(id)) ENGINE = InnoDB");
        $this->addSql("CREATE TABLE event__events_sponsors (sponsor_id INT NOT NULL, event_id INT NOT NULL, INDEX IDX_3CCEC92812F7FB51 (sponsor_id), INDEX IDX_3CCEC92871F7E88B (event_id), PRIMARY KEY(sponsor_id, event_id)) ENGINE = InnoDB");
        $this->addSql("ALTER TABLE event__events_sponsors ADD CONSTRAINT FK_3CCEC92812F7FB51 FOREIGN KEY (sponsor_id) REFERENCES sponsors(id)");
        $this->addSql("ALTER TABLE event__events_sponsors ADD CONSTRAINT FK_3CCEC92871F7E88B FOREIGN KEY (event_id) REFERENCES event__events(id)");
    }

    public function down(Schema $schema)
    {
        // this down() migration is autogenerated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() != "mysql");
        
        $this->addSql("ALTER TABLE event__events_sponsors DROP FOREIGN KEY FK_3CCEC92812F7FB51");
        $this->addSql("DROP TABLE sponsors");
        $this->addSql("DROP TABLE event__events_sponsors");
    }
}
