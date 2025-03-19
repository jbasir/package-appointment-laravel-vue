
## Requirements

- PHP 8.2+
- Laravel 10+ / 11+ / 12+
- Node.js and NPM (for Vue.js UI development)

## Note: If you need to run only the quote package in your Laravel project, you will find another readme in the package directory.

## Installation and run server
 
### 0.

```bash
composer install
```

### 1.

```bash
cp .env.example .env
```

### 2. 

```bash
touch database/database.sqlite
```

### 3.

```bash
php artisan migrate
```

### 4. 

```bash
php artisan serve
```

## Installation and run UI

 
### 0. In a another terminal go to packages/quotes/resources/js directory

```bash
cd packages/quotes/resources/js
```

### 1.

```bash
npm install
```

### 2. 

```bash
npm build
```

### 3.

```bash
npm run dev
```

### 4. Open http://localhost:5173/quotes-ui/ in your browser to test the package with the UI

## Note: If you need to run only the quote package in your Laravel project, you will find another readme in the package directory.