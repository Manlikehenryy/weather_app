### Laravel Project

### Download and install Git

https://git-scm.com/downloads

### Open command prompt and clone the repository
```
git clone https://github.com/Manlikehenryy/weather_app.git
```

### Enter into project folder
```
cd ATS-app
```


# backend

### Download and install composer

https://getcomposer.org/download


### Laravel code

### Project setup
```
composer install 
```
### Download and install to get access to MySQL and PHP version 8.1.12

https://www.apachefriends.org/download.html

### Start the database

click on the start button beside Apache and MySQL

### Open the database on your browser

click on the admin button beside MySQL

### Create database

create a new database, name it "ats_app"

### create environment file
```
cp .env.example .env
```
### Generate key 
```
php artisan key:generate
```
### Migration
```
php artisan migrate
```
### Start the backend server
```
php artisan serve
```