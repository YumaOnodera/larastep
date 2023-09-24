include .env

PROJECT_ENV=stg

pre:
ifdef e
PROJECT_ENV=${e}
endif

gcloud-create-project: pre
	gcloud projects create ${APP_NAME}-${PROJECT_ENV} ;

gcloud-create-repository: pre
	gcloud artifacts repositories create cloud-run-source-deploy \
		--repository-format=docker \
		--location=${GCP_REGION} ;

deploy-setup: pre
	gcloud config set project ${APP_NAME}-${PROJECT_ENV} ;

deploy-app: pre
	@make deploy-setup ;\
	gcloud builds submit --config=cloudbuild.yaml \
		--substitutions=_PROJECT_ENV="${PROJECT_ENV}",_LOCATION="${GCP_REGION}",_REPOSITORY="${GCP_REPOSITORY}",_IMAGE=${APP_NAME},_DOCKER_FILE=Dockerfile_php;\
	gcloud run deploy ${APP_NAME} \
		--image ${GCP_REGION}-docker.pkg.dev/${APP_NAME}-${PROJECT_ENV}/${GCP_REPOSITORY}/${APP_NAME} \
		--region ${GCP_REGION} ;

deploy-meilisearch: pre
	@make deploy-setup ;\
	gcloud builds submit --config=cloudbuild.yaml \
		--substitutions=_PROJECT_ENV="${PROJECT_ENV}",_LOCATION="${GCP_REGION}",_REPOSITORY="${GCP_REPOSITORY}",_IMAGE=meilisearch,_DOCKER_FILE=Dockerfile_meilisearch;\
	gcloud run deploy meilisearch \
		--port 7700 \
		--image ${GCP_REGION}-docker.pkg.dev/${APP_NAME}-${PROJECT_ENV}/${GCP_REPOSITORY}/meilisearch \
		--region ${GCP_REGION} ;
