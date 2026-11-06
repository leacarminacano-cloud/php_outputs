<?php
// Front controller: every request comes through here and is routed to a controller method.
session_start();
require_once __DIR__ . "/controllers/FacultyController.php";

function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, "UTF-8"); }

$routes = ["index", "create", "edit", "delete"];
$action = $_GET["action"] ?? "index";
if (!in_array($action, $routes, true)) { $action = "index"; }

try {
    (new FacultyController())->$action();
} catch (PDOException $ex) {
    http_response_code(500);
    echo "<p>Database error. Did you import database.sql?</p><pre>" . e($ex->getMessage()) . "</pre>";
}
