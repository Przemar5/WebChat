CREATE TABLE users (
	id SERIAL PRIMARY KEY,
	login VARCHAR(255) NOT NULL,
	password VARCHAR (255) NOT NULL,
	email VARCHAR (255) NOT NULL,
	created_on TIMESTAMP NOT NULL,
	verifid BOOLEAN DEFAULT FALSE,
	last_login TIMESTAMP,
	deleted BOOLEAN DEFAULT FALSE
);

CREATE TABLE roles (
	id SERIAL PRIMARY KEY,
	name VARCHAR(255) UNIQUE NOT NULL
);

CREATE TABLE user_role (
	user_id INT NOT NULL,
	role_id INT NOT NULL,
	PRIMARY KEY (user_id, role_id),
	FOREIGN KEY (user_id) REFERENCES users (id),
	FOREIGN KEY (role_id) REFERENCES roles (id)
);

CREATE TABLE rooms (
	id SERIAL PRIMARY KEY,
	name VARCHAR(255) NOT NULL
);

CREATE TABLE messages (
	id SERIAL PRIMARY KEY,
	content VARCHAR(255) NOT NULL,
	author_id INT NOT NULL,
	room_id INT NOT NULL,
	created_at TIMESTAMP,
	FOREIGN KEY (author_id) REFERENCES users (id),
	FOREIGN KEY (room_id) REFERENCES rooms (id)
);

INSERT INTO roles (name) 
VALUES ('USER'), ('ADMIN'), ('OWNER');

INSERT INTO rooms (name) 
VALUES ('General');