pipeline {
	agent any
	environment {
		TEST = "testvar"
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