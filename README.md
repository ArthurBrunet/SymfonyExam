# Intallation

```
git clone https://github.com/ArthurBrunet/SymfonyExam.git
```

```
cd SymfonyExam
```

```
composer install
```

Crée un .env à la racine du projetet configuré le avec l'aide du .env.test

```
php bin/console doctrine:database:create
```

```
php bin/console doctrine:migrations:migrate
```

```
php bin/console doctrine:fixtures:load
```

``` 
php -S localhost:8000 -t public
```

Et voila ! un compte admin est présent pour le test de la route /api/film/create

/api/login  body:{"username":"admin","password":"admin"}
