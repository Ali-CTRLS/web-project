# Task: php/config.php

## Goal
Create a single place for app configuration (paths, storage mode, and defaults) so all PHP files include it.

## Senior Engineer's Advice
> [!TIP]
> Use `dirname(__FILE__)` or `__DIR__` to define absolute paths. This prevents broken includes when files are moved or included from different subdirectories.

## Detailed Steps
1. **Define Root Path**: Use `define('APP_ROOT', dirname(__DIR__) . '/');` to get the base project directory.
2. **Define Data Directory**: Set `define('DATA_DIR', APP_ROOT . 'src/data/');`.
3. **Database Path**: Set `define('SQLITE_PATH', DATA_DIR . 'app.sqlite');`.
4. **Storage Mode**: Set `define('STORAGE_MODE', 'sqlite');` (default). This allows switching to JSON later if needed.
5. **Configuration Boilerplate**:
   ```php
   <?php
   // config.php
   if (!defined('APP_ROOT')) {
       define('APP_ROOT', dirname(__DIR__) . '/');
       define('DATA_DIR', APP_ROOT . 'src/data/');
       define('SQLITE_PATH', DATA_DIR . 'app.sqlite');
       define('STORAGE_MODE', 'sqlite');
   }
   ?>
   ```

## Done When
- Other PHP files can include `config.php` and access `APP_ROOT` or `SQLITE_PATH` without hardcoding paths.
