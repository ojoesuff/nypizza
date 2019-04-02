<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190402084536 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE custom_pizza DROP FOREIGN KEY FK_CD07A06FCDAEAAA');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADFCDAEAAA');
        $this->addSql('CREATE TABLE final_order (id INT AUTO_INCREMENT NOT NULL, customer_id_id INT DEFAULT NULL, address_line1 VARCHAR(255) NOT NULL, address_line2 VARCHAR(255) NOT NULL, address_line3 VARCHAR(255) DEFAULT NULL, county VARCHAR(20) NOT NULL, eircode VARCHAR(10) DEFAULT NULL, order_status VARCHAR(20) NOT NULL, date_created DATETIME NOT NULL, INDEX IDX_F431C008B171EB6C (customer_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci ENGINE = InnoDB');
        $this->addSql('ALTER TABLE final_order ADD CONSTRAINT FK_F431C008B171EB6C FOREIGN KEY (customer_id_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE `order`');
        $this->addSql('ALTER TABLE custom_pizza DROP FOREIGN KEY FK_CD07A06FCDAEAAA');
        $this->addSql('ALTER TABLE custom_pizza ADD CONSTRAINT FK_CD07A06FCDAEAAA FOREIGN KEY (order_id_id) REFERENCES final_order (id)');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADFCDAEAAA');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADFCDAEAAA FOREIGN KEY (order_id_id) REFERENCES final_order (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE custom_pizza DROP FOREIGN KEY FK_CD07A06FCDAEAAA');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADFCDAEAAA');
        $this->addSql('CREATE TABLE `order` (id INT AUTO_INCREMENT NOT NULL, customer_id_id INT DEFAULT NULL, address_line1 VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, address_line2 VARCHAR(255) NOT NULL COLLATE utf8mb4_unicode_ci, address_line3 VARCHAR(255) DEFAULT NULL COLLATE utf8mb4_unicode_ci, county VARCHAR(20) NOT NULL COLLATE utf8mb4_unicode_ci, eircode VARCHAR(10) DEFAULT NULL COLLATE utf8mb4_unicode_ci, order_status VARCHAR(20) NOT NULL COLLATE utf8mb4_unicode_ci, date_created DATETIME NOT NULL, INDEX IDX_F5299398B171EB6C (customer_id_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8 COLLATE utf8_unicode_ci ENGINE = InnoDB COMMENT = \'\' ');
        $this->addSql('ALTER TABLE `order` ADD CONSTRAINT FK_F5299398B171EB6C FOREIGN KEY (customer_id_id) REFERENCES user (id)');
        $this->addSql('DROP TABLE final_order');
        $this->addSql('ALTER TABLE custom_pizza DROP FOREIGN KEY FK_CD07A06FCDAEAAA');
        $this->addSql('ALTER TABLE custom_pizza ADD CONSTRAINT FK_CD07A06FCDAEAAA FOREIGN KEY (order_id_id) REFERENCES `order` (id)');
        $this->addSql('ALTER TABLE product DROP FOREIGN KEY FK_D34A04ADFCDAEAAA');
        $this->addSql('ALTER TABLE product ADD CONSTRAINT FK_D34A04ADFCDAEAAA FOREIGN KEY (order_id_id) REFERENCES `order` (id)');
    }
}
