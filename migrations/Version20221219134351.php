<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221219134351 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE achat DROP FOREIGN KEY FK_26A984562C478137');
        $this->addSql('DROP INDEX IDX_26A984562C478137 ON achat');
        $this->addSql('ALTER TABLE achat CHANGE commandre_id commande_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE achat ADD CONSTRAINT FK_26A9845682EA2E54 FOREIGN KEY (commande_id) REFERENCES commande (id)');
        $this->addSql('CREATE INDEX IDX_26A9845682EA2E54 ON achat (commande_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE achat DROP FOREIGN KEY FK_26A9845682EA2E54');
        $this->addSql('DROP INDEX IDX_26A9845682EA2E54 ON achat');
        $this->addSql('ALTER TABLE achat CHANGE commande_id commandre_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE achat ADD CONSTRAINT FK_26A984562C478137 FOREIGN KEY (commandre_id) REFERENCES commande (id)');
        $this->addSql('CREATE INDEX IDX_26A984562C478137 ON achat (commandre_id)');
    }
}
