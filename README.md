# ToDo App

## Setup
(It is assumed that the reader already has PHP, Symfony, Composer, Docker, NPM, and Angular CLI installed)  
Clone the repository and open the terminal in the root folder.  

To start the back-end server, write  
```
cd api
composer install
docker compose up -d
symfony server:start
```
This will install the project dependencies, start the DB server in Docker, and then start the back-end PHP server.

To start the front-end server, write  
```
cd client
npx http-server -o
```
This will start open a browser window connected to `http://127.0.0.1:8080/`, where the server is hosted.
