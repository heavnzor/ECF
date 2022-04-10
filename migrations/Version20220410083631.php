<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20220410083631 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->addSql('CREATE TABLE cours (id INT AUTO_INCREMENT NOT NULL, section_id INT NOT NULL, formation_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, cours LONGTEXT NOT NULL, image VARCHAR(255) DEFAULT NULL, pdf VARCHAR(255) DEFAULT NULL, video VARCHAR(255) NOT NULL, is_finished TINYINT(1) DEFAULT NULL, INDEX IDX_FDCA8C9CD823E37A (section_id), INDEX IDX_FDCA8C9C5200282E (formation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE formation (id INT AUTO_INCREMENT NOT NULL, auteur_id INT DEFAULT NULL, user_id INT DEFAULT NULL, titre VARCHAR(255) NOT NULL, description LONGTEXT NOT NULL, image VARCHAR(255) NOT NULL, learn_state INT NOT NULL, INDEX IDX_404021BF60BB6FE6 (auteur_id), INDEX IDX_404021BFA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE quizz (id INT AUTO_INCREMENT NOT NULL, section_id INT DEFAULT NULL, question VARCHAR(255) NOT NULL, reponse1 VARCHAR(255) NOT NULL, reponse2 VARCHAR(255) NOT NULL, reponse3 VARCHAR(255) NOT NULL, bonnereponse VARCHAR(255) NOT NULL, UNIQUE INDEX UNIQ_7C77973DD823E37A (section_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE reset_password_request (id INT AUTO_INCREMENT NOT NULL, user_id INT NOT NULL, selector VARCHAR(20) NOT NULL, hashed_token VARCHAR(100) NOT NULL, requested_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', expires_at DATETIME NOT NULL COMMENT \'(DC2Type:datetime_immutable)\', INDEX IDX_7CE748AA76ED395 (user_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE section (id INT AUTO_INCREMENT NOT NULL, formation_id INT NOT NULL, titre VARCHAR(100) NOT NULL, INDEX IDX_2D737AEF5200282E (formation_id), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE section_user (section_id INT NOT NULL, user_id INT NOT NULL, INDEX IDX_82D3A42D823E37A (section_id), INDEX IDX_82D3A42A76ED395 (user_id), PRIMARY KEY(section_id, user_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (id INT AUTO_INCREMENT NOT NULL, email VARCHAR(180) NOT NULL, pseudo VARCHAR(180) DEFAULT NULL, nom VARCHAR(255) DEFAULT NULL, prenom VARCHAR(255) DEFAULT NULL, description LONGTEXT DEFAULT NULL, photo VARCHAR(255) DEFAULT NULL, progress DOUBLE PRECISION DEFAULT NULL, roles JSON DEFAULT NULL, password VARCHAR(255) NOT NULL, is_verified TINYINT(1) DEFAULT NULL, is_postulant TINYINT(1) DEFAULT NULL, UNIQUE INDEX UNIQ_8D93D649E7927C74 (email), PRIMARY KEY(id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user_cours (user_id INT NOT NULL, cours_id INT NOT NULL, INDEX IDX_1F0877C4A76ED395 (user_id), INDEX IDX_1F0877C47ECF78B0 (cours_id), PRIMARY KEY(user_id, cours_id)) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9CD823E37A FOREIGN KEY (section_id) REFERENCES section (id)');
        $this->addSql('ALTER TABLE cours ADD CONSTRAINT FK_FDCA8C9C5200282E FOREIGN KEY (formation_id) REFERENCES formation (id)');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BF60BB6FE6 FOREIGN KEY (auteur_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE formation ADD CONSTRAINT FK_404021BFA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE quizz ADD CONSTRAINT FK_7C77973DD823E37A FOREIGN KEY (section_id) REFERENCES section (id)');
        $this->addSql('ALTER TABLE reset_password_request ADD CONSTRAINT FK_7CE748AA76ED395 FOREIGN KEY (user_id) REFERENCES user (id)');
        $this->addSql('ALTER TABLE section ADD CONSTRAINT FK_2D737AEF5200282E FOREIGN KEY (formation_id) REFERENCES formation (id)');
        $this->addSql('ALTER TABLE section_user ADD CONSTRAINT FK_82D3A42D823E37A FOREIGN KEY (section_id) REFERENCES section (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE section_user ADD CONSTRAINT FK_82D3A42A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_cours ADD CONSTRAINT FK_1F0877C4A76ED395 FOREIGN KEY (user_id) REFERENCES user (id) ON DELETE CASCADE');
        $this->addSql('ALTER TABLE user_cours ADD CONSTRAINT FK_1F0877C47ECF78B0 FOREIGN KEY (cours_id) REFERENCES cours (id) ON DELETE CASCADE');
    }

    public function down(Schema $schema): void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->addSql('ALTER TABLE user_cours DROP FOREIGN KEY FK_1F0877C47ECF78B0');
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9C5200282E');
        $this->addSql('ALTER TABLE section DROP FOREIGN KEY FK_2D737AEF5200282E');
        $this->addSql('ALTER TABLE cours DROP FOREIGN KEY FK_FDCA8C9CD823E37A');
        $this->addSql('ALTER TABLE quizz DROP FOREIGN KEY FK_7C77973DD823E37A');
        $this->addSql('ALTER TABLE section_user DROP FOREIGN KEY FK_82D3A42D823E37A');
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BF60BB6FE6');
        $this->addSql('ALTER TABLE formation DROP FOREIGN KEY FK_404021BFA76ED395');
        $this->addSql('ALTER TABLE reset_password_request DROP FOREIGN KEY FK_7CE748AA76ED395');
        $this->addSql('ALTER TABLE section_user DROP FOREIGN KEY FK_82D3A42A76ED395');
        $this->addSql('ALTER TABLE user_cours DROP FOREIGN KEY FK_1F0877C4A76ED395');
        $this->addSql('DROP TABLE cours');
        $this->addSql('DROP TABLE formation');
        $this->addSql('DROP TABLE quizz');
        $this->addSql('DROP TABLE reset_password_request');
        $this->addSql('DROP TABLE section');
        $this->addSql('DROP TABLE section_user');
        $this->addSql('DROP TABLE user');
        $this->addSql('DROP TABLE user_cours');
    }
}
