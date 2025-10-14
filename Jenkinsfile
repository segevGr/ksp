pipeline {
	agent any
	environment {
		TEST = "testvar"
	}
	stages{
		stage("test"){
			steps{
				script{
					sh 'echo hello man'
				}
			}
		}
	}
}