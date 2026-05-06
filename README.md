# Diario di Lettura

Applicazione web per gestire un diario personale di lettura. Gli utenti possono registrarsi, fare il login e tenere traccia dei libri che stanno leggendo.

## File del progetto

- index.php: pagina di login e registrazione
- dashboard.php: area personale dell'utente
- style.css: stile grafico
- db.sql: script per creare il database

## Database

Il database si chiama diario_lettura e contiene due tabelle. La tabella utenti salva username e password. La tabella libri salva i libri di ogni utente con titolo, pagina attuale, note e data di inserimento, collegata agli utenti tramite utente_id.

## index.php

Mostra il form di login. Con un pulsante si passa alla registrazione e viceversa, usando style.display per mostrare e nascondere i due form. La registrazione salva l'utente con la password hashata tramite password_hash(). Il login verifica la password con password_verify() e se corretta salva i dati in sessione e manda alla dashboard. I form vengono validati con addEventListener prima dell'invio per controllare che i campi non siano vuoti.

## dashboard.php

Accessibile solo se si è loggati, altrimenti rimanda al login. Permette di inserire un nuovo libro tramite un form che si mostra e nasconde con un pulsante. Mostra la lista di tutti i libri salvati in una tabella. Ogni libro ha un pulsante Modifica che apre un form inline precompilato con i dati esistenti. Al salvataggio viene eseguita una query UPDATE. Il logout distrugge la sessione con session_destroy() e rimanda al login.

## Come avviare

Importare db.sql in phpMyAdmin, copiare i file nella cartella htdocs e aprire http://localhost/ nel browser.
