<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20240520100321 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE compte_bancaire (id INT AUTO_INCREMENT NOT NULL, id_compte_id INT NOT NULL, numero_carte VARCHAR(255) DEFAULT NULL, code_securite VARCHAR(255) DEFAULT NULL, solde DOUBLE PRECISION DEFAULT NULL, confirme INT NOT NULL, status INT NOT NULL, UNIQUE INDEX UNIQ_50BC21DE72F0DA07 (id_compte_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE demande_credit (id INT AUTO_INCREMENT NOT NULL, compte_id INT NOT NULL, montant INT NOT NULL, date VARCHAR(255) NOT NULL, nbr_mois INT NOT NULL, status INT NOT NULL, raison VARCHAR(255) NOT NULL, montant_mois INT NOT NULL, INDEX IDX_5E852811F2C56620 (compte_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reclamation (id INT AUTO_INCREMENT NOT NULL, compte_id INT NOT NULL, titre VARCHAR(255) NOT NULL, description VARCHAR(255) NOT NULL, date VARCHAR(255) NOT NULL, status INT NOT NULL, INDEX IDX_CE606404F2C56620 (compte_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE transaction (id INT AUTO_INCREMENT NOT NULL, compte_destinaire_id INT NOT NULL, destinaire VARCHAR(255) NOT NULL, destinataire VARCHAR(255) NOT NULL, date VARCHAR(255) NOT NULL, nom_destinaire VARCHAR(255) NOT NULL, nom_destinataire VARCHAR(255) NOT NULL, montant DOUBLE PRECISION NOT NULL, INDEX IDX_723705D11B661634 (compte_destinaire_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE utilisateur (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(255) NOT NULL, prenom VARCHAR(255) NOT NULL, email VARCHAR(255) NOT NULL, code_confirmation_email VARCHAR(255) NOT NULL, mot_de_passe VARCHAR(255) NOT NULL, profil VARCHAR(255) NOT NULL, code_changement_password VARCHAR(255) NOT NULL, email_confirme INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE compte_bancaire ADD CONSTRAINT FK_50BC21DE72F0DA07 FOREIGN KEY (id_compte_id) REFERENCES utilisateur (id)');
        $this->addSql('ALTER TABLE demande_credit ADD CONSTRAINT FK_5E852811F2C56620 FOREIGN KEY (compte_id) REFERENCES compte_bancaire (id)');
        $this->addSql('ALTER TABLE reclamation ADD CONSTRAINT FK_CE606404F2C56620 FOREIGN KEY (compte_id) REFERENCES compte_bancaire (id)');
        $this->addSql('ALTER TABLE transaction ADD CONSTRAINT FK_723705D11B661634 FOREIGN KEY (compte_destinaire_id) REFERENCES compte_bancaire (id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE compte_bancaire DROP FOREIGN KEY FK_50BC21DE72F0DA07');
        $this->addSql('ALTER TABLE demande_credit DROP FOREIGN KEY FK_5E852811F2C56620');
        $this->addSql('ALTER TABLE reclamation DROP FOREIGN KEY FK_CE606404F2C56620');
        $this->addSql('ALTER TABLE transaction DROP FOREIGN KEY FK_723705D11B661634');
        $this->addSql('DROP TABLE compte_bancaire');
        $this->addSql('DROP TABLE demande_credit');
        $this->addSql('DROP TABLE reclamation');
        $this->addSql('DROP TABLE transaction');
        $this->addSql('DROP TABLE utilisateur');
    }
}
