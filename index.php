<?php
session_start();

if (isset($_SESSION['utente_id'])) {
    header('Location: dashboard.php');
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "diario_lettura", "3306");

$messaggio = '';
$tipo = '';

// Registrazione
if (isset($_POST['azione']) && $_POST['azione'] == 'registra') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if ($username == '' || $password == '') {
        $messaggio = 'Compila tutti i campi.';
        $tipo = 'err';
    } else {
        $risultato = mysqli_query($conn, "SELECT id FROM utenti WHERE username='$username'");
        if (mysqli_num_rows($risultato) > 0) {
            $messaggio = 'Username gia in uso.';
            $tipo = 'err';
        } else {
            $hash = password_hash($password, PASSWORD_DEFAULT);
            mysqli_query($conn, "INSERT INTO utenti (username, password) VALUES ('$username', '$hash')");
            $messaggio = 'Registrazione avvenuta! Ora puoi fare il login.';
            $tipo = 'ok';
        }
    }
}

// Login
if (isset($_POST['azione']) && $_POST['azione'] == 'login') {
    $username = trim($_POST['username']);
    $password = $_POST['password'];

    if ($username == '' || $password == '') {
        $messaggio = 'Compila tutti i campi.';
        $tipo = 'err';
    } else {
        $risultato = mysqli_query($conn, "SELECT id, password FROM utenti WHERE username='$username'");
        $utente = mysqli_fetch_array($risultato);

        if ($utente && password_verify($password, $utente['password'])) {
            $_SESSION['utente_id'] = $utente['id'];
            $_SESSION['username'] = $username;
            header('Location: dashboard.php');
            exit;
        } else {
            $messaggio = 'Username o password errati.';
            $tipo = 'err';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Diario di Lettura</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container">
    <h1>Diario di Lettura</h1>

    <?php if ($messaggio != ''): ?>
        <p class="msg-<?php echo $tipo; ?>"><?php echo $messaggio; ?></p>
    <?php endif; ?>

    <!-- FORM LOGIN -->
    <div id="box-login">
        <h2>Accedi</h2>
        <form id="form-login" method="post" action="">
            <input type="hidden" name="azione" value="login">
            <label>Username</label>
            <input type="text" name="username" id="login-username">
            <label>Password</label>
            <input type="password" name="password" id="login-password">
            <button type="submit">Entra</button>
        </form>
        <button class="toggle-link" onclick="mostraRegistrazione()">Non hai un account? Registrati</button>
    </div>

    <!-- FORM REGISTRAZIONE (nascosto di default) -->
    <div id="box-registrazione" style="display:none;">
        <h2>Registrati</h2>
        <form id="form-reg" method="post" action="">
            <input type="hidden" name="azione" value="registra">
            <label>Username</label>
            <input type="text" name="username" id="reg-username">
            <label>Password</label>
            <input type="password" name="password" id="reg-password">
            <button type="submit">Registrati</button>
        </form>
        <button class="toggle-link" onclick="mostraLogin()">Hai gia un account? Accedi</button>
    </div>
</div>

<script>
    function mostraRegistrazione() {
        document.getElementById('box-login').style.display = 'none';
        document.getElementById('box-registrazione').style.display = 'block';
    }

    function mostraLogin() {
        document.getElementById('box-registrazione').style.display = 'none';
        document.getElementById('box-login').style.display = 'block';
    }

    document.getElementById('form-login').addEventListener('submit', function(e) {
        var username = document.getElementById('login-username').value.trim();
        var password = document.getElementById('login-password').value;
        if (username == '' || password == '') {
            e.preventDefault();
            alert('Compila tutti i campi.');
        }
    });

    document.getElementById('form-reg').addEventListener('submit', function(e) {
        var username = document.getElementById('reg-username').value.trim();
        var password = document.getElementById('reg-password').value;
        if (username == '' || password == '') {
            e.preventDefault();
            alert('Compila tutti i campi.');
        }
    });
</script>
</body>
</html>
