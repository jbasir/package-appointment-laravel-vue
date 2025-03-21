## Requirements

- PHP 8.2+
- Laravel 10+ / 11+ / 12+
- Node.js and NPM (for Vue.js UI development)

## Note: If you need to run only the quote package in your Laravel project, you will find another readme in the package directory.

## Installation and run server
 
### 0. Install PHP dependencies

```bash
composer install
```

### 1. Create environment configuration file

```bash
cp .env.example .env
```

### 2. Create SQLite database file

```bash
touch database/database.sqlite
```

### 3. Run database migrations

```bash
php artisan migrate
```

### 4. Start the Laravel development server

```bash
php artisan serve
```

## Installation and run UI

 
### 0. Navigate to the UI directory

```bash
cd packages/quotes/resources/js
```

### 1. Install JavaScript dependencies

```bash
npm install
```

### 2. Build the UI assets for production

```bash
npm build
```

### 3. Start the UI development server

```bash
npm run dev
```

### 4. Open http://localhost:5173/quotes-ui/ in your browser to test the package with the UI

## Note: If you need to run only the quote package in your Laravel project, you will find another readme in the package directory.