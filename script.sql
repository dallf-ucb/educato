use educato;

drop table if exists usuario;

create table usuario (
    id bigint not null auto_increment,
    nombre varchar(255) not null, 
    clave varchar(255) not null, 
    rol varchar(25) not null,
    created_at datetime, 
    updated_at datetime,
    visible bit not null default 1, 
    primary key(id)
);

insert into usuario (nombre, clave, rol, created_at, updated_at) values 
('admin', sha1('123'), 'administrador', now(), now()),
('sanieto', sha1('123'), 'administrativo', now(), now()),
('dllano', sha1('123'), 'docente', now(), now());

drop table if exists home;

create table home (
    id bigint not null auto_increment,
    sitio varchar(255) not null,
    tema varchar(255) not null, 
    url varchar(255) not null, 
    copyright varchar(255) not null,
    created_at datetime, 
    updated_at datetime,
    visible bit not null default 1, 
    primary key(id)
);

insert into home (sitio, tema, url, copyright, created_at, updated_at) values 
('Educato UCB', 'default', 'educato.org', 'UCB "San Pablo" Tarija', now(), now()),
('Chaguaya Tarija', 'chaguaya', 'chaguaya.org', 'Santuario de Chaguaya Tarija', now(), now());