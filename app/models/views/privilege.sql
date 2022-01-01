SELECT
	`priv`.`id` AS `id`,
	`priv`.`title` AS `title`,
	`priv`.`icon` AS `icon`,
	`priv`.`query_string` AS `query_string`,
	`priv`.`root` AS `root`,
	`priv_root`.`title` AS `root_name`,
	`priv`.`position` AS `position`,
	`priv`.`is` AS `is`,
	`priv`.`uid` AS `uid`,
	`priv`.`nav` AS `nav`,
	`priv`.`permit` AS `permit`
FROM (
	(
		(
			SELECT
				`n`.`id` AS `id`,
				`n`.`title` AS `title`,
				`n`.`icon` AS `icon`,
				`n`.`query_string` AS `query_string`,
				`n`.`root` AS `root`,
				`n`.`position` AS `position`,
				`n`.`is` AS `is`,
				`p`.`uid` AS `uid`,
				`p`.`nav` AS `nav`,
				`p`.`permit` AS `permit`
			FROM (
				`sys_nav` `n`
				JOIN
				`dropship_db`.`sys_privilege` `p`
				ON(
					(`n`.`id` = `p`.`nav`)
					)
				)
			)
		UNION
		SELECT
			`sys_nav`.`id` AS `id`,
			`sys_nav`.`title` AS `title`,
			`sys_nav`.`icon` AS `icon`,
			`sys_nav`.`query_string` AS `query_string`,
			`sys_nav`.`root` AS `root`,
			`sys_nav`.`position` AS `position`,
			`sys_nav`.`is` AS `is`,
			'0' AS `uid`,'0' AS `nav`,
			'1' AS `permit`
		FROM `sys_nav`
		WHERE (
			(`sys_nav`.`is` = 1)
			OR
			(`sys_nav`.`is` = 11)
			)
		) `priv` LEFT JOIN `sys_nav` `priv_root` ON(
			(
			`priv`.`root` = `priv_root`.`id`
			)
		)
	)