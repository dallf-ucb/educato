use educato;

drop table if exists usuario;
drop table if exists menu;
drop table if exists rol;
drop table if exists pagina;

create table rol (
    id bigint not null auto_increment,
    nombre varchar(255) not null, 
    created_at datetime, 
    updated_at datetime,
    visible bit not null default 1, 
    primary key(id)
);

insert into rol (nombre, created_at, updated_at) values 
('Administrador', now(), now()),
('Administrativo', now(), now()),
('Docente', now(), now()),
('Estudiante', now(), now());

create table usuario (
    id bigint not null auto_increment,
    nombre varchar(255) not null unique, 
    clave varchar(255) not null, 
    id_rol bigint not null,
    created_at datetime, 
    updated_at datetime,
    visible bit not null default 1, 
    primary key(id), 
    constraint fk_rol_usuario foreign key (id_rol) references rol (id)
);

insert into usuario (nombre, clave, id_rol, created_at, updated_at) values 
('admin', sha1('123'), 1, now(), now()),
('sanieto', sha1('123'), 2, now(), now()),
('dllano', sha1('123'), 3, now(), now()),
('perez', sha1('123'), 4, now(), now());

create table menu (
    id bigint not null auto_increment,
    texto varchar(25) not null, 
    tipo varchar(8) not null default 'link' check (tipo in ('link','dropdown','divider')), 
    href varchar(500), 
    id_rol bigint not null, 
    id_menu bigint default null, 
    created_at datetime, 
    updated_at datetime, 
    visible bit not null default 1, 
    primary key(id),
    constraint fk_rol_menu foreign key (id_rol) references rol (id),
    constraint fk_menu_padre foreign key (id_menu) references menu (id)
);

insert into menu 
(texto,         tipo,       href,                   id_rol, id_menu, created_at, updated_at) values 
('Acceso',      'dropdown', 'javascript:void(0)',   1,      null, now(), now()),
('Roles',       'link',     'rol/index',            1,      1,    now(), now()),
('Usuarios',    'link',     'usuario/index',        1,      1,    now(), now()),
('Páginas',     'link',     'pagina/index',         1,      null, now(), now()),
('Páginas',     'link',     'pagina/index',         2,      null, now(), now()),
('Páginas',     'link',     'pagina/index',         3,      null, now(), now()),
('Páginas',     'link',     'pagina/lista',         4,      null, now(), now());

create table pagina (
    id bigint not null auto_increment,
    resumen varchar(500) not null, 
    texto text not null, 
    tipo varchar(8) not null default 'pagina' check (tipo in ('pagina','noticia','evento')), 
    id_usuario bigint not null, 
    id_pagina bigint, 
    fecha datetime, 
    created_at datetime, 
    updated_at datetime,
    visible bit not null default 1, 
    primary key(id),
    constraint fk_usuario_pagina foreign key (id_usuario) references usuario (id),
    constraint fk_pagina_anterior foreign key (id_pagina) references pagina (id)
);