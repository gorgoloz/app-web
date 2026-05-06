<?php
session_start();

if (!isset($_SESSION['utente_id'])) {
    header('Location: index.php');
    exit;
}

$conn = mysqli_connect("localhost", "root", "", "diario_lettura", "3306");

$utente_id = $_SESSION['utente_id'];
$messaggio = '';
$tipo = '';

// Logout
if (isset($_GET['logout'])) {
    session_destroy();
    header('Location: index.php');
    exit;
}

// Inserimento libro
if (isset($_POST['azione']) && $_POST['azione'] == 'inserisci') {
    $titolo = trim($_POST['titolo']);
    $pagina = (int)$_POST['pagina_attuale'];
    $note = trim($_POST['note']);

    if ($titolo == '') {
        $messaggio = 'Il titolo e obbligatorio.';
        $tipo = 'err';
    } else {
        mysqli_query($conn, "INSERT INTO libri (utente_id, titolo, pagina_attuale, note) VALUES ('$utente_id', '$titolo', '$pagina', '$note')");
        $messaggio = 'Libro aggiunto!';
        $tipo = 'ok';
    }
}

// Modifica libro
if (isset($_POST['azione']) && $_POST['azione'] == 'modifica') {
    $id = (int)$_POST['id'];
    $titolo = trim($_POST['titolo']);
    $pagina = (int)$_POST['pagina_attuale'];
    $note = trim($_POST['note']);

    if ($titolo == '') {
        $messaggio = 'Il titolo e obbligatorio.';
        $tipo = 'err';
    } else {
        mysqli_query($conn, "UPDATE libri SET titolo='$titolo', pagina_attuale='$pagina', note='$note' WHERE id='$id' AND utente_id='$utente_id'");
        $messaggio = 'Libro aggiornato!';
        $tipo = 'ok';
    }
}

// Lettura libri dell'utente
$risultato = mysqli_query($conn, "SELECT * FROM libri WHERE utente_id='$utente_id' ORDER BY data_inserimento DESC");
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - Diario di Lettura</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<div class="container" style="max-width:700px;">

    <div class="nav">
        <h1>Diario di Lettura</h1>
        <a href="?logout=1"><button class="logout">Esci</button></a>
    </div>
    <p>Ciao, <strong><?php echo $_SESSION['username']; ?></strong>!</p>

    <?php if ($messaggio != ''): ?>
        <p class="msg-<?php echo $tipo; ?>" style="margin-top:12px;"><?php echo $messaggio; ?></p>
    <?php endif; ?>

    <!-- Bottone toggle per mostrare/nascondere il form -->
    <button onclick="toggleForm()" style="margin-top:16px;">+ Aggiungi libro</button>

    <!-- FORM INSERIMENTO (nascosto di default) -->
    <div id="form-box" style="display:none; margin-top:16px; border-top:1px solid #ccc; padding-top:16px;">
        <h2>Nuovo libro</h2>
        <form id="form-libro" method="post" action="">
            <input type="hidden" name="azione" value="inserisci">
            <label>Titolo *</label>
            <input type="text" name="titolo" id="titolo">
            <label>Pagina attuale</label>
            <input type="number" name="pagina_attuale" id="pagina" min="0" value="0">
            <label>Note</label>
            <textarea name="note" id="note" placeholder="Appunti, impressioni..."></textarea>
            <button type="submit">Salva</button>
        </form>
    </div>

    <!-- LISTA LIBRI -->
    <h2 style="margin-top:30px;">I tuoi libri</h2>

    <?php if (mysqli_num_rows($risultato) == 0): ?>
        <p style="color:#888;">Nessun libro inserito ancora.</p>
    <?php else: ?>
        <table>
            <tr>
                <th>Titolo</th>
                <th>Pagina</th>
                <th>Note</th>
                <th>Data</th>
                <th></th>
            </tr>
            <?php while ($libro = mysqli_fetch_array($risultato)): ?>
            <tr>
                <td><?php echo $libro['titolo']; ?></td>
                <td><?php echo $libro['pagina_attuale']; ?></td>
                <td><?php echo $libro['note']; ?></td>
                <td><?php echo date('d/m/Y', strtotime($libro['data_inserimento'])); ?></td>
                <td>
                    <button type="button" onclick="toggleModifica(<?php echo $libro['id']; ?>)">Modifica</button>
                </td>
            </tr>
            <!-- Form modifica inline, nascosto di default -->
            <tr id="modifica-<?php echo $libro['id']; ?>" style="display:none;">
                <td colspan="5" style="background:#f9f9f9; padding:14px;">
                    <form method="post" action="" onsubmit="return validaModifica(this)">
                        <input type="hidden" name="azione" value="modifica">
                        <input type="hidden" name="id" value="<?php echo $libro['id']; ?>">
                        <label>Titolo *</label>
                        <input type="text" name="titolo" value="<?php echo $libro['titolo']; ?>">
                        <label>Pagina attuale</label>
                        <input type="number" name="pagina_attuale" min="0" value="<?php echo $libro['pagina_attuale']; ?>">
                        <label>Note</label>
                        <textarea name="note"><?php echo $libro['note']; ?></textarea>
                        <button type="submit">Salva modifiche</button>
                        <button type="button" onclick="toggleModifica(<?php echo $libro['id']; ?>)" style="background:#888; margin-left:8px;">Annulla</button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </table>
    <?php endif; ?>

</div>

<script>
    function toggleModifica(id) {
        var riga = document.getElementById('modifica-' + id);
        if (riga.style.display == 'none') {
            riga.style.display = 'table-row';
        } else {
            riga.style.display = 'none';
        }
    }

    function validaModifica(form) {
        var titolo = form.querySelector('input[name="titolo"]').value.trim();
        if (titolo == '') {
            alert('Il titolo e obbligatorio.');
            return false;
        }
        return true;
    }

    function toggleForm() {
        var box = document.getElementById('form-box');
        if (box.style.display == 'none') {
            box.style.display = 'block';
        } else {
            box.style.display = 'none';
        }
    }

    document.getElementById('form-libro').addEventListener('submit', function(e) {
        var titolo = document.getElementById('titolo').value.trim();
        if (titolo == '') {
            e.preventDefault();
            alert('Il titolo e obbligatorio.');
        }
    });
</script>
</body>
</html>
