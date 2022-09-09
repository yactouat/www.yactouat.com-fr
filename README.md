# www.yactouat.com-fr

<!-- TOC -->

- [www.yactouat.com-fr](#wwwyactouatcom-fr)
    - [what is this ?](#what-is-this-)
    - [prerequisites](#prerequisites)
    - [how to run](#how-to-run)
    - [run the tests](#run-the-tests)
    - [CI/CD](#cicd)
        - [locally](#locally)

<!-- /TOC -->

## what is this ?

my personal website take #999999999999, app' may be in French or in English, hence the name

## prerequisites

- Linux
- have PHP in your path
- have Docker installed

## how to run

`docker compose up`

## run the tests

- `docker compose up` (if application stack not already running)
- `docker exec -t wwwyactouatdotcom-app-1 bash -c "/var/www/html/vendor/bin/phpunit /var/www/html/tests`

## CI/CD

### locally

- `composer install --ignore-platform-reqs` to execute post deps install script that will copy the pre commit hook that includes tests in the `.git` folder (and also to benefit from code intellisense in your IDE)
- `git add . && git commit -m "initial commit"` => see the magic of a local testing pipeline happening !
