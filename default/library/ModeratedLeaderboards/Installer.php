<?php

class ModeratedLeaderboards_Installer
{

  //protected static $table = array(
  //'createQuery' => 'CREATE TABLE IF NOT EXISTS `xf_moderated_leaderboards_` (
  //`simple_id` INT( 10 ) NOT NULL AUTO_INCREMENT,
  //`simple_text` VARCHAR ( 200 ),
  //`simple_date` INT( 10 ) UNSIGNED,
  //PRIMARY KEY (`simple_id`)
  //)
  //ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;',
  //'dropQuery' => 'DROP TABLE IF EXISTS `xf_moderated_leaderboards`'
  //);

  public static function install()
  {
    $db = XenForo_Application::get('db');
    $db->beginTransaction();

    $db->query(<<<EOT
CREATE TABLE IF NOT EXISTS `xf_moderated_leaderboards_leaderboard` (
                  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                  `name` VARCHAR(300),
                  `maintainer_id` INT(10) UNSIGNED NOT NULL,
                  `thread_id` INT(10) unsigned NOT NULL,
                  PRIMARY KEY (`id`),
                  FOREIGN KEY (`maintainer_id`) REFERENCES xf_user(user_id),
                  FOREIGN KEY (`thread_id`) REFERENCES xf_thread(thread_id)
                  )
              ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;

EOT
  );

    $db->query(<<<EOT
CREATE TABLE IF NOT EXISTS `xf_moderated_leaderboards_required_attributes` (
                  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                  `name` VARCHAR(50) NOT NULL,
                  `type` INT NOT NULL,
                  `ordinal` INT NOT NULL,
                  `ascending` BOOL ,
                  `leaderboard_id` INT UNSIGNED NOT NULL,
                  PRIMARY KEY (`id`),
                  FOREIGN KEY (`leaderboard_id`) REFERENCES xf_moderated_leaderboards_leaderboard(id)
                  )
              ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;

EOT
  );

    $db->query(<<<EOT
CREATE TABLE IF NOT EXISTS `xf_moderated_leaderboards_ranks` (
                  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                  `attribute_id` INT UNSIGNED NOT NULL,
                  `name` VARCHAR(50) NOT NULL,
                  `ordinal` INT NOT NULL,
                  PRIMARY KEY (`id`),
                  FOREIGN KEY (`attribute_id`) REFERENCES xf_moderated_leaderboards_required_attributes(id)
                  )
              ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;

EOT
  );

    $db->query(<<<EOT
CREATE TABLE IF NOT EXISTS `xf_moderated_leaderboards_records` (
                  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                  `comment` TEXT,
                  `proof_url` VARCHAR(300),
                  `leaderboard_id` INT UNSIGNED NOT NULL,
                  `user_id` INT(10) UNSIGNED NOT NULL,
                  PRIMARY KEY (`id`),
                  FOREIGN KEY (`leaderboard_id`) REFERENCES xf_moderated_leaderboards_leaderboard(id),
                  FOREIGN KEY (`user_id`) REFERENCES xf_user(user_id)
                  )
              ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;

EOT
  );

    $db->query(<<<EOT
CREATE TABLE IF NOT EXISTS `xf_moderated_leaderboards_record_attributes` (
                  `id` INT(10) UNSIGNED NOT NULL AUTO_INCREMENT,
                  `value` INT,
                  `attribute_id` INT UNSIGNED NOT NULL,
                  `record_id` INT UNSIGNED NOT NULL,
                  PRIMARY KEY (`id`),
                  FOREIGN KEY (`attribute_id`) REFERENCES xf_moderated_leaderboards_required_attributes(id)
                  )
              ENGINE = InnoDB CHARACTER SET utf8 COLLATE utf8_general_ci;

EOT
  );
    $db->commit();
}

public static function uninstall()
{
  $db = XenForo_Application::get('db');
  $db->query();
} 


}
