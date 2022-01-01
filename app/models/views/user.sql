SELECT
  `p`.`id`         AS `id`,
  `p`.`o_first_name` AS `first_name`,
  `p`.`o_last_name`  AS `last_name`,
  `p`.`o_username`   AS `username`,
  `p`.`o_password`   AS `password`,
  `p`.`o_email`      AS `email`,
  `p`.`o_position`   AS `position`,
  `p`.`o_permit`     AS `permit`,
  `u`.`o_first_name` AS `position_first_name`,
  `u`.`o_last_name`  AS `position_last_name`
FROM (`operator` `p`
   LEFT JOIN `operator` `u`
     ON (`p`.`o_position` = `u`.`id`))