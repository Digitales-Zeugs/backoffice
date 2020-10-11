## Backoffice

#### Configuración

##### Variables de ambiente (.env)

Se requiere generar un archivo .env con las variables de ambiente. Se puede usar como template .env.example y luego editarlo.

```
$ cp .env.example .env
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

- Variables de entorno: .env (Fuera del SCM)

- Fuentes: dentro de la imágen.

```
$ docker-compose -f docker-compose.yml -up -d 
```

o también

```
$ docker-compose --env-file=env/prod -up -d 
```

#### Ejecución del ambiente de testing

- Imágen: _registry.gitlab.com/qkstudio/sadaic/backoffice:testing_  

- Variables de entorno: .env.testing

- Fuentes: dentro de la imágen.

- Instanciará maildog y adminer.

```
$ docker-compose -f docker-compose.yml -f docker-compose.test.yml -up -d 
```

o también

```
$ docker-compose --env-file=env/test -up -d 
```

#### Ejecución del ambiente de desarrollo

- Imágen: _registry.gitlab.com/qkstudio/sadaic/backoffice:develop_ (sí existe, hará el build en caso contrario).

- Variables de entorno: .env.development

- Fuentes: local

- Instanciará maildog y adminer.

```
$ docker-compose -f docker-compose.yml -f docker-compose.dev.yml -up -d 
```

o también

```
$ docker-compose --env-file=env/dev -up -d 
```

Es posible forzar el build de la siguiente manera:

```
$ docker-compose -f docker-compose.yml -f docker-compose.dev.yml -up --build -d 
```

o analogamente

```
$ docker-compose --env-file=env/dev -up --build -d 
```
