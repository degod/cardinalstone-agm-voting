## INTRODUCTION

Part of the responsibilities of CardinalStone Registrars to its clients involves organizing and moderating of Annual General Meetings (AGM). During the AGM, shareholders may be required to cast their votes on certain matters that ensued during the meeting.

## THE CHALLENGE

We are developing a simple voting app which can be used by shareholders during the AGM according to the details below:

### Admin

▪ Admin user can login to create and manage shareholders allowed to vote per company/AGM
▪ Admin can enter the list of items to be voted on per AGM
▪ Admin can see total vote cast per item and generate useful reports and metrics

### Users/Shareholders

▪ Only allow authenticated users to vote
▪ User can see a list of items on the agenda he can vote on
▪ These items must have a button that the user can click on to vote.

### NB:

The number of shares owned by a user translates to the number of votes he can cast on an item.

For instance: a user with 1000 units of DANGCEM shares is limited to 1000 votes per item

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

-   To Login as "Super Admin":

    -   `U:  admin@cardinalstone.test`
    -   `P:  admin123`

-   To Login as any Shareholder:

    -   `U:  select from the list of users [under user management]`
    -   `P:  password`
