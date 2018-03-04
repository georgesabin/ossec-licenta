#!flask/bin/python

#import os
#import sqlite3
from flask import Flask
#, request, session, g, redirect, url_for, abort, render_template, flash
from flask_mysqldb import MySQL

app = Flask(__name__)
app.config['MYSQL_HOST'] = '192.168.114.132'
app.config['MYSQL_USER'] = 'root'
app.config['MYSQL_PASSWORD'] = 'sabingeorge95'
app.config['MYSQL_DB'] = 'smart_monitoring'

mysql = MySQL(app)

@app.route('/')

def index():
	cur = mysql.connection.cursor()
	cur.execute('CREATE TABLE IF NOT EXISTS test (id INT(6) AUTO_INCREMENT PRIMARY KEY, firstname VARCHAR(30) NOT NULL)')
       	return "Hello, World!"

if __name__ == '__main__':
#        app.run(debug=True)
	app.run(host='192.168.114.132')
