<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20190402010015 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE custom_pizza ADD ham TINYINT(1) DEFAULT NULL, ADD chicken TINYINT(1) DEFAULT NULL, ADD pepperoni TINYINT(1) DEFAULT NULL, ADD sweetcorn TINYINT(1) DEFAULT NULL, ADD tomato TINYINT(1) DEFAULT NULL, ADD peppers TINYINT(1) DEFAULT NULL, DROP topping1, DROP topping2, DROP topping3, DROP topping4, DROP topping5, DROP topping6');
        $this->addSql('ALTER TABLE `order` ADD quantity INT NOT NULL, CHANGE date_created date_created DATETIME NOT NULL');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'mysql', 'Migration can only be executed safely on \'mysql\'.');

        $this->addSql('ALTER TABLE custom_pizza ADD topping1 VARCHAR(20) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD topping2 VARCHAR(20) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD topping3 VARCHAR(20) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD topping4 VARCHAR(20) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD topping5 VARCHAR(20) DEFAULT NULL COLLATE utf8mb4_unicode_ci, ADD topping6 VARCHAR(20) DEFAULT NULL COLLATE utf8mb4_unicode_ci, DROP ham, DROP chicken, DROP pepperoni, DROP sweetcorn, DROP tomato, DROP peppers');
        $this->addSql('ALTER TABLE `order` DROP quantity, CHANGE date_created date_created DATETIME DEFAULT NULL');
    }
}
