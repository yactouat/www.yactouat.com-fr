# www.yactouat.com-fr

[![GitHub Super-Linter](https://github.com/yactouat/www.yactouat.com-fr/workflows/Lint%20Code%20Base/badge.svg)](https://github.com/marketplace/actions/super-linter)

<!-- TOC -->

- [www.yactouat.com-fr](#wwwyactouatcom-fr)
    - [what is this ?](#what-is-this-)
    - [prerequisites](#prerequisites)
        - [gcloud CLI and GCP project setup](#gcloud-cli-and-gcp-project-setup)
    - [how to run](#how-to-run)
    - [tests and QA](#tests-and-qa)
        - [automated tests](#automated-tests)
        - [QA manual tests](#qa-manual-tests)
    - [debug the app'](#debug-the-app)
        - [VSCode](#vscode)
    - [Documentation](#documentation)
        - [PHP code](#php-code)
    - [CI/CD](#cicd)
        - [CI: locally](#ci-locally)
        - [CI: GitHub Actions](#ci-github-actions)
    - [CD: Deployment on Google Cloud Run](#cd-deployment-on-google-cloud-run)
        - [manual deployment flow: from Docker image to Cloud Run](#manual-deployment-flow-from-docker-image-to-cloud-run)
            - [build and push the backend API image to Google Cloud Artifact Registry](#build-and-push-the-backend-api-image-to-google-cloud-artifact-registry)
                - [PHP specifics](#php-specifics)
            - [deploy the image on Cloud Run](#deploy-the-image-on-cloud-run)
                - [a live deployment out of the box](#a-live-deployment-out-of-the-box)
        - [automatic deployment](#automatic-deployment)

<!-- /TOC -->

## what is this ?

my personal website take #999999999999, app' may be in French or in English, hence the name

## prerequisites

- Ubuntu or other Linux Deb OS (WSL is fine as well)
- [have PHP installed and in your path](https://tecadmin.net/how-to-install-php-on-ubuntu-22-04/)
- have `unzip` installed => `sudo apt install unzip`
- have `zip xml mbstring` extensions installed => `sudo apt -y install php-zip php-xml php8.1-mbstring`
- [have Composer installed and in your path](https://getcomposer.org/doc/00-intro.md#installation-linux-unix-macos)
- have Docker installed on your machine
- have the `gcloud` CLI installed on your machine, a GCP project, and a Docker repository set up

### `gcloud` CLI and GCP project setup

- `sudo apt update && sudo apt upgrade`
- [install](https://cloud.google.com/sdk/docs/install#deb) the `gcloud` CLI on your machine or run a `gcloud components update` to update your `gcloud` tools
- have a GCP project ready and make sure billing is enabled for it
- GCP APIs that must be enabled in your project (you can do this from your GCP browser console) =>
  - `Artifact Registry API`
  - `Cloud Build API`
  - `Compute Engine API`
  - `Container Analysis API`
- you may need to [connect your GCP identity/repo to GitHub](https://cloud.google.com/build/docs/automating-builds/github/connect-repo-github)
- initialize `gcloud` CLI => `gcloud init`
- then set the project ID =>  `gcloud config set project PROJECT_ID`
- set your default region, replacing the placeholders (without the `{}`, to replace with the relevant Google Cloud region, for instance `europe-west6`) => `gcloud config set run/region {gCloudRegion}`
- authenticate your local Docker install to Artifact Registry, replacing the placeholders (without the `{}`, to replace with the relevant Google Cloud region) => `gcloud auth configure-docker {gCloudRegion}-docker.pkg.dev`
- create a Docker repository in the artifact registry

## how to run

- `docker compose up`
- go to <http://localhost>

## tests and QA

### automated tests

- `docker compose up` (if application stack not already running)
- PHP tests => `docker exec -t wwwyactouatcom-fr-app-1 bash -c "/var/www/html/vendor/bin/phpunit /var/www/html/tests --testdox --colors"`

### QA manual tests

- `/` renders magazine landing page correctly
  - 200 status code
  - all assets are loaded
  - page displays nice on desktop with no apparent display bugs
- `/docs`
  - 200 status code
  - check documentation for at least 2 classes

## debug the app'

### VSCode

- you need to have `ms-vscode-remote.remote-containers` (and possibly `ms-vscode-remote.remote-wsl`) extensions installed
- in a VSCode window, open the command palette with a `ctlr + p` and start a command with a `>`
- run `Remote-Containers: Attach To Running Container`
- select the PHP container in the list containing all the containers of the application stack
- once VSCode is open inside the container, you can run the pre configured PHP debugger from within the UI and start to place breakpoints
- when a request hits the app', you'll be able to debug it !

## Documentation

### PHP code

- on any environment/deployment, view the docs at `/docs`
- we use [phpDocumentor](https://www.phpdoc.org/) and it's [PHAR executable](https://phpdoc.org/phpDocumentor.phar)
- to generate the documentation, just run => `php phpDocumentor.phar` (make sure you have downloaded the PHAR provided in the link above if you're doing this manually)
- to get a feel at how to write PHP doc blocks, check out =>
  - <https://docs.phpdoc.org/3.0/guide/getting-started/what-is-a-docblock.html>
  - <https://docs.phpdoc.org/3.0/guide/guides/docblocks.html>
  - <https://docs.phpdoc.org/3.0/guide/guides/types.html>
- the documentation configuration is described in `phpdoc.dist.xml`

## CI/CD

### CI: locally

- application stack must be up with a `docker compose up`
- `composer install --ignore-platform-reqs` to execute post deps install script that will copy the pre commit hook that includes tests in the `.git` folder (and also to benefit from code intellisense in your IDE); also, if you modify a hook in the `hooks` folder, dont forget to re run a composer install so the hook is updated
- `git add . && git commit -m "initial commit"` =>
  - see the linting happening
  - see the magic of a local testing pipeline happening !
  - see the PHP documentation generated in `./public/docs/php`
  - see whatever changes made by these operations get auto committed

### CI: GitHub Actions

There are GitHub actions workflows implemented in the `.github/workflows` folder for the CI part of the pipeline, these are:

- GitHub Action [super linting](https://github.com/marketplace/actions/super-linter)
  - runs on `pull_request` to `main`

## CD: Deployment on Google Cloud Run

We use GCP Cloud Run for the CD part of the pipeline.

### manual deployment flow: from Docker image to Cloud Run

manual deploys are useful during the development process, if you want to see the results live in dev or staging environment

#### build and push the backend API image to Google Cloud Artifact Registry

- build and tag the relevant Docker image locally, replacing the placeholders (without the `{}`, to replace with the data of your Google Cloud project) => `docker build -t {gCloudRegion}-docker.pkg.dev/{projectId}/{nameOfTheArtifactRegistryRepo}/{nameOfYourContainer}:{tag} -f ./docker/{languageFolder}/prod.Dockerfile .`
- push the images to the Artifact Registry, replacing the placeholders (without the `{}`, to replace with the data of your Google Cloud project) => `docker push {gCloudRegion}-docker.pkg.dev/{projectId}/{nameOfTheArtifactRegistryRepo}/{nameOfYourContainer}:{tag}`

##### PHP specifics

- make sure the `php.ci.Dockerfile` is in sync with `./docker/php/prod.Dockerfile`
- make sure the `composer.json.prod` is in sync with `composer.json` (minus unwanted scripts and dev dependencies)

#### deploy the image on Cloud Run

- to deploy from source, replacing the placeholders (without the `{}`, to replace with the data of your Google Cloud project) => `gcloud run deploy {serviceName-staging|prod} --image={gCloudRegion}-docker.pkg.dev/{projectId}/{nameOfTheArtifactRegistryRepo}/{nameOfYourContainer}:{tag} --port={portOfYourService}`
  - for instance, staging env Cloud Run service name is `wwwyactouatdotcom-staging`
  - prod env Cloud Run service name for FR version is `wwwyactouatdotfr-prod`
- if you have permissions issues because you are running this for the first time, just wait a few minutes and retry later
- when prompted, allow for unauthenticated invocations if it's a public API
- when app' deployed, the wizard should reveal the service URL that you can visit in your browser

##### a live deployment out of the box

When you deployed your Cloud Run instance, every revision that will make on it will have the same TLS protected URL out of the box ! This auto-generated URL is a safe place to test stuff in real world conditions.
For instance, you could deploy a different service for each locale so you can try internationalization without even having to point any domain to the real thing ;)
Add build triggers on top of that and you'll be able to do fairly complex stuff without the headaches ! this is why I like Google Cloud Platform so much :)

### automatic deployment

I thought I was about to read endless docs and writing countless YAML files in trial and error mode before setting up continuous deployment of the app'; surprisingly, it literally took the push of a button to do so =>

- go to your created Cloud Run service
- right below the main UI header you'll find a `SET UP CONTINUOUS DEPLOYMENT` button (it says `EDIT` in the pic because I already used it)
![create continuous deployment button](./public/docs/gcp/create_cd_btn.png)
- when you click on this, just follow the steps, which are:
  - selecting the branch(es) from where you want to trigger builds on push (this may prompt you to choose your GitHub providers and connect specific or all repos)
  - selecting the Dockerfile that you want to build, one limitation though is that the build context will be the folder where the Dockerfile lives, so you may need to create a CI Dockerfile at the root of the repo if your prod Dockerfile is nested somewhere... pretty cumbersome !
- now, every time a commit is pushed to the triggering branch, it will be automatically deployed 🆒
- in the case of this repo, it will be the `main` branch
- however, the deployed image wont be pushed to the Artifact Registry, as it was the case with manual deploys; but instead your Dockerfile is built and directly pushed to the Container Registry instead... still trying to figure out how to sync both registries 🤔
- that means if you manually deploy a new container image, it will be overridden the next time the Cloud Build trigger runs
