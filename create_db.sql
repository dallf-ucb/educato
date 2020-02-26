drop database if exists educato;

create database educato;

grant all privileges on educato.* to admin@'%' identified by 'system32';

flush PRIVILEGES;