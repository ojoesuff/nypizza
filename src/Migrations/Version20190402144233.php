<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190402144233 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE custom_pizza ADD size VARCHAR(2) DEFAULT NULL');
        $this->addSql('ALTER TABLE custom_pizza ADD CONSTRAINT FK_CD07A06FCDAEAAA FOREIGN KEY (order_id_id) REFERENCES final_order (id)');
        $this->addSql('ALTER TABLE final_order ADD total INT NOT NULL');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADFCDAEAAA FOREIGN KEY (order_id_id) REFERENCES final_order (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE custom_pizza DROP FOREIGN KEY FK_CD07A06FCDAEAAA');
        $this->addSql('ALTER TABLE custom_pizza DROP size');
        $this->addSql('ALTER TABLE final_order DROP total');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADFCDAEAAA');
    }
}
