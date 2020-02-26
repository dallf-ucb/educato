drop database if exists educato;

create database educato;

grant all privileges on educato.* to admin@'%' identified by 'system32';

flush PRIVILEGES;

use educato;

create table usuario (
    id bigint not null auto_increment,
    nombre varchar(255) not null, 
    clave varchar(255) not null, 
    rol varchar(25) not null,
    primary key(id)
);

insert into usuario (nombre, clave, rol) values 
('admin', sha1('123'), 'administrador'),
('sanieto', sha1('123'), 'administrativo'),
('dllano', sha1('123'), 'docente');