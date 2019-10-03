SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


CREATE TABLE `answers` (
  `id` int(10) NOT NULL,
  `id_campaign` int(10) NOT NULL,
  `id_place` int(10) NOT NULL,
  `id_question` int(10) DEFAULT NULL,
  `id_option` int(10) DEFAULT NULL,
  `a_value` text,
  `guest_phid` varchar(100) NOT NULL,
  `guest_ip` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `campaigns` (
  `id` int(10) NOT NULL,
  `id_user` int(10) NOT NULL,
  `name` varchar(1000) NOT NULL,
  `description` text,
  `status` int(10) NOT NULL,
  `F_intro_text` text,
  `F_ending_text` text,
  `F_logo` varchar(1000) DEFAULT NULL,
  `F_E_label_title` varchar(1000) DEFAULT NULL,
  `date_from` date NOT NULL,
  `date_to` date DEFAULT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `campaign_places` (
  `id` int(10) NOT NULL,
  `id_user` int(10) NOT NULL,
  `id_place` int(10) NOT NULL,
  `id_campaign` int(10) NOT NULL,
  `shortcode` varchar(5) NOT NULL,
  `label_name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `campaign_questions` (
  `id` int(10) NOT NULL,
  `id_user` int(10) NOT NULL,
  `id_campaign` int(10) NOT NULL,
  `option_type` int(10) NOT NULL,
  `q_order` int(10) NOT NULL,
  `other` int(10) NOT NULL,
  `F_question` varchar(1000) NOT NULL,
  `F_label_other` varchar(1000) DEFAULT NULL,
  `F_extended_desc` text,
  `q_require` int(10) NOT NULL,
  `date_created` datetime NOT NULL,
  `date_modified` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `campaign_question_options` (
  `id` int(10) NOT NULL,
  `id_user` int(10) NOT NULL,
  `id_campaign` int(10) NOT NULL,
  `id_question` int(10) NOT NULL,
  `option_label` varchar(1000) NOT NULL,
  `o_order` int(10) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `dictionary_option_types` (
  `id` int(10) NOT NULL,
  `description` text NOT NULL,
  `type_explain` text NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `places` (
  `id` int(10) NOT NULL,
  `id_user` int(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `comment` text,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `users` (
  `id` int(10) UNSIGNED NOT NULL,
  `firstName` varchar(100) DEFAULT NULL,
  `lastName` varchar(100) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `accept_terms` tinyint(4) NOT NULL,
  `verificationHash` varchar(100) NOT NULL,
  `isVerified` TINYINT(1) DEFAULT 0 NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `password_resets` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `newsletter` (
  `id` int(10) NOT NULL,
  `email_newsletter` varchar(100) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `consent` tinyint(4) DEFAULT NULL,
  `hash_unsubscribe` varchar(100) NOT NULL,
  `hash_resubscribe` varchar(100) NOT NULL,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `unique_views` (
  `id` int(10) NOT NULL,
  `id_campaign` int(10) NOT NULL,
  `id_place` int(10) NOT NULL,
  `guest_phid` varchar(100) NOT NULL,
  `guest_ip` varchar(15) NOT NULL,
  `date_created` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `answers`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `campaigns`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `campaign_places`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `campaign_questions`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `campaign_question_options`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `dictionary_option_types`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `places`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

ALTER TABLE `password_resets`
  ADD PRIMARY KEY (`email`);

ALTER TABLE `newsletter`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `unique_views`
  ADD PRIMARY KEY (`id`);


ALTER TABLE `newsletter`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

ALTER TABLE `unique_views`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

ALTER TABLE `answers`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

ALTER TABLE `campaigns`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

ALTER TABLE `campaign_places`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

ALTER TABLE `campaign_questions`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

ALTER TABLE `campaign_question_options`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

ALTER TABLE `dictionary_option_types`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

ALTER TABLE `places`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT;

ALTER TABLE `users`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);


--
-- EVENTS
--
CREATE EVENT `DeleteNoVerifyUser` ON SCHEDULE EVERY 10 MINUTE STARTS '2017-11-02 10:00:00.000000' ON COMPLETION NOT PRESERVE ENABLE DO DELETE FROM `users` WHERE `created_at` < (NOW() - INTERVAL 1 DAY) AND `isVerified` = 0;

SET GLOBAL event_scheduler="ON";