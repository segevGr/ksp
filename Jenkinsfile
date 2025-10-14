pipeline {
	agent any
	environment {
		TEST = "testvar"
	}
	stages{
		stage("test"){
			script{
				sh """
					echo hello man
				"""
			}
		}
	}
}