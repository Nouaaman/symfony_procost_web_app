# symfony_procost_nroudane
## Procost: User, task, and cost management web app - symfony school project
- Prenom: Nouaaman
- Email: nouaaman.r@outlook.com

### 1 - run command
```
docker install
```

### 1 - create database mysql

### 2 - create .env.local file :
DATABASE_URL="mysql://root@localhost/[database name here]?serverVersion=mariadb-10.4.24&charset=utf8mb4"


### 3 - create fixures (data):
run command :
```
php bin/console d:d:d --force && php bin/console d:d:c && php bin/console doctrine:migrations:diff && php bin/console doctrine:migrations:migrate && php bin/console doctrine:fixtures:load
```
