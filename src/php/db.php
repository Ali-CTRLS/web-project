<?php
// db.php - Simple Database Helper
require_once __DIR__ . '/config.php';
require_once __DIR__ . '/session.php';

function db_connect() {
    $pdo = new PDO('sqlite:' . SQLITE_PATH);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    return $pdo;
}

function db_query($pdo, $sql, $params = []) {
    $stmt = $pdo->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

function db_init($pdo) {
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT,
        email TEXT UNIQUE,
        password TEXT,
        role TEXT DEFAULT 'patient'
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS injuries (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        patient_id INTEGER,
        full_name TEXT,
        injury_date DATE,
        location TEXT,
        severity TEXT,
        cause TEXT,
        description TEXT,
        medications TEXT,
        symptoms TEXT
    )");

    $pdo->exec("CREATE TABLE IF NOT EXISTS appointments (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        patient_id INTEGER,
        doctor_name TEXT,
        preferred_date DATE,
        preferred_time TIME,
        reason TEXT,
        status TEXT DEFAULT 'pending',
        updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");

    $columns = $pdo->query('PRAGMA table_info(appointments)')->fetchAll(PDO::FETCH_ASSOC);
    $has_updated_at = false;

    foreach ($columns as $column) {
        if (($column['name'] ?? '') === 'updated_at') {
            $has_updated_at = true;
            break;
        }
    }

    if (!$has_updated_at) {
        $pdo->exec('ALTER TABLE appointments ADD COLUMN updated_at DATETIME');
    }

    $pdo->exec("CREATE TABLE IF NOT EXISTS reports (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        patient_id INTEGER NOT NULL,
        doctor_id INTEGER,
        doctor_name TEXT,
        report_type TEXT NOT NULL,
        report_date DATE,
        findings TEXT,
        diagnosis TEXT,
        treatment_plan TEXT,
        medications TEXT,
        followup_instructions TEXT,
        additional_notes TEXT,
        status TEXT DEFAULT 'draft',
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP
    )");
}

$pdo = db_connect();
db_init($pdo);
?>
