SET NAMES utf8;
SET time_zone = '+00:00';
SET foreign_key_checks = 0;
SET sql_mode = 'NO_AUTO_VALUE_ON_ZERO';

SET NAMES utf8mb4;

USE www_sadaic;

UPDATE socios SET clave = '601fa408a634a5f58fdb4da801184f35f928071f066f0db88931a264e2e19e62632d762160b5d2785766f1c258a7dd41ff4bcaaec9cafb4b5a2ddf9f8661ec3f'
WHERE socio IN ('70588', '70948', '13383', '682750', '714970', '707933', '695112')
AND heredero = 0;