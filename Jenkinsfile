pipeline {
    agent { label 'docker' }
    triggers {
        bitbucketPush ( )
    }
    environment {
        // Określ zmienne środowiskowe .
        APP_VERSION = '1'
    }
    stage {
        stage ( 'Build' ) {
            steps {
                // Wydrukuj wszystkie zmienne środowiskowe .
                sh 'printenv'
                sh 'echo $ GIT_BRANCH'
                sh'echo $ GIT_COMMIT'
                echo 'Zainstaluj pakiety non-dev composer i przetestuj pamięć podręczną symfony wyczyść'
                sh 'docker-compose -f build.yml up --exit-code-from fpm_build --remove-orphans fpm_build'
                echo 'Tworzenie obrazy dockera z aktualną kompilacją dokera git commit '
                sh ' -f Dockerfile-php-production -t register.example.com/symfony_project_fpm:$GIT_COMMIT. '
                sh 'docker build -f Dockerfile-nginx -t register.example.com/symfony_project_nginx:$GIT_COMMIT.'
                sh 'docker build -f Dockerfile-db -t register.example.com/symfony_project_db:$GIT_COMMIT.'


( 'Test' ) {
            kroki {
                echo 'Testy jednostkowe PHP'
                sh 'docker-compose -f test.yml up -d --build --remove-orphans'
                sh 'sleep 5'
                sh 'docker-compose -f test. yml exec -T fpm_test bash build / php_unit.sh '
            }
        }
        stage ( ' Push ' ) {
            when {
                branch ' master '
            }
            steps {
                echo ' Deploying docker images '
                shTag doker registry.example.com/symfony_project_fpm:$GIT_COMMIT registry.example.com/symfony_project_fpm:$APP_VERSION '
                sh 'tag doker registry.example.com/symfony_project_fpm:$GIT_COMMIT registry.example.com/symfony_project_fpm:latest'
                sh ' rejestr docker push register.example.com/symfony_project_fpm:$APP_VERSION '
                sh ' docker push rejestru.example.com/symfony_project_fpm:latest '
                sh ' docker tag register.example.com/symfony_project_nginx:$GIT_COMMIT rejestr.example.com/symfony_project_project.com/symfony_project APP_VERSION '
                sh ' docker tag register.example.com/symfony_project_nginx:$GIT_COMMIT register.example.com/symfony_project_nginx :najnowszy'
                sh 'docker push rejestru.example.com/symfony_project_nginx:$APP_VERSION'
                sh 'docker push rejestru.example.com/symfony_project_nginx:
                najświeższy ' sh 'docker tag register.example.com/symfony_project_db:$GIT_COMMIT register.example.com/symfony_project_db:$GIT_COMMIT : $ APP_VERSION '
                sh 'tag doker registry.example.com/symfony_project_db:$GIT_COMMIT registry.example.com/symfony_project_db:latest'
                sh 'doker Push registry.example.com/symfony_project_db:$APP_VERSION'
                sh ' doker Push registry.example .com / symfony_project_db: najnowsze '
            }
        }
    }
    post {
        always {
            // Zawsze porządkuj po kompilacji .
            SH 'dokowanym-komponować -f build.yml dół'
            SH 'dokowanym-komponować -f test.yml dół'
            SH 'rm .env'
        }
    }
}