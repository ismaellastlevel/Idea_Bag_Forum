<?php
/**
 * Migrations reset pwd
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
final class Version20200828103542 extends AbstractMigration
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
        $this->addSql('ALTER TABLE user ADD reset_password_token VARCHAR(75) DEFAULT NULL');

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
        $this->addSql('ALTER TABLE user DROP reset_password_token');

    }//end down()


}//end class
