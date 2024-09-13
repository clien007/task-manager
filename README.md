## Task Manager

**Repository**  
https://github.com/clien007/task-manager

**Getting Started**

To set up and run this project locally, follow these steps:

1. Fork this repository.
2. Clone the forked repository to your local machine.
3. Install PHP dependencies: composer install.
4. Run database migrations: php artisan migrate.
5. Seed the database: php artisan db:seed.
6. Install JavaScript dependencies: npm install.
7. Compile assets: npm run dev.
8. Start the development server: php artisan serve.

**Project Overview**

This project is a task management application that supports CRUD operations and provides an API. It also includes multi-language support for English and Spanish.

**Libraries and Tools**

    - laravel-sanctum
    - laravel-breeze
    - Tailwind CSS

**Routes**

    Tasks:
    Get All Tasks
        `GET` /tasks
    Add New Task
       `GET` /tasks/create
    Edit Task
        `GET` /tasks/edit/{id}
    Update Task Status
        `POST` tasks/{id}/update-status
    Delete Task
        `Delete` /tasks/{id}

    Archives:
    Get All Archives
        `GET` /archvives   

**API Documentation**
API requests can be tested using Postman.

    Login
    - `POST` /api/login
    - Request Body
        {
            "email" : "your@email.com",
            "password" : "password" 
        }

After logging in, you will receive a token. In Postman, go to the Authorization tab, select "Bearer Token," and enter the token in the format:
    Authorization: Bearer {token}

**Endpoints**

    Get Tasks Statistics
    `GET` /tasks/stats

    Get All Tasks
    `GET` /tasks

    Add New Task
    `POST` /tasks/
        - Request Body
            {
                "title" : "test",
                "description" : "Desc 1",
                "category_id" : 1
            }

    Update Task
    `PUT` /tasks/edit/{id}
        - Request Body
            {
                "title" : "test",
                "description" : "Desc 1",
                "category_id" : 1
            }

    Update Task Status
    `POST` tasks/{id}/update-status
        - Request Body
            {
                "next_status" : "In Progress"
            }

    Delete Task
    `Delete` /tasks/{id}


**Additional Features**

Enhanced Filtering and Pagination

    Web Application:
        Implemented task filtering by status and category using query parameters.
        Added pagination to the task list view.
    API:
        Added filtering capabilities to the GET /api/tasks endpoint for status and category.
        Implemented pagination for the task list endpoint.
    Shared:
        Created a trait to share filtering logic between web and API controllers.

Dashboard and Statistics

    Web Application:
        Created a dashboard displaying task statistics, including counts of tasks by status.
    API:
        Added an endpoint to retrieve task statistics: GET /api/tasks/stats.
    Shared:
        Extended TaskService to include a method for generating task statistics.
    Caching:
        Implemented caching for task statistics using Laravel's cache system with a reasonable expiration time.

Background Job Processing

    Created a queued job to archive completed tasks older than 30 days.
    Scheduled this job to run daily using Laravel's task scheduler.

Localization

    Added basic localization support for English and Spanish for the web interface.
    Translated navigation items, form labels, and task status names.

    Faced challenges with ensuring accurate translations across the application and resolved them by using Laravel’s built-in localization features and double-checking translations.

Additional Testing

    Wrote tests for the new filtering functionality in both web and API interfaces.
    Created tests for the caching mechanism of task statistics.
    Wrote a test for the queued job that archives old tasks.
