pipeline {
	agent any
	environment {
		TAG_ID = "${env.BUILD_ID}"
		NETWORK = "jenkins-network"
		APP_NAME = "ksp-app"
		REPOSITORY_NAME = "segev126"
		DOCKER_HUB_TOKEN = credentials('DOCKER_HUB_TOKEN')
	}
	stages{
		stage("build and unit test"){
			agent {
				docker {
					image 'php:8.2-cli'
				}
			}
			steps{
				script{
					sh '''
						REQUEST_URI=/health php index.php | grep '"status":"ok"'
					'''
				}
			}
		}
		stage("package"){
			steps{
				script{
					sh """
						docker build -t ${APP_NAME}:${TAG_ID} .
						docker run -d -p 8081:80 --name ${APP_NAME} --network ${NETWORK} ${APP_NAME}:${TAG_ID}
					"""
				}
			}
		}
		stage("E2E"){
			steps{
				script{
					sh """
						curl http://${APP_NAME}:80/health | grep '"status":"ok"'
					"""
				}
			}
		}
		stage("publish") {
			steps{
				script{
					sh """
						echo "$DOCKER_HUB_TOKEN" | docker login -u "$REPOSITORY_NAME" --password-stdin
						docker tag ${APP_NAME}:${TAG_ID} ${REPOSITORY_NAME}/${APP_NAME}:${TAG_ID}
						docker push ${REPOSITORY_NAME}/${APP_NAME}:${TAG_ID}
					"""
				}
			}
		}
		stage("deploy") {
			steps{
				script{
					dir("k8n"){
						sh """
							sed "s/TAG_PLACEHOLDER/${TAG_ID}/g" deployment.yaml > deployment_temp.yaml
							kubectl apply -f deployment_temp.yaml 
							kubectl rollout status deployment/ksp-app
						"""
					}
				}
			}
		}
	}
	post {
		always{
			sh "docker rm -f ${APP_NAME} | true"
			sh "docker rmi -f ${APP_NAME}:${TAG_ID}"
		}
	}
}