pipeline{
	agent any
	environment{
		APP_NAME = "ksp-app"
		TAG_ID = "${BUILD_NUMBER}"
		HOST_NETWORK = "jenkins-network"
		DOCKER_HUB_USER = "segev126"
		DOCKER_HUB_TOKEN = credentials('DOCKER_HUB_TOKEN')
	}
	options {
        timeout(time: 1, unit: 'HOURS') 
    }
	stages {
		stage ("linter"){
			agent {
				docker {
					image "php:8.2-cli"
				}
			}
			steps{
				sh "find . -name '*.php' -print0 | xargs -0 -n1 php -l"
			}
		}
		stage ("unit test") {
				agent {
				docker {
					image "php:8.2-cli"
				}
			}
			steps {
				dir("app"){
					sh "php index.php | grep 'active_count' "
				}
			}
		}
		stage ("package") {
			steps {
				sh "docker build -t ${APP_NAME}:${TAG_ID} ."
			}
		}
		stage ("e2e testing") {
			steps {
				sh """
					docker network ls | grep ${HOST_NETWORK}
					docker run -d --rm --network ${HOST_NETWORK} --name ${APP_NAME} ${APP_NAME}:${TAG_ID}
					
				"""
				retry(3) {
					sh """
						sleep 10
						curl ${APP_NAME}:80 | grep 'active_count'
					"""
				}
			}
		}
		stage ("publish") {
			steps {
				sh """
					echo "$DOCKER_HUB_TOKEN" | docker login --username=${DOCKER_HUB_USER} --password-stdin
					docker tag ${APP_NAME}:${TAG_ID} ${DOCKER_HUB_USER}/${APP_NAME}:${TAG_ID}
					docker push ${DOCKER_HUB_USER}/${APP_NAME}:${TAG_ID}
				"""
			}
		}
		stage ("deploy"){
			steps {
				dir("k8s"){
					sh """
						sed -i "s|IMAGE_PROPS/${DOCKER_HUB_USER}/${APP_NAME}:${TAG_ID}|g" deployment.yaml
						kubectl apply -f deployment.yaml
						kubectl rollout status deployment/ksp-app
					"""
				}
			}
		}
	}
	post {
		always {
			sh """
				docker stop ${env.APP_NAME} || true
				docker rm ${env.APP_NAME} || true
				docker rmi -f ${env.APP_NAME}:${env.TAG_ID} || true
				docker rmi -f ${env.DOCKER_HUB_USER}/${env.APP_NAME}:${env.TAG_ID}} || true
			"""
		}
	}
}