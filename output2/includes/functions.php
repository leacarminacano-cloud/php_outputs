<?php
session_start();

function e($v) { return htmlspecialchars((string)$v, ENT_QUOTES, 'UTF-8'); }

// Registered users are kept in the session so Output #2 works without a database.
function users() { return $_SESSION['users'] ?? []; }
function save_users(array $u) { $_SESSION['users'] = $u; }
function find_user($email) {
    foreach (users() as $u) { if (strcasecmp($u['email'], $email) === 0) return $u; }
    return null;
}
