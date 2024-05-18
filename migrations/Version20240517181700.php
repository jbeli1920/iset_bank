namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240509151217 extends AbstractMigration
{
    public function up(Schema $schema): void
    {
        // Check if the table exists before creating it
        if (!$schema->hasTable('utilisateur')) {
            $this->addSql('CREATE TABLE utilisateur (
                id INT AUTO_INCREMENT NOT NULL,
                username VARCHAR(255) NOT NULL,
                password VARCHAR(255) NOT NULL,
                PRIMARY KEY(id)
            )');
        }
    }

    public function down(Schema $schema): void
    {
        $this->addSql('DROP TABLE utilisateur');
    }
}
