# E-Learning Hub server
<img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400">

## Features

- ### Admin Side:
- Register Instructors & Students
- Reset users passwords 
- ### Instructore Side:
- Create Courses
- Uplaod Mateial
- Manage Quizzes
- Manage Students
- View Students Submissions
- Edit Course Info
- ### Student Side:
- Search For New Courses
- Enroll In Course
- View Uploaded Materials
- Submit Quizzes

## Tech

- [Laravel] - A web application framework with expressive, elegant syntax.
- [JWT] - For authentication using JSON Web Tokens
- [Firebase] - Used for push notifcations
- [React WebApp] - The frontend of the website.



## Installation

Installcomposer on your machine using the following link: <br />
<a href="https://getcomposer.org/download/">Composer download</a>

Clone the repository:

```sh
git clone https://github.com/HaidarAliN/E-learning-Hub-server.git
```
In the command line, run:

```sh
cd E-learning-Hub-server
composer update
```

Copy the example env file and make the required configuration changes in the .env file

```sh
cp .env.example .env
```

Generate a new application key

```sh
php artisan key:generate
```

Generate a new JWT authentication secret key

```sh
php artisan jwt:generate
```

Run the database migrations (Set the database connection in .env before migrating)

```sh
php artisan migrate
```

Start the local development server

```sh
php artisan serve
```

You can now access the server at http://localhost:8000

## Database seeding
Populate the database with seed data with relationships which includes users, courses, uploaded materials, quizzes, questions and submissions. This can help you to quickly start testing the api or couple a frontend and start using it with ready content.

Run the database seeder and you're done
```sh
php artisan db:seed
```
Note : It's recommended to have a clean database before seeding. You can refresh your migrations at any point to clean the database by running the following command

```sh
php artisan migrate:refresh
```

**You can now use the server**


[//]: # (These are reference links used in the body of this note and get stripped out when the markdown processor does its job. There is no need to format nicely because it shouldn't be seen. Thanks SO - http://stackoverflow.com/questions/4823468/store-comments-in-markdown-syntax.)

   [JWT]: <https://www.positronx.io/laravel-jwt-authentication-tutorial-user-login-signup-api/>
   [Firebase]: <https://firebase.google.com/>
   [React WebApp]: <https://github.com/HaidarAliN/E-learning-Hub-Frontend.git>
   [Laravel]: <https://laravel.com/>
   [Amazon EC2]: <https://aws.amazon.com/ec2/>

