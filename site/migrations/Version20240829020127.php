<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

final class Version20240829020127 extends AbstractMigration
{
    public function getDescription(): string
    {
        return '';
    }

    public function up(Schema $schema): void
    {
        $this->addSql('CREATE TABLE post_view (
                        id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
                        user_id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
                        post_key VARCHAR(4) NOT NULL,
                        INDEX IDX_37A8CC85A76ED395 (user_id),
                        PRIMARY KEY(id)
                      ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('CREATE TABLE user (
                        id BINARY(16) NOT NULL COMMENT \'(DC2Type:uuid)\',
                        PRIMARY KEY(id)
                      ) DEFAULT CHARACTER SET utf8mb4 COLLATE `utf8mb4_unicode_ci` ENGINE = InnoDB');
        $this->addSql('ALTER TABLE post_view ADD CONSTRAINT FK_37A8CC85A76ED395
                        FOREIGN KEY (user_id) REFERENCES user (id)');
    }

    public function down(Schema $schema): void
    {
        $this->addSql('ALTER TABLE post_view DROP FOREIGN KEY FK_37A8CC85A76ED395');
        $this->addSql('DROP TABLE post_view');
        $this->addSql('DROP TABLE user');
    }
}
