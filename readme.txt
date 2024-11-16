database query SQL
==================
drop database nabungyuk;
create database nabungyuk;
use nabungyuk;
create table user(idUser int key auto_increment, email varchar(50), username varchar(50), password varchar(50));
create table pemasukan(idPemasukan int key auto_increment, nama varchar(50), jumlah int(50), nominal int(100), created_at date);
create table pengeluaran(idPengeluaran int key auto_increment, nama varchar(50), jumlah int(50), nominal int(100), created_at date);