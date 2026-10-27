<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>LEZ FITNESS</title>

    <link rel="stylesheet" href="assets/css/style.css">
</head>

<body class="splash-page">

    <div class="splash-screen">

        <!-- Background glow -->
        <div class="splash-glow splash-glow-one"></div>
        <div class="splash-glow splash-glow-two"></div>

        <div class="splash-content">

            <!-- Dumbbell Icon -->
            <div class="splash-logo">
                <div class="dumbbell">
                    <span></span>
                    <span></span>
                    <span class="bar"></span>
                    <span></span>
                    <span></span>
                </div>
            </div>

            <!-- Brand -->
            <div class="splash-brand">
                <h1>
                    LEZ <span>FITNESS</span>
                </h1>

                <p>TRAIN • BUILD • ACHIEVE</p>
            </div>

            <!-- Motto -->
            <div class="splash-motto">
                <span></span>
                <h2>DISCIPLINE. STRENGTH. CONSISTENCY.</h2>
                <span></span>
            </div>

            <!-- Loading -->
            <div class="splash-loading">

                <div class="loading-bar">
                    <div class="loading-progress"></div>
                </div>

                <p>INITIALIZING SYSTEM...</p>

            </div>

        </div>

        <!-- Footer -->
        <div class="splash-footer">
            LEZ FITNESS &nbsp;•&nbsp; MEMBERSHIP & ATTENDANCE SYSTEM
        </div>

    </div>


    <script>

        setTimeout(function () {
            document.querySelector(".splash-screen")
                .classList.add("splash-exit");

            setTimeout(function () {
                window.location.href = "login.php";
            }, 700);

        }, 3000);

    </script>

</body>
</html>