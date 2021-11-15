<?php

return [
    'init' => "
        CREATE TABLE IF NOT EXISTS Tasks (
            id VARCHAR(36) NOT NULL PRIMARY KEY,
            user VARCHAR(32) NOT NULL,
            email VARCHAR(32) NOT NULL,
            description VARCHAR(4096) NOT NULL,
            edited INT,
            done INT
        );
    ",
    'fixture' => "
        INSERT INTO Tasks
            (id, user, email, description, edited, done)
        VALUES
            (1, 'user1', 'email1@example.com', '<strong>description1</strong>', 0, 0),
            (2, 'user2', 'email2@example.com', 'description2', 0, 1),
            (11, 'user1', 'email1@example.com', 'description11', 1, 0),
            (12, 'user2', 'email2@example.com', 'description12', 1, 1),
            (31, 'user3', 'email3@example.com', 'description3', 1, 0),
            (32, 'user3', 'email3@example.com', 'description32', null, null),
            (13, 'user1', 'email1@example.com', 'description13', null, null);
    ",
];
