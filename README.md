# HLC TO06

## Dominio usado

http://165.227.158.59/crudphpalumnos/

## Características del alojamiento usado

VPS en Digital Ocean.
Droplet con LEMP preinstalado 4 GB RAM & 80 GB SSD

## Configuración de la aplicación en el servidor

MySQL 8.0.23

- ### Creación de usuario
    - CREATE USER 'alumno'@'localhost' IDENTIFIED WITH mysql_native_password BY '032699aA$';
    - GRANT ALL ON *.alumnosDB TO 'alumno'@'localhost';
    - FLUSH PRIVILEGES;
- ### Creación de la base de datos
create table alumnos
(
id int auto_increment
primary key,
nombre varchar(30) not null,
apellidos varchar(60) not null,
email varchar(60) not null,
telefono varchar(14) not null,
fecha_nacimiento date not null
);

create table notas
(
id int auto_increment
primary key,
asignatura varchar(30) not null,
nota int not null,
observaciones varchar(100) null,
alumnoid int not null,
constraint notas_ibfk_1
foreign key (alumnoid) references alumnos (id)
on update cascade on delete cascade
);

- Datos de acceso:
  ![enter image description here](https://i.imgur.com/5p30fJU.png)


- Info sobre las tablas:
  ![enter image description here](https://i.imgur.com/VtIRtkr.png)


- Permisos de los ficheros:
  ![enter image description here](https://i.imgur.com/0b6ZWXe.png)


