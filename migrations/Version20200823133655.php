<?php
/**
 * Migrations User
 *
 * @package   migrations
 * @version   0.0.1
 * @author    Adrien Colonna
 * @copyright no copyrights
 */

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200823133655 extends AbstractMigration
{


    /**
     * Title of table
     *
     * @return string
     */
    public function getDescription() : string
    {
        return '';

    }//end getDescription()


    /**
     * Schema up of user table
     *
     * @param Schema $schema Schema up of user table.
     *
     * @return void
     */
    public function up(Schema $schema) : void
    {
        // This up() migration is auto-generated, please modify it to your needs.
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, username VARCHAR(255) NOT NULL, roles JSON NOT NULL, password VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), UNIQUE INDEX UNIQ_8D93D649F85E0677 (username), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');

    }//end up()


    /**
     * Schema down of user table
     *
     * @param Schema $schema Schema down of user table.
     *
     * @return void
     */
    public function down(Schema $schema) : void
    {
        // This down() migration is auto-generated, please modify it to your needs.
        $this->addSql('DROP TABLE user');

    }//end down()


}//end class
