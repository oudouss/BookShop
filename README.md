Symfony Shop
==========

This repository contains the code for The BookShop (Admin + API) with Symfony.

Requirements
------------

- PHP 8.0.2+
- [Composer](https://getcomposer.org/download)
- [Symfony CLI](https://symfony.com/download) 
- [Docker & Docker compose](https://docs.docker.com/get-docker) or [XAMPP](https://www.apachefriends.org/fr/download.html)

Getting started
---------------

**Cloning the repository**

```
git clone https://github.com/oudouss/BookShop.git
cd BookShop
```

**Installing dependencies**

```
composer install
```

**Using Docker Compose**


```
docker-compose up -d
```
No need to create Database just run migrations

```
symfony console doctrine:migrations:migrate 
```

**Or If You Use Local Database like (XAMPP/WAWPP)**

Create Database and run migrations
```
symfony console doctrine:database:create
```
```
symfony console doctrine:migrations:migrate 
```

#### Generate the SSL keys:

```
symfony console lexik:jwt:generate-keypair
```

Your keys will land in `config/jwt/private.pem` and `config/jwt/public.pem` (unless you configured a different path).

Available options: 
- `--skip-if-exists` will silently do nothing if keys already exist.
- `--overwrite` will overwrite your keys if they already exist.

Otherwise, an error will be raised to prevent you from overwriting your keys accidentally.

**Loading fake Data**

```
symfony console doctrine:fixtures:load --no-interaction
```

**test Users**
- User  `user@app.com/password`
- Admin `admin@app.com/password`



**Activate TLS support on the Local Web Server**

```
symfony.exe server:ca:install
```

**Launching the Local Web Server**

```
symfony server:start -d
```

- The server started on the port 8000. Open the website <https://127.0.0.1:8000> in a browser.
- Go to Api Docs. Open the website <https://127.0.0.1:8000/api> in a browser.

