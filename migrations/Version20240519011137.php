<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240519011137 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE compte_bancaire (id INT AUTO_INCREMENT NOT NULL, id_compte_id INT NOT NULL, numero_carte VARCHAR(255) DEFAULT NULL, code_securite VARCHAR(255) DEFAULT NULL, solde DOUBLE PRECISION DEFAULT NULL, confirme INT NOT NULL, status INT NOT NULL, UNIQUE INDEX UNIQ_50BC21DE72F0DA07 (id_compte_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, id_compte_bancaire_id INT NOT NULL, destinataire VARCHAR(255) NOT NULL, montant DOUBLE PRECISION NOT NULL, date VARCHAR(255) NOT NULL, INDEX IDX_723705D12C3C85B1 (id_compte_bancaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE compte_bancaire ADD CONSTRAINT FK_50BC21DE72F0DA07 FOREIGN KEY (id_compte_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D12C3C85B1 FOREIGN KEY (id_compte_bancaire_id) REFERENCES compte_bancaire (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE compte_bancaire DROP FOREIGN KEY FK_50BC21DE72F0DA07');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D12C3C85B1');
        $this->addSql('DROP TABLE compte_bancaire');
        $this->addSql('DROP TABLE transaction');
    }
}
