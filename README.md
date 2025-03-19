# Simple TODO List CRUD API

A simple TODO list API built with Laravel, providing basic CRUD (Create, Read, Update, Delete) functionality.

## Set Up and Run

1. Clone repository
2. Run `docker-compose up -d`
3. Copy .env.example, rename to .env and configure
4. Run `docker-compose run --rm composer composer install`
5. Run `docker-compose exec php php artisan migrate`
6. Run `docker-compose exec php php artisan test` to running tests

## Features

- Create task lists
- View all task lists
- Edit task lists
- Delete task lists
- Create tasks
- View all tasks
- Edit tasks
- Delete tasks

## API Routes

The following API routes are available:

| Method | Endpoint                     | Description         |
| ------ |------------------------------|---------------------|
| GET    | `/api/lists`                 | Get lists           |
| POST   | `/api/lists`                 | Create a list       |
| GET    | `/api/lists/{id}`            | Get a single list   |
| PUT    | `/api/lists/{id}`            | Update a list       |
| DELETE | `/api/lists/{id}`            | Delete a list       |
| ------ | -------------------          | ------------------- |
| GET    | `/api/lists/{id}/tasks`      | Get a task          |
| POST   | `/api/lists/{id}/tasks`      | Create a task       |
| GET    | `/api/lists/{id}/tasks/{id}` | Get a single task   |
| PUT    | `/api/lists/{id}/tasks/{id}` | Update a task       |
| DELETE | `/api/lists/{id}/tasks/{id}` | Delete a task       |


