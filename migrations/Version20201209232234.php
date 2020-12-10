<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20201209232234 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE attack (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, description VARCHAR(255) DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon (id INT AUTO_INCREMENT NOT NULL, nom VARCHAR(100) NOT NULL, levelevolution INT DEFAULT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon_attack (pokemon_id INT NOT NULL, attack_id INT NOT NULL, INDEX IDX_2B29516F2FE71C3E (pokemon_id), INDEX IDX_2B29516FF5315759 (attack_id), PRIMARY KEY(pokemon_id, attack_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon_type (pokemon_id INT NOT NULL, type_id INT NOT NULL, INDEX IDX_B077296A2FE71C3E (pokemon_id), INDEX IDX_B077296AC54C8C93 (type_id), PRIMARY KEY(pokemon_id, type_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE pokemon_trainer (pokemon_id INT NOT NULL, trainer_id INT NOT NULL, INDEX IDX_A97F74B12FE71C3E (pokemon_id), INDEX IDX_A97F74B1FB08EDF6 (trainer_id), PRIMARY KEY(pokemon_id, trainer_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE trainer (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(50) NOT NULL, gender INT NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE type (id INT AUTO_INCREMENT NOT NULL, name VARCHAR(100) NOT NULL, PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE pokemon_attack ADD CONSTRAINT FK_2B29516F2FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pokemon_attack ADD CONSTRAINT FK_2B29516FF5315759 FOREIGN KEY (attack_id) REFERENCES attack (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pokemon_type ADD CONSTRAINT FK_B077296A2FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pokemon_type ADD CONSTRAINT FK_B077296AC54C8C93 FOREIGN KEY (type_id) REFERENCES type (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pokemon_trainer ADD CONSTRAINT FK_A97F74B12FE71C3E FOREIGN KEY (pokemon_id) REFERENCES pokemon (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE pokemon_trainer ADD CONSTRAINT FK_A97F74B1FB08EDF6 FOREIGN KEY (trainer_id) REFERENCES trainer (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE pokemon_attack DROP FOREIGN KEY FK_2B29516FF5315759');
        $this->addSql('ALTER TABLE pokemon_attack DROP FOREIGN KEY FK_2B29516F2FE71C3E');
        $this->addSql('ALTER TABLE pokemon_type DROP FOREIGN KEY FK_B077296A2FE71C3E');
        $this->addSql('ALTER TABLE pokemon_trainer DROP FOREIGN KEY FK_A97F74B12FE71C3E');
        $this->addSql('ALTER TABLE pokemon_trainer DROP FOREIGN KEY FK_A97F74B1FB08EDF6');
        $this->addSql('ALTER TABLE pokemon_type DROP FOREIGN KEY FK_B077296AC54C8C93');
        $this->addSql('DROP TABLE attack');
        $this->addSql('DROP TABLE pokemon');
        $this->addSql('DROP TABLE pokemon_attack');
        $this->addSql('DROP TABLE pokemon_type');
        $this->addSql('DROP TABLE pokemon_trainer');
        $this->addSql('DROP TABLE trainer');
        $this->addSql('DROP TABLE type');
    }
}
