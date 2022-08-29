<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220829151830 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE employee (id INT AUTO_INCREMENT NOT NULL, id_job_id INT DEFAULT NULL, first_name VARCHAR(255) NOT NULL, last_name VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, daily_cost DOUBLE PRECISION NOT NULL, hiring_date DATE NOT NULL, INDEX IDX_5D9F75A12DD7FB44 (id_job_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE job (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE production_times (id INT AUTO_INCREMENT NOT NULL, id_employee_id INT DEFAULT NULL, id_project_id INT DEFAULT NULL, production_time INT NOT NULL, entry_date DATE NOT NULL, INDEX IDX_7D62ABA94113CAB (id_employee_id), INDEX IDX_7D62ABAB3E79F4B (id_project_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE project (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, selling_price DOUBLE PRECISION DEFAULT NULL, creation_date DATE NOT NULL, delivery_date DATE DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE employee ADD CONSTRAINT FK_5D9F75A12DD7FB44 FOREIGN KEY (id_job_id) REFERENCES job (id)');
        $this->addSql('ALTER TABLE production_times ADD CONSTRAINT FK_7D62ABA94113CAB FOREIGN KEY (id_employee_id) REFERENCES employee (id)');
        $this->addSql('ALTER TABLE production_times ADD CONSTRAINT FK_7D62ABAB3E79F4B FOREIGN KEY (id_project_id) REFERENCES project (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE production_times DROP FOREIGN KEY FK_7D62ABA94113CAB');
        $this->addSql('ALTER TABLE employee DROP FOREIGN KEY FK_5D9F75A12DD7FB44');
        $this->addSql('ALTER TABLE production_times DROP FOREIGN KEY FK_7D62ABAB3E79F4B');
        $this->addSql('DROP TABLE employee');
        $this->addSql('DROP TABLE job');
        $this->addSql('DROP TABLE production_times');
        $this->addSql('DROP TABLE project');
    }
}
