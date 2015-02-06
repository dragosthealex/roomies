#!/usr/bin/python
import MySQLdb


db = MySQLdb.connect(host="dbhost.cs.man.ac.uk", # your host, usually localhost
                     user="mbax4dr2", # your username
                      passwd="vac0metru", # your password
                      db="2014_comp10120_x7") # name of the data base

# you must create a Cursor object. It will let
#  you execute all the queries you need
cur = db.cursor()

# Use all the SQL you like
cur.execute("SELECT * FROM rusers")

# print all the first cell of all the rows
for row in cur.fetchall() :
    print row[0]
