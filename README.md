# KratosUI

A WIP Projet about making a ory/kratos front-end application.

## Requirements

- php 7.4 (due to kratos php lib which is not compatible 8.x as
  of [today](https://github.com/ory/kratos-client-php/issues/3))
- node and yarn
- composer
- docker-compose
- free ports : 4433,4434,4436,4437,5001,800x
- Symfony cli for more user-friendly php dev server

## Getting started

clone the project then:

```shell
composer install  # install project dependencies
yarn # install frontend dependencies
docker-compose up -d # start kratos stack
```

Then to start to dev:

```shell
yarn dev #starts a viteJS watcher
symfony serve #starts a webserver on port 8000 (by default)
```

then go to [localhost:8000/auth/login](http://localhost:8000/auth/login) to start the workflow. Because some
redirections are not ok and some sessions as well, if you want to check if you are login, you can go to [/profile](http://localhost:8000/profile).


**This project is a Proof of concept. Keep in mind that this project is still a WIP**