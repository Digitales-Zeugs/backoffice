## Backoffice

#### Configuración

##### Variables de ambiente (.env)

Según el ambiente, se requiere generar un archivo .env con las variables de ambiente. Se puede usar como template .env.example y luego editarlo.

```
$ cp .env.example .env
```

##### Estructura de directorios

Como también es necesario el proyecto registro-obras, deben ubicarse juntos bajo un mismo directorio.

```
|-- full-root-project/
|   |-- registro-obras/
|   |   |-- .env                 # Vars. ambiente producción registro-obras
|   |   |-- .env.testing         # Vars. ambiente testing registro-obras
|   |   |-- .env.development     # Vars. ambiente desarrollo registro-obras
|   |-- backoffice/
|   |   |-- .env                 # Vars. ambiente producción backoffice
|   |   |-- .env.testing         # Vars. ambiente producción testing
|   |   |-- .env.development     # Vars. ambiente producción development
|   |   |--  docker-compose*
```

Se debe trabajar con **docker-compose** desde el **directorio backoffice**.

```
backoficce/ $ docker-compose (...)
```

#### Generar una imágen de docker

Se disparará la ejecución del proceso de contrucción de una imágen docker luego del evento _push-tag_ cuyo identificador termine en "-build".

Ejemplo:

```
backoffice/ (branch: 1.1.0)$ git push --tag 1.1.0-build 
```

Generará la imágen: _registry.gitlab.com/qkstudio/sadaic/backoffice:1.1.0_

#### Ejecución del ambiente de producción

- Imágen: _registry.gitlab.com/qkstudio/sadaic/backoffice:latest_

- Variables de entorno (Fuera del SCM):  

    - backoffice/.env 
    
    - registro-obras/.env

- Fuentes: dentro de la imágen.

```
backoffice/ $ docker-compose -f docker-compose.yml -up -d 
```

o también

```
backoffice/ $ docker-compose --env-file=env/prod -up -d 
```

#### Ejecución del ambiente de testing

- Imágen: _registry.gitlab.com/qkstudio/sadaic/backoffice:testing_  

- Variables de entorno: 

    - backoffice/.env.testing
    
    - registro-obras/.env.testing

- Fuentes: dentro de la imágen.

- Instanciará mailhog y adminer.

```
backoffice/ $ docker-compose -f docker-compose.yml -f docker-compose.test.yml -up -d 
```

o también

```
backoffice/ $ docker-compose --env-file=env/test -up -d 
```

#### Ejecución del ambiente de desarrollo

- Imágen: _registry.gitlab.com/qkstudio/sadaic/backoffice:develop_ (sí existe, hará el build en caso contrario).

- Variables de entorno: 

    - backoffice/.env.development
    
    - registro-obras/.env.development

- Fuentes: local (a pesar del build que agrega herramientas y demás los fuentes que utiliza son los locales)

- Instanciará mailhog y adminer.

```
backoffice/ $ docker-compose -f docker-compose.yml -f docker-compose.dev.yml -up -d 
```

o también

```
backoffice/ $ docker-compose --env-file=env/dev -up -d 
```

Es posible forzar el build de la siguiente manera:

```
backoffice/ $ docker-compose -f docker-compose.yml -f docker-compose.dev.yml -up --build -d 
```

o análogamente

```
backoffice/ $ docker-compose --env-file=env/dev -up --build -d 
```

Como los fuentes que utiliza son los locales, es necesario instalar las dependencias de php con composer y las de node con npm.

```
backoffice/ $ docker-compose --env-file=env/dev exec php_backoffice composer install
backoffice/ $ docker-compose --env-file=env/dev exec php_registro_obras composer install
backoffice/ $ docker-compose --env-file=env/dev exec node_backoffice npm install
backoffice/ $ docker-compose --env-file=env/dev exec node_backoffice npm run development
backoffice/ $ docker-compose --env-file=env/dev exec node_registro_obras npm install
backoffice/ $ docker-compose --env-file=env/dev exec node_registro_obras npm run development
```