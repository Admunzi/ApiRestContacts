# Api Rest Contact

| Method | Path | Action | Body
| --- | --- | --- | --- |
| GET | /contacts | Select all contacts|
| GET ? X | /contacts/ {id} | Select one contacts|
| POST | /contacts | Insert one contact | {"name": "Juan","tlf": "676829402","mail": "juan@gmail.com"} |
| PUT | /contacts/ {id} | Update one contact | {"name": "Alex","tlf": "555658694","mail": "raul@gmail.com"} |
| DELETE | /contacts/ {id} | Delete one contact |

