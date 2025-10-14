pipeline {
	agent {
		docker {
            image 'php:8.2'
        }
	}
	environment {
		TAG_ID = env.BUILD_ID
	}
	stages{
		stage("build and test"){
			steps{
				script{
					sh '''
						php -S localhost:8081
						curl localhost:8081/health | grep '"status":"ok"'
					'''
				}
			}
		}
	}
}