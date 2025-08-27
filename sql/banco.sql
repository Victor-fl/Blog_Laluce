drop database if exists Blog_Laluce

create database Blog_Laluce if not exists;
use Blog_Laluce;

create table usuario
(
id int(11) not null auto_increment primary key,
nome varchar not null(50),
email varchar(225) not null,
senha char(60) not null,
data_criacao datetime not null default current_timestamp,
ativo tinyint(4) not null default '0',
adm tinyint(4) not null default '0'
);

create table post
(
id int (11) not null auto_increment primary key,
titulo varchar(225) not null,
texto text not null,
usuario_id int(11) not null,
data_criacao datetime not null default current_timestamp,
data_postagem datetime not null,
foreign key (usuario_id) references usuario (id)
);