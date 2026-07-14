# Task Manager PHP

Simple built with pure PHP and MySQL.

## About

This project was created to practice backend development without using a PHP framework.

The goal was to understand authentication, sessions, database relations and CRUD operations before rewriting the application in Laravel.

## Features

- User registration
- User login and logout
- Password hashing
- Session-based authentication
- Task creation
- Task editing
- Task deletion
- Task status management
- User-specific tasks

## Technologies

- PHP
- MySQL
- PDO
- HTML
- CSS
- JavaScript

## Database

The application uses two main tables:

- `users`
- `tasks`

A user can have many tasks.

## Security

- Passwords are securely hashed using `password_hash()` and verified with `password_verify()`
- User authentication is handled with PHP sessions
- Dashboard access is restricted to authenticated users
- Tasks are associated with users through `user_id`
- Users can only modify or delete their own tasks
- SQL queries use PDO prepared statements
- User-generated content is escaped using `htmlspecialchars()`

## Project status

Completed.

The application is currently being rewritten in Laravel to learn MVC, Eloquent ORM and Laravel authentication.