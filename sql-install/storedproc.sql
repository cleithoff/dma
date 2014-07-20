DELIMITER $$
DROP PROCEDURE IF EXISTS generateLines $$
CREATE PROCEDURE generateLines()
BEGIN

	DECLARE done INT DEFAULT FALSE;

	DECLARE _partner_nr INT;
    DECLARE _value VARCHAR(255);

	DECLARE cursor_empty_lines CURSOR FOR 
		SELECT import_ordercompare.partner_nr, iol7.value FROM import_ordercompare 
			LEFT JOIN import_ordercompare AS iol7 ON iol7.partner_nr = import_ordercompare.partner_nr AND `iol7`.`key` = 'line7'
			WHERE import_ordercompare.`key` = 'partner_nr'
			GROUP BY import_ordercompare.partner_nr
			HAVING iol7.value IS NULL;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

	OPEN cursor_empty_lines;

  read_loop: LOOP
	FETCH cursor_empty_lines INTO _partner_nr, _value;
	IF done THEN
	  LEAVE read_loop;
	END IF;
    CALL generateLine(_partner_nr);
  END LOOP;

  CLOSE cursor_empty_lines;

END; $$

DELIMITER $$
DROP PROCEDURE IF EXISTS generateLine; $$
CREATE PROCEDURE generateLine(__partner_nr INT)
BEGIN

	DECLARE done INT DEFAULT FALSE;

    DECLARE _partner_nr INT;
    DECLARE _key VARCHAR(255);
    DECLARE _value VARCHAR(255);
    DECLARE _default VARCHAR(255);

    DECLARE _active_line INT DEFAULT 1;
    DECLARE _shift_line INT DEFAULT 7;

    DECLARE _druck_anrede_fuer_hg_ng,_druck_hg_name1,_druck_hg_name2,_druck_hg_strasse,_druck_hg_plz,_druck_hg_ort,_druck_hg_tel,
            _druck_ng_name1,_druck_ng_name2,_druck_ng_strasse,_druck_ng_plz,_druck_ng_ort,_druck_ng_tel VARCHAR(255);

    DECLARE _line VARCHAR(60);

	DECLARE cursor_properties CURSOR FOR 
		SELECT import_ordercompare.partner_nr, import_ordercompare.key, import_ordercompare.value FROM import_ordercompare 
			WHERE import_ordercompare.`partner_nr` = __partner_nr;

    DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;

	OPEN cursor_properties;

  read_loop: LOOP
	FETCH cursor_properties INTO _partner_nr, _key, _value;
	IF done THEN
	  LEAVE read_loop;
	END IF;
    CASE _key
        WHEN 'druck_anrede_fuer_hg_ng' THEN SET _druck_anrede_fuer_hg_ng = _value;
        WHEN 'druck_hg_name1' THEN SET _druck_hg_name1 = _value;
        WHEN 'druck_hg_name2' THEN SET _druck_hg_name2 = _value;
        WHEN 'druck_hg_strasse' THEN SET _druck_hg_strasse = _value;
        WHEN 'druck_hg_plz' THEN SET _druck_hg_plz = _value;
        WHEN 'druck_hg_ort' THEN SET _druck_hg_ort = _value;
        WHEN 'druck_hg_tel' THEN SET _druck_hg_tel = _value;
        WHEN 'druck_ng_name1' THEN SET _druck_ng_name1 = _value;
        WHEN 'druck_ng_name2' THEN SET _druck_ng_name2 = _value;
        WHEN 'druck_ng_strasse' THEN SET _druck_ng_strasse = _value;
        WHEN 'druck_ng_plz' THEN SET _druck_ng_plz = _value;
        WHEN 'druck_ng_ort' THEN SET _druck_ng_ort = _value;
        WHEN 'druck_ng_tel' THEN SET _druck_ng_tel = _value;
        ELSE SET _default = '';            
    END CASE;

  END LOOP;

  CLOSE cursor_properties;

  SET _line = REPLACE(_druck_anrede_fuer_hg_ng, ':', '');
  INSERT INTO import_ordercompare (`partner_nr`,`key`,`value`) VALUES (__partner_nr,CONCAT('line', _active_line), _line) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`);
  SET _active_line = 2;
  SET _line = CONCAT(CONCAT(_druck_hg_name1, ' ',_druck_hg_name2), ',');
  INSERT INTO import_ordercompare (`partner_nr`,`key`,`value`) VALUES (__partner_nr,CONCAT('line', _active_line), _line) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`);
  
  SET _active_line = 3;
  SET _line = CONCAT(_druck_hg_strasse, ', ');
  IF CHAR_LENGTH(_line) + CHAR_LENGTH(CONCAT(_druck_hg_plz, ' ', _druck_hg_ort, ', ')) < 60 THEN
     SET _line = CONCAT(_line, _druck_hg_plz, ' ', _druck_hg_ort, ', ');
  ELSE
     INSERT INTO import_ordercompare (`partner_nr`,`key`,`value`) VALUES (__partner_nr,CONCAT('line', _active_line), _line) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`);
     SET _line = CONCAT(_druck_hg_plz, ' ', _druck_hg_ort, ', ');
	 SET _active_line = _active_line + 1;
  END IF;

  IF CHAR_LENGTH(_line) + CHAR_LENGTH(CONCAT('Tel. ', REPLACE(_druck_hg_tel, '-', ' / '))) < 60 THEN
     SET _line = CONCAT(_line, 'Tel. ', REPLACE(_druck_hg_tel, '-', ' / '));
  ELSE
     INSERT INTO import_ordercompare (`partner_nr`,`key`,`value`) VALUES (__partner_nr,CONCAT('line', _active_line), _line) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`);
     SET _line = CONCAT('Tel. ', REPLACE(_druck_hg_tel, '-', ' / '));
	 SET _active_line = _active_line + 1;
  END IF;

  INSERT INTO import_ordercompare (`partner_nr`,`key`,`value`) VALUES (__partner_nr,CONCAT('line', _active_line), _line) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`);

  IF (_druck_ng_name1 IS NOT NULL) THEN
      SET _active_line = _active_line + 1;
      SET _line = CONCAT(_druck_ng_strasse, ', ');
	  IF CHAR_LENGTH(_line) + CHAR_LENGTH(CONCAT(_druck_ng_plz, ' ', _druck_ng_ort, ', ')) < 60 THEN
		 SET _line = CONCAT(_line, _druck_ng_plz, ' ', _druck_ng_ort, ', ');
	  ELSE
		 INSERT INTO import_ordercompare (`partner_nr`,`key`,`value`) VALUES (__partner_nr,CONCAT('line', _active_line), _line) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`);
		 SET _line = CONCAT(_druck_ng_plz, ' ', _druck_ng_ort, ', ');
		 SET _active_line = _active_line + 1;
	  END IF;

	  IF CHAR_LENGTH(_line) + CHAR_LENGTH(CONCAT('Tel. ', REPLACE(_druck_ng_tel, '-', ' / '))) < 60 THEN
		 SET _line = CONCAT(_line, 'Tel. ', REPLACE(_druck_ng_tel, '-', ' / '));
	  ELSE
		 INSERT INTO import_ordercompare (`partner_nr`,`key`,`value`) VALUES (__partner_nr,CONCAT('line', _active_line), _line) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`);
		 SET _line = CONCAT('Tel. ', REPLACE(_druck_ng_tel, '-', ' / '));
		 SET _active_line = _active_line + 1;
	  END IF;

	  INSERT INTO import_ordercompare (`partner_nr`,`key`,`value`) VALUES (__partner_nr,CONCAT('line', _active_line), _line) ON DUPLICATE KEY UPDATE `value` = VALUES(`value`);

  END IF;

  SET _shift_line = 7; 
  -- _shift_line - _active_line;

  shift_loop: LOOP
	IF _shift_line = 0 THEN
	  LEAVE shift_loop;
	END IF;

    IF (_shift_line - (7 -_active_line) > 0) THEN
        SELECT `value` INTO _line FROM import_ordercompare WHERE partner_nr = __partner_nr AND `key` = CONCAT('line', _shift_line - (7 -_active_line) ) LIMIT 1;
		INSERT INTO import_ordercompare (`partner_nr`,`key`,`value`) VALUES (__partner_nr, CONCAT('line', _shift_line), _line)
		ON DUPLICATE KEY UPDATE `value` = VALUES(`value`);
    ELSE
	    INSERT INTO import_ordercompare (`partner_nr`,`key`,`value`) VALUES (__partner_nr, CONCAT('line', _shift_line), '')
		ON DUPLICATE KEY UPDATE `value` = VALUES(`value`);
    END IF;

    SET _shift_line = _shift_line - 1;
  END LOOP;

END; $$