
README

Task management application built with Laravel 11 (PHP 8.3+), Livewire v3, and SortableJS.

Author: Mateusz Grzegorz Lipiński<br>
e-mail: mglipinski92@gmail.com

Requirements

• Docker Desktop and WSL2

Setup and Installation

1. Open your WSL2 terminal, navigate to the project directory and start the Docker containers:
   ./vendor/bin/sail up -d
2. Install the Composer dependencies inside the application container:
   ./vendor/bin/sail composer install
3. Create the environment file and generate the application encryption key:
   cp .env.example .env
   ./vendor/bin/sail artisan key:generate
4. Run the database migrations and seed the initial projects:
   ./vendor/bin/sail artisan migrate:fresh --seed
5. Install the frontend npm packages for TailwindCSS and SortableJS:
   ./vendor/bin/sail npm install
6. Start the Vite asset compilation server in the background:
   ./vendor/bin/sail npm run dev
   The web application is accessible in your browser at: http://localhost
