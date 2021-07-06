# Secret Server Bittner Attila

## Setup
 1. git clone `https://github.com/bittner-attila/secret-server`
 2. A frontend könyvtárba futtatni a `npm install` parancsot.
 3. A backend könyvtárba composer update parancs futtataása.
 4. A backend könyvtárba .env example fájl, hogy létrehozása a .env.example alapján.
 5. gyökérkönyvtárában futtatni a `docker-compose up -d` parancsot.
 6. belépés az app konténerébe `docker exec -ti secret-backend sh`
 7. Teszt futtatása: `php artisan test --filter SecretTest`
 8. Adatbázis feltöltése teszt adatokkal: `php artisan db:seed`
