# Multiuser-XR-Server

A small server application for multiuser XR applications. It is written with PHP and uses the SleekDB (flat file NoSQL DB) to store users' data.  

It comes with a client written with A-Frame. 

![Two users views](Test-1.gif)

## Development status:
- A user is created with each browser session. Its position and rotation, accompanied by an ID is stored in DB and updated when position is changed.
- Clients retrieve stored data and refresh the appearance of other users with avatars.

## Uses: 
### PHP
### Flat file DB https://sleekdb.github.io/
