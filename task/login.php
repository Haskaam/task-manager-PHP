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
                    <h3>Zaloguj się</h3>
                    <p>Witaj w TaskManager. Zaloguj się aby kontynuować.</p>

                    <label><span class="helptext">Nazwa użytkownika:</span><br>
                        <input type="text" name="user" placeholder="Wpisz login">
                    </label>

                    <label><span class="helptext">Hasło:</span><br>
                        <input type="password" name="password" placeholder="Wpisz hasło">
                    </label>
                    <a href="login.php">Nie pamiętasz hasła?</a>

                    <button type="submit" name="login">Zaloguj się</button>
                </form>

                <?php 
                $host = "localhost";
                $dbname = "taskmanager";

                try {
                $connect = new PDO("mysql:host=$host; dbname=$dbname; charset=utf8mb4", "root", "");

                $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                } catch(PDOException $e) {
                    die("Connection failed: " . $e->getMessage());
                }


                if(isset($_POST['login'])) {
                    $user = trim($_POST['user']);
                    $password = trim($_POST['password']);

                    if(empty($user) || empty($password)) {
                        echo("Wypełnij wszystkie pola.");
                        exit;
                    }

                    $stmt = $connect->prepare("SELECT * FROM users WHERE username = ?");

                    $stmt->execute([$user]);

                    $userData = $stmt->fetch();

                    if(!$userData) {
                        echo("Nieprawidłowy login lub hasło.");
                        exit;
                    }

                    if(!password_verify($password, $userData['password'])) {
                        echo("Nieprawidłowy login lub hasło.");
                        exit;
                    }

                    session_start();

                    $_SESSION['user_id'] = $userData['id'];
                    $_SESSION['username'] = $userData['username'];

                    header("Location: dashboard.php");
                    exit;
                }
                ?>

                <div class="divider">
                    <span>lub</span>
                </div>

                <p>Nie masz jeszcze konta? <a href="register.php">Zarejestruj się</a></p>
            </section>
        </main>

        <footer>
            <p>2026 TaskManager. Wszystkie prawa zastrzeżone.</p>
        </footer>
    </body>
</html>