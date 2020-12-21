### Despliegue

Crear nueva BBBD para el nuevo sistema

Habilitar la carga de archivos locales directamente en la BBDD: https://stackoverflow.com/questions/10762239/mysql-enable-load-data-local-infile

Clonar dentro de una misma carpeta los repositorios backoffice y registro-obras

En **registro-obras** copiar el archivo *.env.example* como *.env*

Modificar en *.env* los siguientes parámetros con los datos de conexión a la nueva BBDD:
- DB_HOST
- DB_PORT
- DB_DATABASE
- DB_USERNAME
- DB_PASSWORD
- DB_CHARSET
- DB_COLLATION

Modificar los siguientes parámetros con los datos de conexión a la BBDD del sitio actual de SADAIC:
- SADAIC_HOST
- SADAIC_PORT
- SADAIC_DATABASE
- SADAIC_USERNAME
- SADAIC_PASSWORD
- SADAIC_CHARSET
- SADAIC_COLLATION

Modificar los siguientes parámetros con los datos de conexión al servidor SMTP:
- MAIL_MAILER
- MAIL_HOST
- MAIL_PORT
- MAIL_USERNAME
- MAIL_PASSWORD
- MAIL_ENCRYPTION
- MAIL_FROM_ADDRESS

Modificar el parámetro SADAIC_HASH con el hash que se utiliza para generar claves en el ABM actual. Este dato está guardado en el archivo .htaccess en la raiz del sitio web

Modificar el parámetro SADAIC_WEB con la url para acceder al sitio web actual

En **backoffice** copiar el archivo *.env.example* como *.env*

Modificar en *.env* los siguientes parámetros con los datos de conexión a la nueva BBDD:
- DB_HOST
- DB_PORT
- DB_DATABASE
- DB_USERNAME
- DB_PASSWORD
- DB_CHARSET
- DB_COLLATION

Modificar los siguientes parámetros con los datos de conexión a la BBDD del sitio actual de SADAIC:
- SADAIC_HOST
- SADAIC_PORT
- SADAIC_DATABASE
- SADAIC_USERNAME
- SADAIC_PASSWORD
- SADAIC_CHARSET
- SADAIC_COLLATION

Modificar los siguientes parámetros con los datos de conexión al servidor SMTP:
- MAIL_MAILER
- MAIL_HOST
- MAIL_PORT
- MAIL_USERNAME
- MAIL_PASSWORD
- MAIL_ENCRYPTION
- MAIL_FROM_ADDRESS

Modificar el parámetro SADAIC_REGISTRY_LIFE_DAYS con la cantidad de días que tienen que transcurrir antes que una solicitud expire

En el archivo *docker-compose.prod.yml* configurar el volumen **user-files** para que apunte a la carpeta donde se van a guardar los archivos subidos por los usuarios del sistema

En el archivo *docker-compose.prod.yml* configurar el volumen **sync-files** para que apunte a la carpeta donde se van a guardar los archivos de sincronización y respuesta del sistema interno

Hacer el build inicial de los contenedores con docker-compose utilizando los archivos de configuración *docker-compose.yml* y *docker-compose.prod.yml*:
```
docker-compose -f docker-compose.yml -f docker-compose.prod.yml up
```

Realizar la creación y carga inicial de datos en la nueva BBDD utilizando la herramienta artisan:
```
docker-compose -f docker-compose.yml -f docker-compose.prod.yml exec php artisan db:reset
```

Realizar la carga inicial de datos de SADAIC copiando los archivos en la carpeta **input** dentro de la carpeta a la que apunta el volumen **sync-files** y ejecutando el comando:
```
docker-compose -f docker-compose.yml -f docker-compose.prod.yml exec php artisan sadaic:sync
```

Configurar en cron la ejecución una vez por día de la tarea encargada de controlar el vencimiento de los trámites:
```
docker-compose -f docker-compose.yml -f docker-compose.prod.yml exec php artisan sadaic:expiration
```


---

### Configuración de volúmenes
El seteo de los volúmenes externos a docker se debería hacer de la misma forma en que se hace con **../registro-obras/src/public** en *docker.compose.dev.yml* para el servicio *nginx_registro_obras*

Esta configuración se debe realizar en cada servicio y no de forma global

### Cambios en los archivos de configuración
Una vez que los contenedores estén corriendo, si se modifican parámetros dentro del archivo *.env*, hace falta detenerlos y volver a iniciarlos para que se apliquen los cambios:
```
docker-compose -f docker-compose.yml -f docker-compose.prod.yml down
docker-compose -f docker-compose.yml -f docker-compose.prod.yml up
```

### Cambios en el código
Cuando sea necesario aplicar cambios en el código es necesario generar nuevas imágenes para los contenedores, borrar los contenedores actuales e iniciar los nuevos:
```
docker-compose -f docker-compose.yml -f docker-compose.prod.yml build
docker-compose -f docker-compose.yml -f docker-compose.prod.yml down -v
docker-compose -f docker-compose.yml -f docker-compose.prod.yml up
```
