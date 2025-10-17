pipeline{
	agent any
	environment{
		APP_NAME = "ksp-app"
		TAG_ID = "${BUILD_NUMBER}"
	}
	options {
        timeout(time: 1, unit: 'HOURS') 
    }
	stages {
		stage ("linter"){
			agent {
				docker{
					image: "php:8.2-cli"
				}
			}
			steps{
				script{
						sh "php -l /app"
				}
			}
		}
	}
}