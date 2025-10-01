# Project

My personal website built with Laravel and Tailwind CSS.

## Project Structure

```
engetschwiler/
    app/                    # Application business logic
    bootstrap/              # Laravel bootstrap files
    config/                 # Configuration files
    public/                 # Public entry point (index.php, assets)
    resources/              # Views, CSS and JavaScript
       css/                 # CSS files
       js/                  # JavaScript files
       views/               # Blade templates
    routes/                 # Route definitions
    storage/                # Generated files (logs, cache, etc.)
    tests/                  # Automated tests
    vendor/                 # PHP dependencies (Composer)
    node_modules/           # JavaScript dependencies (npm/yarn)
    .env.example            # Configuration template
    composer.json           # PHP dependencies
    package.json            # JavaScript dependencies
    vite.config.js          # Vite configuration
```

## Prerequisites

- PHP >= 8.3
- Composer
- Node.js and npm (or yarn)
- A web server (Apache, Nginx) or use Laravel's built-in server

## Installation

### 1. Clone the project

```bash
git clone <repo-url>
cd engetschwiler
```

### 2. Install PHP dependencies

```bash
composer install
```

### 3. Install JavaScript dependencies

```bash
npm install
# or
yarn install
```

### 4. Environment setup

```bash
cp .env.example .env
php artisan key:generate
```

Edit the `.env` file according to your needs (database, etc.).

### 5. Start the development server

```bash
composer dev
```

This command starts simultaneously:
- Laravel server (port 8000)
- Vite server for hot-reload assets

Or run services separately:

```bash
# Laravel server only
php artisan serve

# Compile assets in development mode
npm run dev
```

The site will be accessible at: `http://localhost:8000`

## Development

### Build assets for production

```bash
npm run build
```

### Run tests

```bash
composer test
# or
php artisan test
```

### Format PHP code

```bash
./vendor/bin/pint
```

## Technologies Used

- **Backend**: Laravel 12
- **Frontend**: Tailwind CSS 4
- **Build tool**: Vite
- **Package manager**: Composer (PHP), npm/yarn (JavaScript)

## Modifying the Code

### Add a new page

1. Create a route in `routes/web.php`
2. Create a controller in `app/Http/Controllers/` (optional)
3. Create a view in `resources/views/`

### Modify styles

- CSS files are located in `resources/css/`
- The project uses Tailwind CSS for styling
- Changes are automatically reloaded in development mode

### Modify JavaScript

- JS files are located in `resources/js/`
- Changes are automatically reloaded in development mode

## License

MIT
