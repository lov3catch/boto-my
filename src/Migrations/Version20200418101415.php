<?php

declare(strict_types=1);

namespace DoctrineMigrations;

use Doctrine\DBAL\Schema\Schema;
use Doctrine\Migrations\AbstractMigration;

/**
 * Auto-generated Migration: Please modify to your needs!
 */
final class Version20200418101415 extends AbstractMigration
{
    public function getDescription() : string
    {
        return '';
    }

    public function up(Schema $schema) : void
    {
        // this up() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('DROP SEQUENCE channel_activity_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE moderator_start_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE moderator_member_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE moderator_block_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE moderator_referral_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE moderator_setting_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE moderator_group_id_seq CASCADE');
        $this->addSql('DROP SEQUENCE moderator_owner_id_seq CASCADE');
        $this->addSql('DROP TABLE moderator_start');
        $this->addSql('DROP TABLE moderator_member');
        $this->addSql('DROP TABLE moderator_block');
        $this->addSql('DROP TABLE moderator_referral');
        $this->addSql('DROP TABLE moderator_setting');
        $this->addSql('DROP TABLE moderator_group');
        $this->addSql('DROP TABLE moderator_owner');
        $this->addSql('DROP TABLE channel');
        $this->addSql('DROP TABLE channel_activity');
        $this->addSql('ALTER TABLE element ALTER id SET NOT NULL');
        $this->addSql('ALTER TABLE element ALTER name SET NOT NULL');
        $this->addSql('ALTER TABLE element ALTER description SET NOT NULL');
        $this->addSql('ALTER TABLE element ALTER status SET NOT NULL');
        $this->addSql('ALTER TABLE element ALTER url SET NOT NULL');
        $this->addSql('ALTER TABLE element ALTER platform_id SET NOT NULL');
        $this->addSql('ALTER TABLE element ALTER type_id SET NOT NULL');
        $this->addSql('ALTER TABLE element ALTER group_id SET DEFAULT 0');
        $this->addSql('ALTER TABLE element ALTER group_id SET NOT NULL');
        $this->addSql('ALTER TABLE element ADD CONSTRAINT FK_41405E39FFE6496F FOREIGN KEY (platform_id) REFERENCES platform (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('ALTER TABLE element ADD CONSTRAINT FK_41405E39C54C8C93 FOREIGN KEY (type_id) REFERENCES element_type (id) NOT DEFERRABLE INITIALLY IMMEDIATE');
        $this->addSql('CREATE INDEX IDX_41405E39FFE6496F ON element (platform_id)');
        $this->addSql('CREATE INDEX IDX_41405E39C54C8C93 ON element (type_id)');
        $this->addSql('ALTER TABLE element ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE element_type ALTER id SET NOT NULL');
        $this->addSql('ALTER TABLE element_type ALTER name SET NOT NULL');
        $this->addSql('ALTER TABLE element_type ADD PRIMARY KEY (id)');
        $this->addSql('ALTER TABLE platform ALTER id SET NOT NULL');
        $this->addSql('ALTER TABLE platform ALTER name SET NOT NULL');
        $this->addSql('ALTER TABLE platform ADD PRIMARY KEY (id)');
    }

    public function down(Schema $schema) : void
    {
        // this down() migration is auto-generated, please modify it to your needs
        $this->abortIf($this->connection->getDatabasePlatform()->getName() !== 'postgresql', 'Migration can only be executed safely on \'postgresql\'.');

        $this->addSql('CREATE SCHEMA public');
        $this->addSql('CREATE SEQUENCE channel_activity_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE moderator_start_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE moderator_member_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE moderator_block_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE moderator_referral_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE moderator_setting_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE moderator_group_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE SEQUENCE moderator_owner_id_seq INCREMENT BY 1 MINVALUE 1 START 1');
        $this->addSql('CREATE TABLE moderator_start (id INT NOT NULL, bot_id INT NOT NULL, user_id INT NOT NULL, is_superuser BOOLEAN NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE moderator_member (id INT NOT NULL, member_id INT NOT NULL, member_is_bot BOOLEAN NOT NULL, member_first_name VARCHAR(255) NOT NULL, member_username VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE moderator_block (id INT NOT NULL, group_id BIGINT NOT NULL, user_id INT NOT NULL, admin_id INT NOT NULL, strategy VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE moderator_referral (id INT NOT NULL, group_id BIGINT NOT NULL, user_id INT NOT NULL, referral_id INT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE moderator_setting (id INT NOT NULL, is_default BOOLEAN NOT NULL, max_message_words_count INT NOT NULL, max_message_chars_count INT NOT NULL, holdtime INT NOT NULL, max_daily_message_count INT NOT NULL, min_referrals_count INT NOT NULL, group_id BIGINT DEFAULT NULL, allow_link BOOLEAN NOT NULL, greeting_message TEXT DEFAULT NULL, greeting_buttons TEXT DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE moderator_group (id INT NOT NULL, group_id BIGINT NOT NULL, group_title VARCHAR(255) NOT NULL, group_username VARCHAR(255) NOT NULL, group_type VARCHAR(255) NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE moderator_owner (id INT NOT NULL, user_id INT NOT NULL, group_id BIGINT NOT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE NOT NULL, is_active BOOLEAN NOT NULL, PRIMARY KEY(id))');
        $this->addSql('CREATE TABLE channel (channel_id INT DEFAULT NULL, first_name VARCHAR(255) DEFAULT NULL, last_name VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, updated_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL, language_code VARCHAR(5) DEFAULT NULL, handler_name VARCHAR(255) DEFAULT NULL)');
        $this->addSql('CREATE TABLE channel_activity (id INT DEFAULT NULL, channel_id INT DEFAULT NULL, handler_name VARCHAR(255) DEFAULT NULL, created_at TIMESTAMP(0) WITHOUT TIME ZONE DEFAULT NULL)');
        $this->addSql('DROP INDEX "primary"');
        $this->addSql('ALTER TABLE element_type ALTER id DROP NOT NULL');
        $this->addSql('ALTER TABLE element_type ALTER name DROP NOT NULL');
        $this->addSql('DROP INDEX "primary"');
        $this->addSql('ALTER TABLE platform ALTER id DROP NOT NULL');
        $this->addSql('ALTER TABLE platform ALTER name DROP NOT NULL');
        $this->addSql('ALTER TABLE element DROP CONSTRAINT FK_41405E39FFE6496F');
        $this->addSql('ALTER TABLE element DROP CONSTRAINT FK_41405E39C54C8C93');
        $this->addSql('DROP INDEX IDX_41405E39FFE6496F');
        $this->addSql('DROP INDEX IDX_41405E39C54C8C93');
        $this->addSql('DROP INDEX "primary"');
        $this->addSql('ALTER TABLE element ALTER id DROP NOT NULL');
        $this->addSql('ALTER TABLE element ALTER platform_id DROP NOT NULL');
        $this->addSql('ALTER TABLE element ALTER type_id DROP NOT NULL');
        $this->addSql('ALTER TABLE element ALTER name DROP NOT NULL');
        $this->addSql('ALTER TABLE element ALTER description DROP NOT NULL');
        $this->addSql('ALTER TABLE element ALTER status DROP NOT NULL');
        $this->addSql('ALTER TABLE element ALTER url DROP NOT NULL');
        $this->addSql('ALTER TABLE element ALTER group_id DROP DEFAULT');
        $this->addSql('ALTER TABLE element ALTER group_id DROP NOT NULL');
    }
}
