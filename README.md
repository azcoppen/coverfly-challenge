# Coverfly Team Comment Visibility

## Solution

The issue here isn't so much about roles and permissions, but about showing and hiding (i.e. visibility).

Business rules:

 - Users must be able to create a team.
 - Teams must member roles/ranks.
 - A user can be a member of multiple teams at once.
 - Users without a team may only see their own comments.
 - Users in teams may see their own comments, and the comments of the members of their teams.
 - Team owners must be able to add and remove members.
 - A team owner must be able to see comments made by previous members after they've been removed.

## Setup

You will need Docker: `apt install docker-ce`.

1. Clone the repo.
2. `cd laradock/`
3. Create the docker setup with `cp env-example .env`
4. `docker-compose up -d nginx mariadb`
5. Check the containers are up with `docker-ps`
6. SSH into workspace container with `docker-compose exec workspace bash`
7. `composer -v install`
8. `npm run dev`
9. `yarn prod`
10. `php artisan migrate`
11. `php artisan db:seed`

**Very important**

Set your DB driver to the **container** (mariadb), not a host:

```
DB_CONNECTION=mysql
DB_HOST=mariadb
DB_PORT=3306
DB_DATABASE=default
DB_USERNAME=root
DB_PASSWORD=root
```

You should have 5 docker containers which look like this:

```
CONTAINER ID        IMAGE                COMMAND                  CREATED             STATUS              PORTS                                                                                                                            NAMES
aa65a734c032        laradock_nginx       "/docker-entrypoint.…"   20 hours ago        Up 6 hours          0.0.0.0:80-81->80-81/tcp, 0.0.0.0:443->443/tcp                                                                                   laradock_nginx_1
948e8e79d187        laradock_php-fpm     "docker-php-entrypoi…"   20 hours ago        Up 6 hours          9000/tcp                                                                                                                         laradock_php-fpm_1
fb2247ee3c5d        laradock_workspace   "/sbin/my_init"          20 hours ago        Up 6 hours          0.0.0.0:3000-3001->3000-3001/tcp, 0.0.0.0:4200->4200/tcp, 0.0.0.0:8080->8080/tcp, 0.0.0.0:2222->22/tcp, 0.0.0.0:8001->8000/tcp   laradock_workspace_1
73b89fdd6f9b        docker:19.03-dind    "dockerd-entrypoint.…"   20 hours ago        Up 6 hours          2375-2376/tcp                                                                                                                    laradock_docker-in-docker_1
c5137e24d955        laradock_mariadb     "docker-entrypoint.s…"   20 hours ago        Up 6 hours          0.0.0.0:3306->3306/tcp                                                                                                           laradock_mariadb_1

```

Visit `http://localhost/login` and use your coverfly email with the password `bundy`.

## Important Notes

An organisation, team, or dept are **all the same thing**. They are a grouping of users with some additional metadata. We want to be able to group more than just user objects, so we use a polymorphic structure: any model can be part of a group.

We cannot use a pivot table (groups_users) here for two reasons: a) soft deletes aren't available on a pivot, and b) attach/detach removes a record entirely. We need to be able to see the comments of a person who has historically been part of a group in the past. So we use an interstitial model which contains membership information.

## Technical notes

First step: don't write code we don't need to. We do whatever we can with packages: impersonate, comments, activity, JWT, OTP, etc.

Simplicity is the name of the game.

1. We can create any number of groups we want.
2. Any objects/models can have a *membership* of a group which contains specific information about the object's access (approval status, time-limits etc).
3. When an object is added to a group, a record is created.
4. When it is removed, the record is soft-deleted. This means their historical membership is still known.
5. If an object needs to be added, its soft delete is removed so it is restored.

You'll notice we're using the *repository pattern* here. Doing that allows us to cache results, but crucially, it allows us to **encapsulate the filtering logic into a criteria** applied to an injectable class loaded into the controller. That keeps our code clean.

We've simplified the ideas of roles here. They are denoted by a slug next to the membership record. We can easily substitute this for a role ID if we need to.

Anything can have comments. Their content is encrypted.

Users have a one-time pass secret and JWT tokens.
