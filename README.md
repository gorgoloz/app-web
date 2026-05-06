Documentazione
Applicazione web per gestire un diario personale di lettura. Permette la registrazione e il login degli utenti, e la gestione dei libri letti o in lettura.

Struttura dei file
index.php Pagina di login e registrazione dashboard.php Area personale dell'utente style.css Stile grafico dell'applicazione db.sql Script per creare il database

Database
Il database diario_lettura contiene due tabelle:

utenti – memorizza gli utenti registrati con username e password hashata.

libri – memorizza i libri di ogni utente con: titolo, pagina attuale, note e data di inserimento.

index.php – Login e Registrazione
All'apertura della pagina viene mostrato il form di login. Tramite un pulsante è possibile passare al form di registrazione (toggle JS).

Registrazione: controlla che l'username non sia già in uso, poi salva l'utente con la password hashata tramite password_hash().

Login: cerca l'utente nel DB e verifica la password con password_verify(). Se corretta, salva l'id e lo username in sessione e reindirizza alla dashboard.

JavaScript:

mostraRegistrazione() / mostraLogin() – mostrano e nascondono i due form con style.display
Validazione con addEventListener('submit') – controlla che i campi non siano vuoti prima di inviare il form
dashboard.php – Area Utente
Accessibile solo se l'utente è loggato (controlla $_SESSION['utente_id'], altrimenti reindirizza al login).

Inserimento libro: form con tre campi (titolo, pagina attuale, note). I dati vengono salvati nel DB con una query INSERT.

Modifica libro: ogni riga della tabella ha un pulsante "Modifica" che apre un form inline precompilato con i dati esistenti. Alla conferma viene eseguita una query UPDATE.

Lista libri: recupera tutti i libri dell'utente con una query SELECT e li mostra in una tabella.

Logout: distrugge la sessione con session_destroy() e reindirizza al login.

JavaScript:

toggleForm() – mostra/nasconde il form di inserimento
toggleModifica(id) – mostra/nasconde il form di modifica per ogni libro
Validazione con addEventListener e onsubmit – controlla che il titolo non sia vuoto
Come avviare il progetto
Importare db.sql in phpMyAdmin per creare il database
Copiare i file PHP e CSS nella cartella htdocs
Aprire http://localhost nel browser
