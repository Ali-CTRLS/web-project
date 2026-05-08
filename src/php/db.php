<?php
// db.php - Simple Database Helper
require_once 'config.php';

function db_connect() {
    $pdo = new PDO("sqlite:" . SQLITE_PATH);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ATTR_EXCEPTION);
    return $pdo;
}

function db_init($pdo) {
    // Create Users
    $pdo->exec("CREATE TABLE IF NOT EXISTS users (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        name TEXT, 
        email TEXT UNIQUE, 
        password TEXT, 
        role TEXT DEFAULT 'patient'
    )");

    // Create Injuries
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

    // Create Appointments
    $pdo->exec("CREATE TABLE IF NOT EXISTS appointments (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        patient_id INTEGER, 
        doctor_name TEXT, 
        preferred_date DATE, 
        preferred_time TIME, 
        reason TEXT, 
        status TEXT DEFAULT 'pending'
    )");
}

// Connect and Init
$pdo = db_connect();
db_init($pdo);
?>
