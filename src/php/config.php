<?php
// config.php - Path and Storage Settings

// المسار المطلق لقاعدة البيانات
define('SQLITE_PATH', __DIR__ . '/../data/app.sqlite');

// يمكنك إضافة رابط الموقع الأساسي هنا لتسهيل الروابط لاحقاً
define('BASE_URL', '/myapp/web-project/');

// تفعيل عرض الأخطاء (أثناء البرمجة فقط)
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// إعداد المنطقة الزمنية (مفيد للمواعيد والتقارير)
date_default_timezone_set('Africa/Cairo'); 
?>