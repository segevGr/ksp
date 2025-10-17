pipeline{
	agent any
	environment{
		APP_NAME = "ksp-app"
		TAG_ID = "${BUILD_NUMBER}"
		HOST_NETWORK = "jenkins-network"
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
	}
	post {
		always {
			sh """
				docker stop ${APP_NAME} || true
				docker rm ${APP_NAME} || true
				docker rmi -f ${APP_NAME}:${TAG_ID} || true
			"""
		}
	}
}