# cc-shippers-app

cc-shippers-app test project

## Getting started

# (Laravel + Breeze & Vue3 (inertia)  + Tailwindcss )
# Laravel Sail / Docker 


1) create ".env" file from ".env.example"

2) composer install

    add "--ignore-platform-reqs" in cas you don't have php8.1 or composer 2

3)  ./vendor/bin/sail up

    alias sail='[ -f sail ] && bash sail || bash vendor/bin/sail'

4) sail composer install 

5) sail php artisan key:generate

6) sail php artisan migrate --seed

8) sail npm install (Optional)

7) go to http://localhost/login

    email       :       test@app.com
    password    :       password





