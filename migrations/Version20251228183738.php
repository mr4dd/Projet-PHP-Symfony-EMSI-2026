<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20251228183738 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE course (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, module_id_id INT NOT NULL, INDEX IDX_169E6FB97648EE39 (module_id_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE module (id INT AUTO_INCREMENT NOT NULL, nom LONGTEXT NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE note (id INT AUTO_INCREMENT NOT NULL, note DOUBLE PRECISION DEFAULT NULL, etud_id_id INT DEFAULT NULL, course_id_id INT NOT NULL, INDEX IDX_CFBDFA14A6FC11F1 (etud_id_id), INDEX IDX_CFBDFA1496EF99BF (course_id_id), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE student (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, matricule VARCHAR(12) NOT NULL, PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('CREATE TABLE messenger_messages (id BIGINT AUTO_INCREMENT NOT NULL, body LONGTEXT NOT NULL, headers LONGTEXT NOT NULL, queue_name VARCHAR(190) NOT NULL, created_at DATETIME NOT NULL, available_at DATETIME NOT NULL, delivered_at DATETIME DEFAULT NULL, INDEX IDX_75EA56E0FB7336F0 (queue_name), INDEX IDX_75EA56E0E3BD61CE (available_at), INDEX IDX_75EA56E016BA31DB (delivered_at), PRIMARY KEY (id)) DEFAULT CHARACTER SET utf8mb4');
        $this->addSql('ALTER TABLE course ADD CONSTRAINT FK_169E6FB97648EE39 FOREIGN KEY (module_id_id) REFERENCES module (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA14A6FC11F1 FOREIGN KEY (etud_id_id) REFERENCES student (id)');
        $this->addSql('ALTER TABLE note ADD CONSTRAINT FK_CFBDFA1496EF99BF FOREIGN KEY (course_id_id) REFERENCES course (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE course DROP FOREIGN KEY FK_169E6FB97648EE39');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA14A6FC11F1');
        $this->addSql('ALTER TABLE note DROP FOREIGN KEY FK_CFBDFA1496EF99BF');
        $this->addSql('DROP TABLE course');
        $this->addSql('DROP TABLE module');
        $this->addSql('DROP TABLE note');
        $this->addSql('DROP TABLE student');
        $this->addSql('DROP TABLE messenger_messages');
    }
}
