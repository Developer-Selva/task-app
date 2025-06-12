# Laravel Task Manager

## Features
- Create, edit, delete tasks
- Drag & drop reordering (auto updates priority)
- Associate tasks with projects
- View tasks filtered by project
- Create projects

## Setup Instructions

1. Clone the repo
```bash
git clone <your-repo-url>
cd task-app
```

2. Install dependencies
```bash
composer install
cp .env.example .env
php artisan key:generate
```

3. Configure `.env` with MySQL DB credentials.

4. Create Models
```bash
php artisan make:model Project --migration
php artisan make:model Task --migration
```
5. Create controllers
```bash
php artisan make:controller ProjectController 
php artisan make:controller TaskController
```
6. Run migrations
```bash
php artisan migrate
```

7. Serve the app
```bash
php artisan serve
```

Visit: `http://127.0.0.1:8000`