# Task List CRUD API

A simple task list API built with Laravel, providing basic CRUD (Create, Read, Update, Delete) functionality. This project does not include user authentication or role-based access.

## Features

- Create tasks
- View all tasks
- Edit tasks
- Delete tasks

## API Routes

The following API routes are available:

| Method | Endpoint          | Description       |
| ------ |-------------------| ----------------- |
| GET    | `/api/tasks`      | Get all tasks     |
| POST   | `/api/tasks`      | Create a task     |
| GET    | `/api/tasks/{id}` | Get a single task |
| PUT    | `/api/tasks/{id}` | Update a task     |
| DELETE | `/api/tasks/{id}` | Delete a task     |

