# OpenChat
## INTRODUCTION
1. Run setup.sh to setup your database, and web-server data!
2. Code the table structure in MySQL:
```
USER:
+----------+---------+------+-----+---------+----------------+
| Field    | Type    | Null | Key | Default | Extra          |
+----------+---------+------+-----+---------+----------------+
| sel      | int(11) | NO   | PRI | NULL    | auto_increment |
| id       | text    | YES  |     | NULL    |                |
| fname    | text    | YES  |     | NULL    |                |
| timezone | text    | YES  |     | '00'    |                |
| setoff   | int(11) | YES  |     | NULL    |                |
| room     | int(11) | YES  |     | NULL    |                |
+----------+---------+------+-----+---------+----------------+

CHAT:
+--------+---------+------+-----+---------+----------------+
| Field  | Type    | Null | Key | Default | Extra          |
+--------+---------+------+-----+---------+----------------+
| id     | int(11) | NO   | PRI | NULL    | auto_increment |
| title  | text    | YES  |     | NULL    |                |
| rights | text    | YES  |     | NULL    |                |
| enc    | text    | YES  |     | 'NONE'  |                |
+--------+---------+------+-----+---------+----------------+

FILE:
+---------+---------+------+-----+---------+----------------+
| Field   | Type    | Null | Key | Default | Extra          |
+---------+---------+------+-----+---------+----------------+
| aktu    | int(11) | YES  |     | NULL    |                |
| type    | int(11) | YES  |     | NULL    |                |
| id      | int(11) | NO   | PRI | NULL    | auto_increment |
| ownname | text    | YES  |     | 'NONE'  |                |
| title   | text    | YES  |     | NULL    |                |
| rmid    | text    | YES  |     | NULL    |                |
+---------+---------+------+-----+---------+----------------+

MESSAGE:
+-------+---------+------+-----+---------+----------------+
| Field | Type    | Null | Key | Default | Extra          |
+-------+---------+------+-----+---------+----------------+
| id    | int(11) | NO   | PRI | NULL    | auto_increment |
| von   | text    | YES  |     | NULL    |                |
| nach  | text    | YES  |     | NULL    |                |
| text  | text    | YES  |     | NULL    |                |
| title | text    | YES  |     | NULL    |                |
| sd    | int(11) | YES  |     | NULL    |                |
+-------+---------+------+-----+---------+----------------+

SEEN:
+-------+---------+------+-----+---------+----------------+
| Field | Type    | Null | Key | Default | Extra          |
+-------+---------+------+-----+---------+----------------+
| id    | int(11) | NO   | PRI | NULL    | auto_increment |
| sd    | int(11) | YES  |     | NULL    |                |
| user  | int(11) | YES  |     | NULL    |                |
| type  | int(11) | YES  |     | 0       |                |
+-------+---------+------+-----+---------+----------------+

TELL:
+--------+---------+------+-----+---------+----------------+
| Field  | Type    | Null | Key | Default | Extra          |
+--------+---------+------+-----+---------+----------------+
| id     | int(11) | NO   | PRI | NULL    | auto_increment |
| text   | text    | YES  |     | NULL    |                |
| chatid | int(11) | YES  |     | NULL    |                |
| von    | text    | YES  |     | NULL    |                |
| hour   | text    | YES  |     | NULL    |                |
| time   | text    | YES  |     | NULL    |                |
| aktu   | int(11) | YES  |     | NULL    |                |
| sd     | int(11) | YES  |     | NULL    |                |
+--------+---------+------+-----+---------+----------------+
```
3. Add the follow database-entrys:
```
+-----+----------------------------------+------------+----------+------------+------+
| sel | id                               | fname      | timezone | setoff     | room |
+-----+----------------------------------+------------+----------+------------+------+
|   0 | 7cbff9f534bf023c49c773f3fdd33ba7 | SYSTEM     | 00       |          0 |    0 |
|   1 | 0                                | PFADSETTER | 00       |          0 |    0 |
+-----+----------------------------------+------------+----------+------------+------+
```
### Status: Version b1.0.0