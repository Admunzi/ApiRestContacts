# Api Rest Contact

## BD db_contacts.sql

| Method | Path | Action | Body
| --- | --- | --- | --- |
| GET | /login | User login (user,password)| {"user": "admin","psw": "admin"} |
| GET | /contacts | Select all contacts|
| GET | /contacts/ {id} | Select one contacts|
| POST | /contacts | Insert one contact | {"name": "Juan","tlf": "676829402","mail": "juan@gmail.com"} |
| PUT | /contacts/ {id} | Update one contact | {"name": "Alex","tlf": "555658694","mail": "raul@gmail.com"} |
| DELETE | /contacts/ {id} | Delete one contact |

