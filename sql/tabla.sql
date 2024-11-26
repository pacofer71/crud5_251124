create table provincias(
    id int AUTO_INCREMENT PRIMARY KEY,
    nombre varchar(50) not null,
    color varchar(50) not null
);
create table users(
    id int AUTO_INCREMENT PRIMARY KEY,
    username varchar(24) unique not null,
    email varchar(100) unique not null,
    imagen varchar(240) not null,
    provincia_id int,
    constraint fk_user_prov FOREIGN KEY(provincia_id) REFERENCES provincias(id) on delete cascade
);