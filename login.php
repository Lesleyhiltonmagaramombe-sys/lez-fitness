<?php

session_start();

require_once "config/db.php";

$error = "";

if (isset($_POST["login"])) {

    $username = trim($_POST["username"] ?? "");
    $password = $_POST["password"] ?? "";

    /*
    |--------------------------------------------------------------------------
    | ADMIN / STAFF / TRAINER LOGIN
    |--------------------------------------------------------------------------
    */

    $stmt = $conn->prepare("
        SELECT id, username, password, role
        FROM users
        WHERE username = ?
        LIMIT 1
    ");

    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();

    if ($result->num_rows === 1) {

        $user = $result->fetch_assoc();

        if (password_verify($password, $user["password"])) {

            /*
            |--------------------------------------------------------------------------
            | TRAINER LOGIN
            |--------------------------------------------------------------------------
            */

            if ($user["role"] === "trainer") {

                $trainer_stmt = $conn->prepare("
                    SELECT t.id, t.status
                    FROM trainer_accounts ta
                    INNER JOIN trainers t
                        ON t.id = ta.trainer_id
                    WHERE ta.user_id = ?
                    LIMIT 1
                ");

                $trainer_stmt->bind_param("i", $user["id"]);
                $trainer_stmt->execute();

                $trainer_result = $trainer_stmt->get_result();

                if ($trainer_result->num_rows === 1) {

                    $trainer = $trainer_result->fetch_assoc();

                    if ($trainer["status"] === "Active") {

                        session_regenerate_id(true);

                        // Clear any previous member or user session.
                        unset(
                            $_SESSION["member_id"],
                            $_SESSION["member_number"],
                            $_SESSION["member_name"],
                            $_SESSION["trainer_id"]
                        );

                        $_SESSION["user_id"] = (int) $user["id"];
                        $_SESSION["username"] = $user["username"];
                        $_SESSION["role"] = "trainer";
                        $_SESSION["trainer_id"] = (int) $trainer["id"];

                        $trainer_stmt->close();
                        $stmt->close();

                        header("Location: trainer/dashboard.php");
                        exit();

                    } else {

                        $error = "Your trainer account is inactive. Please contact the administrator.";

                    }

                } else {

                    $error = "No trainer profile is linked to this account. Please contact the administrator.";

                }

                $trainer_stmt->close();

            }

            /*
            |--------------------------------------------------------------------------
            | ADMIN / STAFF LOGIN
            |--------------------------------------------------------------------------
            */

            elseif (in_array($user["role"], ["admin", "staff"], true)) {

                session_regenerate_id(true);

                // Clear any previous member or trainer session.
                unset(
                    $_SESSION["member_id"],
                    $_SESSION["member_number"],
                    $_SESSION["member_name"],
                    $_SESSION["trainer_id"]
                );

                $_SESSION["user_id"] = (int) $user["id"];
                $_SESSION["username"] = $user["username"];
                $_SESSION["role"] = $user["role"];

                $stmt->close();

                header("Location: admin/dashboard.php");
                exit();

            } else {

                $error = "This account does not have a valid login role.";

            }

        }

    }

    $stmt->close();

    /*
    |--------------------------------------------------------------------------
    | MEMBER LOGIN USING PHONE NUMBER
    |--------------------------------------------------------------------------
    */

    if ($error === "") {

        $stmt = $conn->prepare("
            SELECT id, member_number, full_name, phone
            FROM members
            WHERE phone = ?
            LIMIT 1
        ");

        $stmt->bind_param("s", $username);
        $stmt->execute();

        $member_result = $stmt->get_result();

        if ($member_result->num_rows === 1) {

            $member = $member_result->fetch_assoc();

            /*
             * Existing member login method:
             * The password must match the registered phone number.
             */

            if ($password === $member["phone"]) {

                session_regenerate_id(true);

                // Clear any previous staff or trainer session.
                unset(
                    $_SESSION["user_id"],
                    $_SESSION["username"],
                    $_SESSION["role"],
                    $_SESSION["trainer_id"]
                );

                $_SESSION["member_id"] = (int) $member["id"];
                $_SESSION["member_number"] = $member["member_number"];
                $_SESSION["member_name"] = $member["full_name"];

                $stmt->close();

                header("Location: member/dashboard.php");
                exit();

            }

        }

        $stmt->close();

        $error = "Invalid username/phone number or password.";

    }

}

?>

<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>LEZ FITNESS - Login</title>

    <link rel="stylesheet" href="assets/css/style.css">

</head>

<body>

<div class="login-page">

    <div class="login-box">

        <!-- BRAND -->
        <div class="login-brand">

            <div class="login-mark">
                LF
            </div>

            <div>
                <h1>LEZ FITNESS</h1>
                <p>MEMBERSHIP &amp; ATTENDANCE</p>
            </div>

        </div>

        <!-- LOGIN INTRO -->
        <div class="login-heading">

            <h2>WELCOME BACK</h2>

            <p>
                Sign in to access your fitness management portal.
            </p>

        </div>

        <!-- ERROR MESSAGE -->
        <?php if ($error !== ""): ?>

            <div class="alert alert-error">
                <?php echo htmlspecialchars($error, ENT_QUOTES, "UTF-8"); ?>
            </div>

        <?php endif; ?>

        <!-- LOGIN FORM -->
        <form method="POST">

            <div class="form-group">

                <label for="username">
                    USERNAME / PHONE NUMBER
                </label>

                <input
                    type="text"
                    id="username"
                    name="username"
                    placeholder="Enter username or phone number"
                    autocomplete="username"
                    required
                >

            </div>

            <div class="form-group">

                <label for="password">
                    PASSWORD
                </label>

                <input
                    type="password"
                    id="password"
                    name="password"
                    placeholder="Enter your password"
                    autocomplete="current-password"
                    required
                >

            </div>

            <button
                type="submit"
                name="login"
                class="login-button"
            >
                LOGIN
            </button>

        </form>

        <!-- LOGIN INFORMATION -->
        <div class="login-info">

            <div class="login-info-row">

                <span class="info-label">ADMIN / STAFF</span>

                <span>
                    Use your assigned username and password.
                </span>

            </div>

            <div class="login-info-row">

                <span class="info-label">TRAINER</span>

                <span>
                    Use the trainer login credentials assigned by the administrator.
                </span>

            </div>

            <div class="login-info-row">

                <span class="info-label">MEMBER</span>

                <span>
                    Use your registered phone number as your username and password.
                </span>

            </div>

        </div>

        <!-- FOOTER -->
        <div class="login-footer">

            <span>LEZ FITNESS</span>

            <span>SECURE ACCESS</span>

        </div>

    </div>

</div>

</body>

</html>