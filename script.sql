use educato;

drop table if exists usuario;

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

drop table if exists home;

create table home (
    id bigint not null auto_increment,
    sitio varchar(255) not null,
    tema varchar(255) not null, 
    url varchar(255) not null, 
    copyright varchar(255) not null,
    primary key(id)
);

insert into home (sitio, tema, url, copyright) values 
('Educato UCB', 'default', 'educato.org', 'UCB "San Pablo" Tarija'),
('Chaguaya Tarija', 'chaguaya', 'chaguaya.org', 'Santuario de Chaguaya Tarija');