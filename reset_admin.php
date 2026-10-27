<?php

require_once "config/db.php";

$username = "admin";
$password = "lez2026";

$hash = password_hash($password, PASSWORD_DEFAULT);

$stmt = $conn->prepare(
    "UPDATE users SET password = ?, role = 'admin' WHERE username = ?"
);

$stmt->bind_param("ss", $hash, $username);

if (!$stmt->execute()) {
    die("UPDATE FAILED: " . $stmt->error);
}

if ($stmt->affected_rows >= 0) {

    $check = $conn->prepare(
        "SELECT password FROM users WHERE username = ?"
    );

    $check->bind_param("s", $username);
    $check->execute();

    $result = $check->get_result();
    $user = $result->fetch_assoc();

    if (password_verify($password, $user["password"])) {
        echo "<h1 style='color:green;'>PASSWORD RESET SUCCESSFUL ✅</h1>";
        echo "<p>Username: admin</p>";
        echo "<p>Password: lez2026</p>";
        echo "<p>The password has been verified by PHP.</p>";
    } else {
        echo "<h1 style='color:red;'>PASSWORD VERIFICATION FAILED ❌</h1>";
    }

    $check->close();
}

$stmt->close();
$conn->close();

?>