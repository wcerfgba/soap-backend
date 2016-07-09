<?php

namespace Application\Migrations;

use Doctrine\DBAL\Migrations\AbstractMigration;
use Doctrine\DBAL\Schema\Schema;

/**
 * Populates the database with some test values.
 */
class Version20160709154218 extends AbstractMigration
{
    /**
     * @param Schema $schema
     */
    public function up(Schema $schema)
    {
      $sql = <<<EOD
INSERT INTO score (id, name, difficulty, score) VALUES
(1, "John", "medium", 500),
(2, "Bill", "medium", 700),
(3, "Adam", "medium", 900),
(4, "Kate", "hard", 2000),
(5, "Mary", "medium", 50),
(6, "Joanne", "hard", 690),
(7, "Bill", "easy", 210),
(8, "Kate", "easy", 5000),
(9, "Joanne", "medium", 550),
(10, "John", "easy", 961),
(11, "Adam", "easy", 2010),
(12, "Mary", "medium", 3050),
(13, "Bill", "hard", 500),
(14, "Mary", "medium", 550),
(15, "Joanne", "hard", 2000),
(16, "John", "hard", 50),
(17, "Kate", "medium", 960),
(18, "Mary", "easy", 1130),
(19, "Kate", "medium", 4400),
(20, "Bill", "medium", 500),
(21, "Joanne", "hard", 500)
EOD;
      $this->addSql($sql);
    }

    /**
     * @param Schema $schema
     */
    public function down(Schema $schema)
    {
      $this->addSql('DELETE FROM score WHERE id BETWEEN 1 AND 21');
    }
}
