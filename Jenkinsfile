pipeline {
  agent {
    docker {
      image 'ittools/common-jenkins'
    }

  }
  stages {
    stage('Checkout') {
      steps {
        git(url: 'https://github.com/jpyzio/docker-jenkins-api-example.git', branch: 'master', changelog: true)
      }
    }

    stage('Prepare') {
      steps {
        sh 'composer install'
        sh 'bin/console assets:install'
        sh 'bin/console cache:clear'
      }
    }

  }
}