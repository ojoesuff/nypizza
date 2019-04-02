<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190402083526 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `order` ADD customer_id_id INT DEFAULT NULL, CHANGE status order_status VARCHAR(20) NOT NULL');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398B171EB6C FOREIGN KEY (customer_id_id) REFERENCES user (id)');
        $this->addSql('CREATE INDEX IDX_F5299398B171EB6C ON `order` (customer_id_id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE `order` DROP FOREIGN KEY FK_F5299398B171EB6C');
        $this->addSql('DROP INDEX IDX_F5299398B171EB6C ON `order`');
        $this->addSql('ALTER TABLE `order` DROP customer_id_id, CHANGE order_status status VARCHAR(20) NOT NULL COLLATE utf8mb4_unicode_ci');
    }
}
