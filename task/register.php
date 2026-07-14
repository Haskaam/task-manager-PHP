<!DOCTYPE html>

<html lang="pl">
    <head>
        <meta charset="utf-8">
        <link rel="stylesheet" href="style.css">
    </head>
    <body>
        <header><h1>TaskManager</h1>

        <section id="home">
            <a href="login.php">Home</a>
            <a href="login.php">O aplikacji</a>
            <a href="login.php">Funkcje</a>
        </section>

        <nav>
            <a href="login.php" id="loginbtn">Zaloguj się</a>
            <a href="register.php" id="regbtn">Zarejestruj się</a>
        </nav>
        </header>

        <main>
            <section id="login">
                <form method="POST">
                    <h3>Utwórz konto</h3>
                    <p>Dołącz do nas i zacznij zarządzać swoimi zadaniami!</p>

                    <label><span class="helptext">Nazwa użytkownika:</span><br>
                        <input type="text" name="user" placeholder="Utwórz login">
                    </label>

                    <label><span class="helptext">Hasło:</span><br>
                        <input type="password" name="password" placeholder="Utwórz hasło">
                    </label>

                    <label><span class="helptext">Powtórz hasło:</span><br>
                        <input type="password" name="repeated_password" placeholder="Powtórz hasło">
                    </label>

                    <button type="submit" name="register">Zarejestruj się</button>
                </form>

                <?php
                $host = "localhost";
                $dbname = "taskmanager";

                try {
                $connect = new PDO("mysql:host=$host; dbname=$dbname;charset=utf8mb4", "root", "");

                $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch(PDOException $e){
                    die("Connection failed: " . $e->getMessage());
                }

                if(isset($_POST['register'])) {
                    $user = trim($_POST['user']);
                    $password = trim($_POST['password']);
                    $repeated_password = trim($_POST['repeated_password']);

                    if(empty($user) || empty($password) || empty($repeated_password)) {
                        echo("Wypełnij wszystkie pola.");
                        exit;
                    }

                        if($password !== $repeated_password) {
                            echo("Hasła nie są takie same.");
                            exit;
                    }

                    $stmt = $connect ->prepare("SELECT id FROM users WHERE username = ?");

                    $stmt->execute([$user]);

                    if($stmt->fetch()) {
                        echo("Taki użytkownik już istnieje.");
                        exit;
                    }

                    $hashedpassword = password_hash($password, PASSWORD_DEFAULT);

                    $stmt = $connect->prepare("INSERT INTO users (username, password) VALUES (?, ?)");

                    $stmt->execute([$user, $hashedpassword]);

                    header("Location: login.php");
                    exit;
                }
                ?>

                <div class="divider">
                    <span>lub</span>
                </div>

                <p>Masz już konto? <a href="login.php">Zaloguj się</a></p>
            </section>
        </main>

        <footer>
            <p>2026 TaskManager. Wszystkie prawa zastrzeżone.</p>


        </footer>
    </body>
</html>