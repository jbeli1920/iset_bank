<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240520100213 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE demande_credit (id INT AUTO_INCREMENT NOT NULL, compte_id INT NOT NULL, montant INT NOT NULL, date VARCHAR(255) NOT NULL, nbr_mois INT NOT NULL, status INT NOT NULL, raison VARCHAR(255) NOT NULL, montant_mois INT NOT NULL, INDEX IDX_5E852811F2C56620 (compte_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, compte_id INT NOT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, date VARCHAR(255) NOT NULL, status INT NOT NULL, INDEX IDX_CE606404F2C56620 (compte_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE demande_credit ADD CONSTRAINT FK_5E852811F2C56620 FOREIGN KEY (compte_id) REFERENCES compte_bancaire (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404F2C56620 FOREIGN KEY (compte_id) REFERENCES compte_bancaire (id)');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D12C3C85B1');
        $this->addSql('DROP INDEX IDX_723705D12C3C85B1 ON transaction');
        $this->addSql('ALTER TABLE transaction ADD destinaire VARCHAR(255) NOT NULL, ADD nom_destinaire VARCHAR(255) NOT NULL, ADD nom_destinataire VARCHAR(255) NOT NULL, CHANGE id_compte_bancaire_id compte_destinaire_id INT NOT NULL');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D11B661634 FOREIGN KEY (compte_destinaire_id) REFERENCES compte_bancaire (id)');
        $this->addSql('CREATE INDEX IDX_723705D11B661634 ON transaction (compte_destinaire_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE demande_credit DROP FOREIGN KEY FK_5E852811F2C56620');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404F2C56620');
        $this->addSql('DROP TABLE demande_credit');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D11B661634');
        $this->addSql('DROP INDEX IDX_723705D11B661634 ON transaction');
        $this->addSql('ALTER TABLE transaction DROP destinaire, DROP nom_destinaire, DROP nom_destinataire, CHANGE compte_destinaire_id id_compte_bancaire_id INT NOT NULL');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D12C3C85B1 FOREIGN KEY (id_compte_bancaire_id) REFERENCES compte_bancaire (id) ON UPDATE NO ACTION ON DELETE NO ACTION');
        $this->addSql('CREATE INDEX IDX_723705D12C3C85B1 ON transaction (id_compte_bancaire_id)');
    }
}
