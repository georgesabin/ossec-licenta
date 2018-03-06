#!flask/bin/python

# Linux command run
import subprocess
from subprocess import call

#import os
#import sqlite3

# Flask framework and other things
from flask import Flask, request, session, g, redirect, url_for, abort, render_template, flash, jsonify
from flask_mysqldb import MySQL

app = Flask(__name__)

# MySQL Config
app.config['MYSQL_HOST'] = '192.168.114.132'
app.config['MYSQL_USER'] = 'root'
app.config['MYSQL_PASSWORD'] = 'sabingeorge95'
app.config['MYSQL_DB'] = 'smart_monitoring'

# Instantiate MySQL
mysql = MySQL(app)

# Add agent route
@app.route('/agent/add', methods=['POST'])
def add_agent():
	if not request.json or not 'agent_name' in request.json or not 'agent_ip' in request.json:
		return jsonify({
			'status_code': 400,
			'response': 'Bad Request'
		}), 400

	smartMonitoring = mysql.connection.cursor()

	agent_name = request.json['agent_name']
	agent_ip = request.json['agent_ip']

	# Generate a file with IP and name agent
	subprocess.check_output('sudo sh -c "echo ' + agent_ip + ', ' + agent_name + ' > ip_name_agent.txt"', shell=True)

	# Create the new agent and set the output
	agentResponse = subprocess.check_output('sudo /var/ossec/bin/manage_agents -f ip_name_agent.txt', shell=True)
	# Remove the file
	subprocess.check_output('sudo rm -r ip_name_agent.txt', shell=True)

	# Check if the agent exists on server
	if 'Name \'' + agent_name + '\' already present.' in agentResponse:
		return jsonify({
			'description': agent_name + ' agent already present!',
			'response': 'Bad Request',
			'status_code': 400
		}), 400
 

	echoResponse = subprocess.Popen(['echo', agentResponse], stdout=subprocess.PIPE)
	stringAgentID = subprocess.check_output(('grep', 'ID:'), stdin=echoResponse.stdout)
	# Wait for child process to terminate
	echoResponse.wait()

	agent_id = stringAgentID.replace('ID:', '').strip()

	# a = subprocess.check_output(('sed', '-e', 's/^ID://'), stdin=t.stdout)
	# Wait for child process to terminate
	# t.wait()
	# v = subprocess.check_output(('sed', 's/\/n//'), stdin=a.stdout, shell=True)
	# Wait for child process to terminate
	# a.wait()

	# Insert agent's dates in table
	smartMonitoring.execute('INSERT INTO smart_monitoring_agents (agent_id, agent_name, agent_ip) VALUES("' + agent_id + '", "' + agent_name + '", "' + agent_ip + '")')
	smartMonitoring.execute('COMMIT')

	agent = {
		'agent_id': agent_id,
		'agent_name': agent_name,
		'agent_ip': agent_ip
	}
	return jsonify({
		'agent': agent,
		'response': agentResponse
		}), 201

@app.route('/agent/key', methods=['GET'])
def get_key_agent():
	return ''

@app.route('/')
def index():
	#process = subprocess.Popen(['touch', 'ip_name_agent.txt'], stdout=subprocess.PIPE)
	#stdout = process.communicate()[0]
	#print 'STDOUT:{}'.format(stdout)
	#return process.poll()
	#while True:
		#outt = process.stdout.readline()
		#if outt == '' and process.poll() is not None:
		#	break
		#if outt:
		#	print outt.strip()
	#	rc = process.poll()
	# cur = mysql.connection.cursor()
	# cur.execute('CREATE TABLE IF NOT EXISTS test (id INT(6) AUTO_INCREMENT PRIMARY KEY, firstname VARCHAR(30) NOT NULL)')
    return "Hello, World!"

if __name__ == '__main__':
#        app.run(debug=True)
	app.run(host='192.168.114.132')
