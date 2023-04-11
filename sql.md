sudo service apache2 start
sudo service postgresql start
cd ../../var/www/html/CIR_WebProject
sudo -u postgres psql
create role web login password 'password';
create database db_webproject owner web;