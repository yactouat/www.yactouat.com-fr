# www.yactouat.com-fr

<!-- TOC -->

- [www.yactouat.com-fr](#wwwyactouatcom-fr)
    - [what is this ?](#what-is-this-)
    - [prerequisites](#prerequisites)
    - [how to run](#how-to-run)
    - [test the app'](#test-the-app)
        - [automated](#automated)
        - [QA](#qa)
    - [Documentation](#documentation)
        - [PHP code](#php-code)
            - [generate the docs](#generate-the-docs)
            - [view the docs](#view-the-docs)
    - [CI/CD](#cicd)
        - [locally](#locally)
    - [Deployment](#deployment)
        - [first deployment steps](#first-deployment-steps)
        - [manual deployment flow: from Docker image to Cloud Run](#manual-deployment-flow-from-docker-image-to-cloud-run)
            - [build and push the backend API image to Google Cloud Artifact Registry](#build-and-push-the-backend-api-image-to-google-cloud-artifact-registry)
                - [for PHP](#for-php)
            - [deploy the image on Cloud Run](#deploy-the-image-on-cloud-run)
                - [a live staging env out of the box](#a-live-staging-env-out-of-the-box)
        - [automatic deployment](#automatic-deployment)

<!-- /TOC -->

## what is this ?

my personal website take #999999999999, app' may be in French or in English, hence the name

## prerequisites

- Linux
- have PHP in your path
- have Docker installed on your machine

## how to run

`docker compose up`

## test the app'

### automated

- `docker compose up` (if application stack not already running)
- `docker exec -t wwwyactouatdotcom-app-1 bash -c "/var/www/html/vendor/bin/phpunit /var/www/html/tests --testdox --colors"`

### QA

- no conf error message on going to `/`
- go to `/docs` and check documentation for any class

## Documentation

### PHP code

#### generate the docs

- we use [phpDocumentor](https://www.phpdoc.org/) and it's [PHAR executable](https://phpdoc.org/phpDocumentor.phar)
- make sure you have downloaded the PHAR provided in the link above
- to generate the documentation, just run => `php phpDocumentor.phar`
- to get a feel at how to write PHP doc blocks, check out =>
  - <https://docs.phpdoc.org/3.0/guide/getting-started/what-is-a-docblock.html>
  - <https://docs.phpdoc.org/3.0/guide/guides/docblocks.html>
  - <https://docs.phpdoc.org/3.0/guide/guides/types.html>
- the documentation configuration is described in `phpdoc.dist.xml`

#### view the docs

on any environment/deployment, view the docs at `/docs`

## CI/CD

### locally

- application stack must be up with a `docker compose up`
- `composer install --ignore-platform-reqs` to execute post deps install script that will copy the pre commit hook that includes tests in the `.git` folder (and also to benefit from code intellisense in your IDE); also, if you modify a hook in the `hooks` folder, dont forget to re run a composer install so it's re copied in the `.git` folder
- `git add . && git commit -m "initial commit"` =>
  - see the magic of a local testing pipeline happening !
  - see the PHP documentation generated in `./public/docs`

## Deployment

we use GCP Cloud Run to deploy this app'

### first deployment steps

- install the `gcloud` CLI on your machine or run a `gcloud components update` to update your `gcloud` tools
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

### manual deployment flow: from Docker image to Cloud Run

#### build and push the backend API image to Google Cloud Artifact Registry

- build and tag the relevant Docker image locally, replacing the placeholders (without the `{}`, to replace with the data of your Google Cloud project) => `docker build -t {gCloudRegion}-docker.pkg.dev/{projectId}/{nameOfTheArtifactRegistryRepo}/{nameOfYourContainer}:{tag} -f ./docker/{languageFolder}/prod.Dockerfile .`
- push the images to the Artifact Registry, replacing the placeholders (without the `{}`, to replace with the data of your Google Cloud project) => `docker push {gCloudRegion}-docker.pkg.dev/{projectId}/{nameOfTheArtifactRegistryRepo}/{nameOfYourContainer}:{tag}`

##### for PHP

- make sure the `php.ci.Dockerfile` is in sync with `./docker/php/prod.Dockerfile`
- make sure the `composer.json.prod` is in sync with `composer.json` (minus unwanted scripts and dev dependencies)

#### deploy the image on Cloud Run

- to deploy from source, replacing the placeholders (without the `{}`, to replace with the data of your Google Cloud project) => `gcloud run deploy {serviceName-staging|prod} --image={gCloudRegion}-docker.pkg.dev/{projectId}/{nameOfTheArtifactRegistryRepo}/{nameOfYourContainer}:{tag} --port={portOfYourService}`
- if you have permissions issues because you are running this for the first time, just wait a few minutes and retry later
- when prompted, allow for unauthenticated invocations if it's a public API
- when app' deployed, the wizard should reveal the service URL that you can visit in your browser

##### a live staging env out of the box

when you deployed your Cloud Run instance, every revision that will make on it will have the same TLS protected URL out of the box ! I consider this URL to be a safe place to test stuff in real world conditions; for instance, you could deploy a different service for each locale so you can try internationalization without even having to point any domain to the real thing ;) add build triggers on top of that and you'll be able to do fairly complex stuff without the headaches ! this is why I like Google Cloud Platform so much :)

### automatic deployment

I thought I was about to read endless docs and writing countless YAML files in trial and error mode before setting up continuous deployment of the app'; surprisingly, it literally took the push of a button to do so =>

- go to your created Cloud Run service
- right below the main UI header you'll find a `SET UP CONTINUOUS DEPLOYMENT` button (it says `EDIT` in the pic because I already used it)
![create continuous deployment button](./public/docs/gcp/create_cd_btn.png)
- when you click on this, just follow the steps, which are:
  - selecting the branch(es) from where you want to trigger builds on push (this may prompt you to choose your GitHub providers and connect specific or all repos)
  - selecting the Dockerfile that you want to build, one limitation though is that the build context will be the folder where the Dockerfile lives, so you may need to create a CI Dockerfile at the root of the repo if your prod Dockerfile is nested somewhere...
- now, every time a commit is pushed to the triggering branch, it will be automatically deployed 🆒
- however, the deployed image wont be pushed to the Artifact Registry, as it was the case with manual deploys; but it is pushed to the Container Registry instead... still trying to figure out how to sync both registries 🤔
