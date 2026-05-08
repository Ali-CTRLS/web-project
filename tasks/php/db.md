# Task: php/db.php

## Goal
Provide database access and initialize schema for users, injuries, and appointments.

## Senior Engineer's Advice
> [!IMPORTANT]
> Always use PDO (PHP Data Objects) with prepared statements to prevent SQL injection. For SQLite, ensure you enable Foreign Keys explicitly.

## Detailed Steps
1. **Include Configuration**: Start with `require_once 'config.php';`.
2. **Database Connection**: Create a function `db_connect()` that returns a `PDO` instance.
   ```php
   function db_connect() {
       $pdo = new PDO("sqlite:" . SQLITE_PATH);
       $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ATTR_EXCEPTION);
       $pdo->exec("PRAGMA foreign_keys = ON;");
       return $pdo;
   }
   ```
3. **Initialize Schema**: Create `db_init($pdo)` to set up tables if they don't exist.
   ```sql
   -- Users Table
   CREATE TABLE IF NOT EXISTS users (
       id INTEGER PRIMARY KEY AUTOINCREMENT,
       name TEXT NOT NULL,
       email TEXT UNIQUE NOT NULL,
       password TEXT NOT NULL,
       role TEXT DEFAULT 'patient',
       created_at DATETIME DEFAULT CURRENT_TIMESTAMP
   );

   -- Injuries Table
   CREATE TABLE IF NOT EXISTS injuries (
       id INTEGER PRIMARY KEY AUTOINCREMENT,
       patient_id INTEGER NOT NULL,
       full_name TEXT NOT NULL,
       injury_date DATE NOT NULL,
       location TEXT NOT NULL,
       severity TEXT NOT NULL,
       cause TEXT NOT NULL,
       description TEXT NOT NULL,
       medications TEXT,
       symptoms TEXT NOT NULL,
       created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
       FOREIGN KEY (patient_id) REFERENCES users(id)
   );

   -- Appointments Table
   CREATE TABLE IF NOT EXISTS appointments (
       id INTEGER PRIMARY KEY AUTOINCREMENT,
       patient_id INTEGER NOT NULL,
       doctor_name TEXT NOT NULL,
       preferred_date DATE NOT NULL,
       preferred_time TIME NOT NULL,
       reason TEXT NOT NULL,
       status TEXT DEFAULT 'pending',
       created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
       updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
       FOREIGN KEY (patient_id) REFERENCES users(id)
   );
   ```
4. **Helper Functions**:
   - `db_query($pdo, $sql, $params = [])`: Prepares and executes a SELECT statement.
   - `db_execute($pdo, $sql, $params = [])`: For INSERT/UPDATE/DELETE.

## Done When
- Calling `db_connect()` returns a working PDO object and tables are created automatically on the first run.
