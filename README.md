## API-DDD Test

#### Docker
```
Собрать и запустить образ: docker-compose up -d --build
Остановить образ: docker-compose down --remove-orphans

Удалить и очистить: docker system prune --all --volumes --force
```

## Composer
```
composer update
```

## Doctrine
#### Credentials DB
```
User: ddd_user
Password: ddd_passw
Database: ddd_db
Host: localhost:5433 - for local connection
```

#### To run that SQL, execute your migrations:
```
php bin/console doctrine:migrations:migrate
```

## Unit Tests
```
php bin/phpunit
```

## API

### Register new user
PUT http://localhost:120/api/v1/user/register
```json
{
    "name": "Petr Pavel",
    "age": 35,
    "region": "OS",
    "income": 1500,
    "score": 600,
    "pin": "123-45-6789",
    "email": "petr.pavel@example.com",
    "phone": "+420123456789"
}
```
#### Response
```json
{
    "success": true
}
```
```json
{
    "error": {
        "code": 409,
        "message": "User with this email or phone already exists.",
        "fields": null
    }
}
```


### Add new Loan
PUT http://localhost:120/api/v1/loan/add
```json
{
    "name": "Personal Loan",
    "amount": 1000,
    "rate": "10%",
    "start_date": "2025-01-01",
    "end_date": "2025-12-31"
}
```
#### Response
```json
{
    "success": true
}
```

### Check Loan
POST http://localhost:120/api/v1/loan/check
```json
{
    "user_id": 1,
    "loan_id": 1
}
```
#### Response
#### Region PR
```json
{
    "is_eligible": true,
    "reasons": [],
    "adjusted_rate": 10,
    "success": true
}
```
```json
{
    "is_eligible": false,
    "reasons": [
        "Случайный отказ клиенту из Праги."
    ],
    "adjusted_rate": 10,
    "success": true
}
```
#### Region OS
```json
{
    "is_eligible": true,
    "reasons": [],
    "adjusted_rate": 15,
    "success": true
}
```
#### Region Other
```json
{
    "is_eligible": false,
    "reasons": [
        "Регион пользователя не поддерживается для кредитования."
    ],
    "adjusted_rate": 10,
    "success": true
}
```
#### Age: < 18 or > 60
```json
{
    "is_eligible": false,
    "reasons": [
        "Возраст клиента должен быть от 18 до 60 лет.",
        "Случайный отказ клиенту из Праги."
    ],
    "adjusted_rate": 10,
    "success": true
}
```
### Income: < 1000
```json
{
    "is_eligible": false,
    "reasons": [
        "Доход клиента ниже 1000."
    ],
    "adjusted_rate": 10,
    "success": true
}
```
#### Score: < 500
```json
{
    "is_eligible": false,
    "reasons": [
        "Кредитный рейтинг ниже 500."
    ],
    "adjusted_rate": 10,
    "success": true
}
```
#### Answers for user in log
```
2025-06-19 10:37 К сожалению, ваш кредит не может быть одобрен. [Petr Pavel]: Кредит отклонён

2025-06-19 10:39 Поздравляем! Ваш кредит одобрен. Ставка: 10%. [Petr Pavel]: Кредит одобрен
```
