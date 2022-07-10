# Test task API

## How to deploy a project on a local machine

- add the following line to the file `/etc/hosts`:
```
127.0.0.1 test-task.local
```
- execute bash script to run project's containers
```
./test_task up -d
```
- go inside the php fpm container
```
docker exec -it docker_test-task-php-fpm_1 bash
```
- install all project dependencies
```
composer install
```
- register user
```
bin/console app:register-user admin@test-task.local admin
```
- go to browser and open the link below to see docs
```
http://test-task.service/api/docs
```
- send a request to the endpoint `/authentication_token` in order to get a token with which you can authenticate in the api
- add a Jwt token to every request you send to api using the Authorization header with value `Bearer <jwt-token>` or use the Authorization button on the documentation page

## How to test emails locally
Go to browser and open the link `http://127.0.0.1:1080/`

## PHP Code sniffer

Automatically executed before every git push

`./vendor/bin/phpcs`

## PSALM

Automatically executed before every git push
 
`./vendor/bin/psalm`

## PHPUnit

Automatically executed before every git push
 
`./vendor/bin/phpunit`

## Contributing
Read about [Conventional Commits](https://www.conventionalcommits.org/en/v1.0.0/)