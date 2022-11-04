/* Fix: Método de autenticación MySQL 8.0 no soportado */
alter user 'root'@'%' identified with mysql_native_password by 'toor';
flush privileges;