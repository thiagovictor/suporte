#Projeto elaborado no Modulo Doctrine(Curso Trilhando caminho com PHP).

1 - Instalar
Clone o projeto
```
git clone https://github.com/thiagovictor/SilexAndDoctrine.git
```
Apos clonar, digite os comandos:
```
cd SilexAndDoctrine
php composer.phar self-update
php composer.phar install
```
#Agora vamos criar o Banco de dados.
Edit o arquivo bootstrap com os dados do seu banco e rode o comando para criação das tabelas.
```
bin\doctrine orm:schema-tool:create
```
Pronto. Agora, vamos iniciar o servidor PHP Built-in Server na pasta public
```
php -S localhost:8080 -t public
```
