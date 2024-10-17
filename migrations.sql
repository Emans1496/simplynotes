-- Creazione della tabella 'users'
CREATE TABLE IF NOT EXISTS users (
    id SERIAL PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    email VARCHAR(100) NOT NULL UNIQUE,
    password VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Creazione della tabella 'notes'
CREATE TABLE IF NOT EXISTS notes (
    id SERIAL PRIMARY KEY,
    user_id INTEGER NOT NULL REFERENCES users(id) ON DELETE CASCADE,
    title VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- Trigger per aggiornare automaticamente 'updated_at' su UPDATE
CREATE OR REPLACE FUNCTION update_updated_at_column()
RETURNS TRIGGER AS $$
BEGIN
   NEW.updated_at = NOW();
   RETURN NEW;
END;
$$ LANGUAGE 'plpgsql';

CREATE TRIGGER update_notes_updated_at
BEFORE UPDATE ON notes
FOR EACH ROW
EXECUTE PROCEDURE update_updated_at_column();

-- Inserimento dei dati iniziali
INSERT INTO users (username, email, password, created_at) VALUES
('testuser', 'testuser@example.com', 'hashed_password', CURRENT_TIMESTAMP);

INSERT INTO notes (user_id, title, content, created_at) VALUES
(1, 'Prima Nota', 'Questo è il contenuto della prima nota.', CURRENT_TIMESTAMP),
(1, 'Seconda Nota', 'Questo è il contenuto della seconda nota.', CURRENT_TIMESTAMP);
