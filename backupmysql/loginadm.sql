ALTER TABLE usuarios MODIFY COLUMN e_admin INT(1);

UPDATE usuarios SET e_admin = 1 WHERE id = <id_do_usuario>;
