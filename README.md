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
    - [CI/CD](#cicd)
        - [CI: locally](#ci-locally)
        - [CI: GitHub Actions](#ci-github-actions)
        - [CD: Deployment on Google Cloud Run](#cd-deployment-on-google-cloud-run)
            - [manual deployment flow: from Docker image to Cloud Run](#manual-deployment-flow-from-docker-image-to-cloud-run)
                - [build and push the backend API image to Google Cloud Artifact Registry](#build-and-push-the-backend-api-image-to-google-cloud-artifact-registry)
                    - [PHP specifics](#php-specifics)
                - [deploy the image on Cloud Run](#deploy-the-image-on-cloud-run)
                    - [a live deployment out of the box](#a-live-deployment-out-of-the-box)
            - [automatic deployment flow](#automatic-deployment-flow)
    - [mapping a domain to your Cloud Run service](#mapping-a-domain-to-your-cloud-run-service)

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
- having bought a domain name, preferably on Google Domains

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

- `composer install --ignore-platform-reqs`
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

## CI/CD

### CI: locally

- application stack must be up with a `docker compose up`
- `composer install --ignore-platform-reqs` to execute post deps install script that will copy the pre commit hook that includes tests in the `.git` folder (and also to benefit from code intellisense in your IDE); also, if you modify a hook in the `hooks` folder, dont forget to re run a composer install so the hook is updated
- `git add . && git commit -m "initial commit"` =>
  - see the linting happening
  - see the magic of a local testing pipeline happening !
  - see whatever changes made by these operations get auto committed

### CI: GitHub Actions

There are GitHub actions workflows implemented in the `.github/workflows` folder for the CI part of the pipeline, these are:

- GitHub Action [super linting](https://github.com/marketplace/actions/super-linter)
  - runs on `pull_request` to `main`

### CD: Deployment on Google Cloud Run

We use GCP Cloud Run for the CD part of the pipeline.

#### manual deployment flow: from Docker image to Cloud Run

manual deploys are useful during the development process, if you want to see the results live in dev or staging environment

##### build and push the backend API image to Google Cloud Artifact Registry

- build and tag the relevant Docker image locally, replacing the placeholders (without the `{}`, to replace with the data of your Google Cloud project) => `docker build -t {gCloudRegion}-docker.pkg.dev/{projectId}/{nameOfTheArtifactRegistryRepo}/{nameOfYourContainer}:{tag} -f ./docker/{languageFolder}/prod.Dockerfile .`
- push the images to the Artifact Registry, replacing the placeholders (without the `{}`, to replace with the data of your Google Cloud project) => `docker push {gCloudRegion}-docker.pkg.dev/{projectId}/{nameOfTheArtifactRegistryRepo}/{nameOfYourContainer}:{tag}`

###### PHP specifics

- make sure the `composer.json.prod` is in sync with `composer.json` (minus unwanted scripts and dev dependencies)

##### deploy the image on Cloud Run

- to deploy from source, replacing the placeholders (without the `{}`, to replace with the data of your Google Cloud project) => `gcloud run deploy {serviceName-staging|prod} --image={gCloudRegion}-docker.pkg.dev/{projectId}/{nameOfTheArtifactRegistryRepo}/{nameOfYourContainer}:{tag} --port={portOfYourService}`
- for instance, staging env Cloud Run service name can be `wwwyactouatdotfr-staging`, or prod env Cloud Run service name for FR version can be `wwwyactouatdotfr-prod`
- if you have permissions issues because you are running this for the first time, just wait a few minutes and retry later
- when prompted, allow for unauthenticated invocations if it's a public API
- when app' deployed, the wizard should reveal the service URL that you can visit in your browser

###### a live deployment out of the box

When you deployed your Cloud Run instance, every revision that will make on it will have the same TLS protected URL out of the box ! This auto-generated URL is a safe place to test stuff in real world conditions, even in staging.
For instance, you could deploy a different service for each locale so you can try internationalization without even having to point any domain to the real thing ;)
Add build triggers on top of that and you'll be able to do fairly complex stuff without the headaches ! this is why I like Google Cloud Platform so much :)

#### automatic deployment flow

There are two build triggers pre configured in the GCP UI =>

- one for prod, that is run on tagging the main branch; tags must follow the pattern `^v(\d+)\.(\d+)\.(\d+)$`
  - to push a tag => `git tag tag_name && git push origin tag_name`
- one for staging, that runs for each commit that is not on the main branch; as I work alone I allow myself to do this for now, but if you're using this repo as a template, you may want to consider a unique staging branch and restrict the trigger to that branch
- cloud build steps are specified in the `gcp` folder
- in my project, all images are sent to GCP's Artifact Registry and not Container Registry

## mapping a domain to your Cloud Run service

Mapping a domain is pretty straightforward:

- go to your list of Cloud Runs services
- right below the main UI header you'll find a `MANAGE CUSTOM DOMAINS` button
- on click, you arrive on the domain mappings section
- just click the `ADD MAPPING` domain and select the correct service and domain (don't use a subdomain if it's your first mapping)
- if domain mappings are not available for your region (that's the case for a lot of regions in Europe), check out <https://cloud.google.com/run/docs/mapping-custom-domains#limitations>
- you can also add a domain mapping for any subdomain
- follow instructions in <https://cloud.google.com/run/docs/mapping-custom-domains?_ga=2.169427362.-284760377.1664096993#dns_update>
- deploying to domain preferred method is the manual one
