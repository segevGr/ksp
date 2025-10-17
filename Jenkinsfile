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
				docker {
					image "php:8.2-cli"
				}
			}
			steps{
				script{
						sh "find . -name '*.php' -print0 | xargs -0 -n1 php -l"
				}
			}
		}
		stage ("unit test") {
				agent {
				docker {
					image "php:8.2-cli"
				}
			}
			steps {
				script {
					dir("/app"){
						sh "php index.php | grep 'active_count' "
					}
				}
			}
		}
	}
}