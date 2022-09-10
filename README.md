# www.yactouat.com-fr

<!-- TOC -->

- [www.yactouat.com-fr](#wwwyactouatcom-fr)
    - [what is this ?](#what-is-this-)
    - [prerequisites](#prerequisites)
    - [how to run](#how-to-run)
    - [test the app'](#test-the-app)
        - [automated](#automated)
        - [QA](#qa)
    - [CI/CD](#cicd)
        - [locally](#locally)
    - [Deployment](#deployment)
        - [first deployment steps](#first-deployment-steps)
        - [deployment flow: from Docker image to Cloud Run](#deployment-flow-from-docker-image-to-cloud-run)
            - [build and push the backend API image to Google Cloud Artifact Registry](#build-and-push-the-backend-api-image-to-google-cloud-artifact-registry)
            - [deploy the image on Cloud Run](#deploy-the-image-on-cloud-run)

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

## CI/CD

### locally

- `composer install --ignore-platform-reqs` to execute post deps install script that will copy the pre commit hook that includes tests in the `.git` folder (and also to benefit from code intellisense in your IDE)
- `git add . && git commit -m "initial commit"` => see the magic of a local testing pipeline happening !

## Deployment

we use GCP Cloud Run to deploy this app'

### first deployment steps

- install the `gcloud` CLI on your machine or run a `gcloud components update` to update your `gcloud` tools
- have a GCP project ready and make sure billing is enabled for it
- GCP APIs that must be enabled in your project (you can do this from your GCP browser console) =>
  - `Artifact Registry API`
  - `Compute Engine API`
- initialize `gcloud` CLI => `gcloud init`
- then set the project ID =>  `gcloud config set project PROJECT_ID`
- set your default region, replacing the placeholders (without the `{}`, to replace with the relevant Google Cloud region, for instance `europe-west6`) => `gcloud config set run/region {gCloudRegion}`
- authenticate your local Docker install to Artifact Registry, replacing the placeholders (without the `{}`, to replace with the relevant Google Cloud region) => `gcloud auth configure-docker {gCloudRegion}-docker.pkg.dev`
- create a Docker repository in the artifact registry

### deployment flow: from Docker image to Cloud Run

#### build and push the backend API image to Google Cloud Artifact Registry

- build and tag the relevant Docker image locally, replacing the placeholders (without the `{}`, to replace with the data of your Google Cloud project) => `docker build -t {gCloudRegion}-docker.pkg.dev/{projectId}/{nameOfTheArtifactRegistryRepo}/{nameOfYourContainer}:{tag} -f ./docker/{languageFolder}/prod.Dockerfile .`
- push the images to the Artifact Registry, replacing the placeholders (without the `{}`, to replace with the data of your Google Cloud project) => `docker push {gCloudRegion}-docker.pkg.dev/{projectId}/{nameOfTheArtifactRegistryRepo}/{nameOfYourContainer}:{tag}`

#### deploy the image on Cloud Run

- to deploy from source, replacing the placeholders (without the `{}`, to replace with the data of your Google Cloud project) => `gcloud run deploy {serviceName-staging|prod} --image={gCloudRegion}-docker.pkg.dev/{projectId}/{nameOfTheArtifactRegistryRepo}/{nameOfYourContainer}:{tag} --port={portOfYourService}`
- if you have permissions issues because you are running this for the first time, just wait a few minutes and retry later
- when prompted, allow for unauthenticated invocations if it's a public API
- when app' deployed, the wizard should reveal the service URL that you can visit in your browser
