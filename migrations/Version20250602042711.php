<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20250602042711 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            CREATE TABLE listas (id INT AUTO_INCREMENT NOT NULL, usuario_id INT NOT NULL, titulo VARCHAR(150) NOT NULL, is_publico TINYINT(1) NOT NULL, INDEX IDX_C54ECE20629AF449 (usuario_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE tarefa_status (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE tarefas (id INT AUTO_INCREMENT NOT NULL, lista_id INT DEFAULT NULL, tarefa_status_id INT NOT NULL, tiulo VARCHAR(150) NOT NULL, INDEX IDX_30B98ED594F06EEA (lista_id), UNIQUE INDEX UNIQ_30B98ED51C8001ED (tarefa_status_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            CREATE TABLE usuarios (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, email VARCHAR(150) NOT NULL, password VARCHAR(80) NOT NULL, is_admin TINYINT(1) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE listas ADD CONSTRAINT FK_C54ECE20629AF449 FOREIGN KEY (usuario_id) REFERENCES usuarios (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tarefas ADD CONSTRAINT FK_30B98ED594F06EEA FOREIGN KEY (lista_id) REFERENCES listas (id)
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tarefas ADD CONSTRAINT FK_30B98ED51C8001ED FOREIGN KEY (tarefa_status_id) REFERENCES tarefa_status (id)
        SQL);
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql(<<<'SQL'
            ALTER TABLE listas DROP FOREIGN KEY FK_C54ECE20629AF449
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tarefas DROP FOREIGN KEY FK_30B98ED594F06EEA
        SQL);
        $this->addSql(<<<'SQL'
            ALTER TABLE tarefas DROP FOREIGN KEY FK_30B98ED51C8001ED
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE listas
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE tarefa_status
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE tarefas
        SQL);
        $this->addSql(<<<'SQL'
            DROP TABLE usuarios
        SQL);
    }
}
