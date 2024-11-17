database query SQL
==================
drop database nabungyuk;
create database nabungyuk;
use nabungyuk;
create table user(idUser int key auto_increment, email varchar(50), username varchar(50), password varchar(255));
create table pemasukan(idPemasukan int key auto_increment, user int(11), nama varchar(50), jumlah int(50), nominal int(100), total int(100), created_at datetime);
create table pengeluaran(idPengeluaran int key auto_increment, user int(11), nama varchar(50), jumlah int(50), nominal int(100), total int(100), created_at datetime);
create table saldo(idSaldo int key auto_increment, user int(11), nominal int(100), catatan text, created_at datetime);



truncate pemasukan;
truncate pengeluaran;
truncate saldo;
