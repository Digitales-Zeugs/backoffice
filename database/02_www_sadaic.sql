SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

USE www_sadaic;

/* Login socios */
UPDATE socios SET clave = '601fa408a634a5f58fdb4da801184f35f928071f066f0db88931a264e2e19e62632d762160b5d2785766f1c258a7dd41ff4bcaaec9cafb4b5a2ddf9f8661ec3f'
WHERE socio IN ('70588', '70948', '13383', '682750', '714970', '707933', '695112')
AND heredero = 0;

/* Login usuarios bo */
INSERT INTO usuarios (email, usuarioid, clave, status, activacion)
SELECT 'roberto.vaccaro@qkstudio.com', 'rvaccaro', MD5('pruebas'), 0, NOW()
WHERE NOT EXISTS (
    SELECT usuarioid FROM usuarios WHERE usuarioid = 'rvaccaro'
) LIMIT 1;

INSERT INTO usuarios (email, usuarioid, clave, status, activacion)
SELECT 'leo.miaton@qkstudio.com', 'lmiaton', MD5('pruebas'), 0, NOW()
WHERE NOT EXISTS (
    SELECT usuarioid FROM usuarios WHERE usuarioid = 'lmiaton'
) LIMIT 1;

/* Permisos usuarios bo */
INSERT INTO usuarios_privilegios (recid_usuario, capitulo, privilegios)
SELECT 51, 'nb_login', 'lee'
WHERE NOT EXISTS (
    SELECT recid FROM usuarios_privilegios WHERE recid_usuario = 51 AND capitulo = 'nb_login' AND privilegios = 'lee'
) LIMIT 1;

INSERT INTO usuarios_privilegios (recid_usuario, capitulo, privilegios)
SELECT 51, 'nb_socios', 'lee'
WHERE NOT EXISTS (
    SELECT recid FROM usuarios_privilegios WHERE recid_usuario = 51 AND capitulo = 'nb_socios' AND privilegios = 'lee'
) LIMIT 1;

INSERT INTO usuarios_privilegios (recid_usuario, capitulo, privilegios)
SELECT 51, 'nb_obras', 'lee'
WHERE NOT EXISTS (
    SELECT recid FROM usuarios_privilegios WHERE recid_usuario = 51 AND capitulo = 'nb_obras' AND privilegios = 'lee'
) LIMIT 1;

INSERT INTO usuarios_privilegios (recid_usuario, capitulo, privilegios)
SELECT 51, 'nb_jingles', 'lee'
WHERE NOT EXISTS (
    SELECT recid FROM usuarios_privilegios WHERE recid_usuario = 51 AND capitulo = 'nb_jingles' AND privilegios = 'lee'
) LIMIT 1;


INSERT INTO usuarios_privilegios (recid_usuario, capitulo, privilegios)
SELECT 58, 'nb_login', 'lee'
WHERE NOT EXISTS (
    SELECT recid FROM usuarios_privilegios WHERE recid_usuario = 58 AND capitulo = 'nb_login' AND privilegios = 'lee'
) LIMIT 1;

INSERT INTO usuarios_privilegios (recid_usuario, capitulo, privilegios)
SELECT 58, 'nb_socios', 'carga'
WHERE NOT EXISTS (
    SELECT recid FROM usuarios_privilegios WHERE recid_usuario = 58 AND capitulo = 'nb_socios' AND privilegios = 'carga'
) LIMIT 1;

INSERT INTO usuarios_privilegios (recid_usuario, capitulo, privilegios)
SELECT 58, 'nb_obras', 'carga'
WHERE NOT EXISTS (
    SELECT recid FROM usuarios_privilegios WHERE recid_usuario = 58 AND capitulo = 'nb_obras' AND privilegios = 'carga'
) LIMIT 1;

INSERT INTO usuarios_privilegios (recid_usuario, capitulo, privilegios)
SELECT 58, 'nb_jingles', 'carga'
WHERE NOT EXISTS (
    SELECT recid FROM usuarios_privilegios WHERE recid_usuario = 58 AND capitulo = 'nb_jingles' AND privilegios = 'carga'
) LIMIT 1;

INSERT INTO usuarios_privilegios (recid_usuario, capitulo, privilegios)
SELECT 59, 'nb_login', 'lee'
WHERE NOT EXISTS (
    SELECT recid FROM usuarios_privilegios WHERE recid_usuario = 59 AND capitulo = 'nb_login' AND privilegios = 'lee'
) LIMIT 1;

INSERT INTO usuarios_privilegios (recid_usuario, capitulo, privilegios)
SELECT 59, 'nb_socios', 'homologa'
WHERE NOT EXISTS (
    SELECT recid FROM usuarios_privilegios WHERE recid_usuario = 59 AND capitulo = 'nb_socios' AND privilegios = 'homologa'
) LIMIT 1;

INSERT INTO usuarios_privilegios (recid_usuario, capitulo, privilegios)
SELECT 59, 'nb_obras', 'homologa'
WHERE NOT EXISTS (
    SELECT recid FROM usuarios_privilegios WHERE recid_usuario = 59 AND capitulo = 'nb_obras' AND privilegios = 'homologa'
) LIMIT 1;

INSERT INTO usuarios_privilegios (recid_usuario, capitulo, privilegios)
SELECT 59, 'nb_jingles', 'homologa'
WHERE NOT EXISTS (
    SELECT recid FROM usuarios_privilegios WHERE recid_usuario = 59 AND capitulo = 'nb_jingles' AND privilegios = 'homologa'
) LIMIT 1;