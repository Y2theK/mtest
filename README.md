### Installation

```shell
git clone https://github.com/Y2theK/mtest.git
```

```
cd toYourFolder
```

```shell
cp .env.example .env
```

```shell
touch database/datebase.sqlite
```

```shell
composer install
```

```shell
php artisan key:generate
```

```shell
php artisan migrate:fresh --seed
```

```shell
npm install && npm run build
```

```shell
php artisan serve
```


### Testing

```
php artisan test --filter=CompanyTest  
```

```
php artisan test --filter=EmployeeTest  
```
