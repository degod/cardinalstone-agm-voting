## PRE-REQUISITE FOR SETUP

-   Docker desktop
-   Web browser
-   Terminal (git bash)

## HOW TO SETUP

-   Make sure your docker desktop is up and running
-   Launch you terminal and navigate to your working directory

```bash
cd ./working_dir
```

-   Clone repository

```bash
git clone https://github.com/degod/cardinalstone-agm-voting.git
```

-   Move into the project directory

```bash
cd cardinalstone-agm-voting/
```

-   Copy env.example into .env

```bash
cp .env.example .env
```

-   Build app using docker

```bash
docker compose up -d --build
```

-   Log in to docker container bash

```bash
docker compose exec app bash
```

-   Install composer

```bash
composer install
```

-   Create an application key

```bash
php artisan key:generate
```

-   Run database migration and seeder

```bash
php artisan migrate:fresh --seed
```

-   To access application, visit
    `http://localhost:9190`

-   To access application's database, visit
    `http://localhost:9191`

-   To access application's mailhost, visit
    `http://localhost:8025`
