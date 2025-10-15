pipeline {
	agent any
	environment {
		TAG_ID = ${env.BUILD_ID}
		NETWORK = "jenkins-network"
		APP_NAME = "ksp-app"
	}
	stages{
		// stage("build"){
		// 	steps{
		// 		script{
		// 			sh '''
		// 				php -S localhost:8081
		// 				curl localhost:8081/health | grep '"status":"ok"'
		// 			'''
		// 		}
		// 	}
		// }
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
						curl http://${APP_NAME}:8081 | grep '"status":"ok"'
					"""
				}
			}
		}
	}
}