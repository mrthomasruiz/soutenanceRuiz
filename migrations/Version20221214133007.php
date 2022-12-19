<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20221214133007 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produits ADD categories_id INT DEFAULT NULL, ADD sous_categorie_id INT DEFAULT NULL');
        $this->addSql('ALTER TABLE produits ADD CONSTRAINT FK_BE2DDF8CA21214B7 FOREIGN KEY (categories_id) REFERENCES categorie (id)');
        $this->addSql('ALTER TABLE produits ADD CONSTRAINT FK_BE2DDF8C365BF48 FOREIGN KEY (sous_categorie_id) REFERENCES sous_categorie (id)');
        $this->addSql('CREATE INDEX IDX_BE2DDF8CA21214B7 ON produits (categories_id)');
        $this->addSql('CREATE INDEX IDX_BE2DDF8C365BF48 ON produits (sous_categorie_id)');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE produits DROP FOREIGN KEY FK_BE2DDF8CA21214B7');
        $this->addSql('ALTER TABLE produits DROP FOREIGN KEY FK_BE2DDF8C365BF48');
        $this->addSql('DROP INDEX IDX_BE2DDF8CA21214B7 ON produits');
        $this->addSql('DROP INDEX IDX_BE2DDF8C365BF48 ON produits');
        $this->addSql('ALTER TABLE produits DROP categories_id, DROP sous_categorie_id');
    }
}
