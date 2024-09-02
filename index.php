<?php
session_start();
require_once("config\config.php"); // Include database file
require_once("controller\controller.php"); // Include controller file

// Create a new instance of the Database class
$database = new Database();
$pdo = $database->connect(); // Get the PDO instance

// Create a new instance of the UserModel class
$userModel = new UserModel($pdo); // Pass the PDO instance

// Create a new instance of the UserController class
$controller = new UserController($pdo);

// Determine the action to perform
$action = isset($_GET['action']) ? $_GET['action'] : '';
$id = isset($_GET['id']) ? $_GET['id'] : null;

switch ($action) {
    case 'showlogin':
        $controller->showlogin();
        break;
    case 'showregister':
        $controller->showregister();
        break;
    case 'login':
        $controller->login();
        break;
    case 'register':
        $controller->register();
        break;
    case 'dashboard':
        if (isset($_SESSION['id'])) {
            $controller->showDashboard();
        } else {
            header('Location: ?action=showlogin');
        }
        break;
    case 'filterdate':
         if (isset($_SESSION['id'])) {
            $controller->searchDate();
        } else {
            header('Location: ?action=showlogin');
        }
        break;
    case 'sort':
        if (isset($_SESSION['id'])) {
            $controller->sort();
        } else {
            header('Location: ?action=showlogin');
        }
        break;
    case 'download_csv':
        if (isset($_SESSION['id'])) {
            $controller->download_csv();
        } else {
            header('Location: ?action=showlogin');
        }
        break;
    case 'getAttendanceData':
        if (isset($_SESSION['id'])) {
            $controller->getAttendanceData();
        } else {
            header('Location: ?action=showlogin');
        }
        break;
    case 'update':
        if (isset($_SESSION['id'])) {
            $controller->updateAttendanceData();
        } else {
            header('Location: ?action=showlogin');
        }
        break;
    case 'checkIn':
        if (isset($_SESSION['id'])) {
            $userId = $_SESSION['id']; // Get user ID from session
            $controller->checkIn($userId); // Pass the user ID to checkIn
            header('Location: ?action=dashboard'); // Redirect to dashboard to update button state
        } else {
            header('Location: ?action=showlogin');
        }
        break;
    case 'checkOut':
        if (isset($_SESSION['id'])) {
            $userId = $_SESSION['id']; // Get user ID from session
            $controller->checkOut($userId); // Pass the user ID to checkOut
            header('Location: ?action=dashboard'); // Redirect to dashboard to update button state
        } else {
            header('Location: ?action=showlogin');
        }
        break;

    case 'logout':
        $controller->logout();
        break;
    default:
        // Handle default case, e.g., show a home page or redirect
        $controller->showlogin();

        break;
}
?>
