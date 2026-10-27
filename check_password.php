<?php

require_once "config/db.php";

$username = "admin";
$password = "lez2026";

$stmt = $conn->prepare("SELECT password FROM users WHERE username = ?");
$stmt->bind_param("s", $username);
$stmt->execute();

$result = $stmt->get_result();

if ($result->num_rows === 1) {

    $user = $result->fetch_assoc();

    echo "<h2>Admin account found ✅</h2>";

    if (password_verify($password, $user["password"])) {
        echo "<h2 style='color:green;'>PASSWORD MATCHES ✅</h2>";
    } else {
        echo "<h2 style='color:red;'>PASSWORD DOES NOT MATCH ❌</h2>";
    }

} else {
    echo "<h2 style='color:red;'>ADMIN ACCOUNT NOT FOUND ❌</h2>";
}

?>