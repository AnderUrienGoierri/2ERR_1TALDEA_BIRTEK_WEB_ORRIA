CREATE 
    ALGORITHM = UNDEFINED 
    DEFINER = `root`@`localhost` 
    SQL SECURITY DEFINER
VIEW `birtek_db`.`bista_langileak_sailak` AS
    SELECT 
        `l`.`id_langilea` AS `id_langilea`,
        `l`.`izena` AS `izena`,
        `l`.`abizena` AS `abizena`,
        `l`.`nan` AS `nan`,
        `l`.`jaiotza_data` AS `jaiotza_data`,
        `h`.`izena` AS `herria`,
        `l`.`helbidea` AS `helbidea`,
        `l`.`posta_kodea` AS `posta_kodea`,
        `l`.`telefonoa` AS `telefonoa`,
        `l`.`emaila` AS `emaila`,
        `s`.`izena` AS `saila`,
        `s`.`kokapena` AS `saila_kokapena`
    FROM
        ((`birtek_db`.`langileak` `l`
        LEFT JOIN `birtek_db`.`langile_sailak` `s` ON ((`l`.`saila_id` = `s`.`id_saila`)))
        LEFT JOIN `birtek_db`.`herriak` `h` ON ((`l`.`herria_id` = `h`.`id_herria`)))