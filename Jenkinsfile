pipeline {
  agent {
    docker {
      image 'ittools/common-jenkins'
    }

  }
  stages {
    stage('Prepare') {
      steps {
        sh 'composer install'
        sh 'bin/console assets:install'
        sh 'bin/console cache:clear'
      }
    }

    stage('Tests') {
      parallel {
        stage('PHP Syntax check') {
          steps {
            sh 'vendor/bin/parallel-lint --exclude vendor/ --exclude ./bin .'
          }
        }

        stage('Symfony Lint') {
          steps {
            sh 'bin/console lint:yaml src'
            sh 'bin/console lint:yaml tests'
            sh 'bin/console lint:twig src'
            sh 'bin/console lint:twig tests'
          }
        }

        stage('PHPUnit') {
          steps {
            sh 'bin/phpunit --coverage-html build/coverage --coverage-clover build/coverage/index.xml'
            publishHTML (target: [
              allowMissing: false,
              alwaysLinkToLastBuild: false,
              keepAll: true,
              reportDir: 'build/coverage',
              reportFiles: 'index.html',
              reportName: "Coverage Report"
            ])
          }
        }

        stage('Publish Clover') {
          steps {
            step([
              $class: 'CloverPublisher',
              cloverReportDir: 'build/coverage',
              cloverReportFileName: 'index.xml',
              healthyTarget: [methodCoverage: 70, conditionalCoverage: 80, statementCoverage: 80], // optional, default is: method=70, conditional=80, statement=80
              unhealthyTarget: [methodCoverage: 50, conditionalCoverage: 50, statementCoverage: 50], // optional, default is none
              failingTarget: [methodCoverage: 0, conditionalCoverage: 0, statementCoverage: 0]     // optional, default is none
            ])
          }
        }

        stage('Checkstyle Report') {
          steps {
            sh 'vendor/bin/phpcs --report=checkstyle --report-file=build/logs/checkstyle.xml --standard=phpcs.xml.dist --extensions=php,inc -wp'
            checkstyle(pattern: 'build/logs/checkstyle.xml')
          }
        }

        stage('Mess Detection Report') {
          steps {
            sh 'vendor/bin/phpmd src/ xml phpmd.xml --reportfile build/logs/pmd.xml --exclude vendor/'
            pmd(canRunOnFailed: true, pattern: 'build/logs/pmd.xml')
          }
        }

        stage('CPD Report') {
          steps {
            sh 'vendor/bin/phpcpd --log-pmd build/logs/pmd-cpd.xml --exclude bin --exclude vendor --exclude src/Migrations --exclude var . --progress'
            dry canRunOnFailed: true, pattern: 'build/logs/pmd-cpd.xml'
          }
        }

        stage('Lines of Code') {
          steps {
            sh 'vendor/bin/phploc --count-tests --log-csv build/logs/phploc.csv --log-xml build/logs/phploc.xml . --exclude vendor --exclude src/Migrations --exclude var .'
          }
        }

        stage('Software metrics') {
          steps {
            sh 'vendor/bin/pdepend --jdepend-xml=build/logs/jdepend.xml --jdepend-chart=build/dependencies.svg --overview-pyramid=build/overview-pyramid.svg --ignore=vendor,var,bin,build .'
          }
        }

        stage('Generate documentation') {
          steps {
            sh 'vendor/bin/phpdox -f phpdox.xml'
          }
        }

      }
    }

    stage('Documentation') {
      steps {
        sh 'vendor/bin/phpdox -f phpdox.xml'
        publishHTML (target: [
          allowMissing: false,
          alwaysLinkToLastBuild: false,
          keepAll: true,
          reportDir: 'docs/html',
          reportFiles: 'index.xhtml',
          reportName: "PHPDox Documentation"
        ])
      }
    }

    stage('Deploy') {
      steps {
        echo 'Fire!'
      }
    }

  }
}