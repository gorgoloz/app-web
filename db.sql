CREATE DATABASE IF NOT EXISTS diario_lettura;
USE diario_lettura;

CREATE TABLE IF NOT EXISTS utenti (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL
);

CREATE TABLE IF NOT EXISTS libri (
    id INT AUTO_INCREMENT PRIMARY KEY,
    utente_id INT NOT NULL,
    titolo VARCHAR(100) NOT NULL,
    pagina_attuale INT DEFAULT 0,
    note TEXT,
    data_inserimento TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (utente_id) REFERENCES utenti(id)
);
