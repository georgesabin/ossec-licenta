#!flask/bin/python

# Linux command run
import subprocess
from subprocess import call

# Others
import time

# Flask framework and other things
from flask import Flask, request, session, g, redirect, url_for, abort, render_template, flash, jsonify, make_response
from flask_mysqldb import MySQL
from flask_httpauth import HTTPBasicAuth
import os

app = Flask(__name__)

# MySQL Config
app.config['MYSQL_HOST'] = '192.168.114.132'
app.config['MYSQL_USER'] = 'root'
app.config['MYSQL_PASSWORD'] = 'sabingeorge95'
app.config['MYSQL_DB'] = 'smart_monitoring'

# Basic HTTP authentication
auth = HTTPBasicAuth()

@auth.get_password
def get_password(username):
	if username == 'sabin':
		return 'python'
	return None

@auth.error_handler
def unauthorized():
	return make_response(jsonify({'error': 'Unauthorized'}), 401)

# Instantiate MySQL
mysql = MySQL(app)

# Start/stop/restart ossec
@app.route('/server/<string:cmd_type>', methods=['GET'])
@auth.login_required
def server(cmd_type = None):
	if cmd_type is None:
		return make_response(jsonify({
			'response': 'Bad Request',
			'status_code': 400
		}), 400)
		
	message = ''
	if cmd_type == 'start':
		subprocess.check_output('sudo /var/ossec/bin/ossec-control start', shell=True)
		message = 'The OSSEC server was started!'
	elif cmd_type == 'stop':
		subprocess.check_output('sudo /var/ossec/bin/ossec-control stop', shell=True)
		message = 'The OSSEC server was stoped!'		
	elif cmd_type == 'restart':
		subprocess.check_output('sudo /var/ossec/bin/ossec-control restart', shell=True)
		message = 'The OSSEC server was restarted!'		
	
	return make_response(jsonify({
		'response': message
	}), 200)

# Get ossec.conf
@app.route('/server/confFile', methods=['GET'])
@auth.login_required
def conf_file():
	result = subprocess.check_output('sudo cat /var/ossec/etc/ossec.conf', shell=True)
	return make_response(jsonify({
		'response': result,
		'status_code': 200
	}), 200)

# Replace ossec.conf
@app.route('/server/replaceConfFile', methods=['POST'])
@auth.login_required
def replace_conf_file():
	if not request.json or 'server_conf' is None or not 'server_conf' in request.json:
		return jsonify({
			'status_code': 400,
			'response': 'Bad Request'
		}), 400

	# Add new settings in ossec.conf file
	echoConf = subprocess.Popen(['echo', request.json['server_conf']], stdout=subprocess.PIPE)
	subprocess.check_output(('sudo', 'tee', '/var/ossec/etc/ossec.conf'), stdin=echoConf.stdout)
	# Wait for child process to terminate
	echoConf.wait()

	# Restart the ossec server
	try:
		subprocess.check_output('sudo /var/ossec/bin/ossec-control restart', shell=True)
	except subprocess.CalledProcessError as e:
		print(e.output)
		return make_response(jsonify({
			'status_code': 404,
			'error': e.output
		}), 200)

	return make_response(jsonify({
		'status_code': 200 
	}), 200)

# Get ossec.log file
@app.route('/server/ossecLog', methods=['GET'])
@auth.login_required
def ossec_log():
	result = subprocess.check_output('sudo cat /var/ossec/logs/ossec.log', shell=True)
	return make_response(jsonify({
		'response': result,
		'status_code': 200
	}), 200)

# Add agent route
@app.route('/agent/add', methods=['POST'])
@auth.login_required
def add_agent():
	if not request.json or 'agent_name' is None or 'agent_ip' is None or not 'agent_name' in request.json or not 'agent_ip' in request.json:
		return jsonify({
			'status_code': 400,
			'response': 'Bad Request'
		}), 400

	smartMonitoring = mysql.connection.cursor()

	agent_name = request.json['agent_name']
	agent_ip = request.json['agent_ip']

	# Check if IP already exists in DB
	smartMonitoring.execute('SELECT CASE WHEN EXISTS (SELECT agent_name FROM smart_monitoring_agents WHERE agent_ip=\'' + agent_ip + '\') THEN \'Yes\' ELSE \'No\' END as agent_exists')

	rv = smartMonitoring.fetchall()

	if str(rv[0][0]) == 'Yes':
		return jsonify({
				'description': 'IP-ul ' + agent_ip + ' exista in baza de date!',
				'response': 'Conflict',
				'status_code': 409
			}), 409

	# Generate a file with IP and name agent
	subprocess.check_output('echo ' + agent_ip + ', ' + agent_name + ' > ip_name_agent.txt', shell=True)
	# Create the new agent and set the output
	try:
		agentResponse = subprocess.check_output('sudo /var/ossec/bin/manage_agents -f ip_name_agent.txt', shell=True)
	except subprocess.CalledProcessError as e:
		# Remove the file
		subprocess.check_output('sudo rm -r ip_name_agent.txt', shell=True)
		return make_response(jsonify({
				'description': e.output,
				'response': 'Not Found',
				'returncode': e.returncode,
				'status_code': 404,
			}), 404)

	# Check if the agent exists on server
	if 'Name \'' + agent_name + '\' already present.' in agentResponse:
		return jsonify({
			'description': agent_name + ' agent already present!',
			'response': 'Bad Request',
			'status_code': 409
		}), 409
 

	echoResponse = subprocess.Popen(['echo', agentResponse], stdout=subprocess.PIPE)
	stringAgentID = subprocess.check_output(('grep', 'ID:'), stdin=echoResponse.stdout)
	# Wait for child process to terminate
	echoResponse.wait()

	agent_id = stringAgentID.replace('ID:', '').strip()

	# Insert agent's dates in table
	currentDate = time.strftime('%Y-%m-%d %H:%M:%S')
	
	smartMonitoring.execute('INSERT INTO smart_monitoring_agents (agent_id, agent_name, agent_ip, agent_date_created) VALUES("' + agent_id + '", "' + agent_name + '", "' + agent_ip + '", "' + currentDate + '")')
	smartMonitoring.execute('COMMIT')

	agent = {
		'agent_id': agent_id,
		'agent_name': agent_name,
		'agent_ip': agent_ip
	}
	return jsonify({
		'agent': agent,
		'description': agentResponse,
		'response': 'Created',
		'status_code': 201
		}), 201

# Generate agent key
@app.route('/agent/key/<string:agent_id>', methods=['GET'])
@auth.login_required
def get_key_agent(agent_id = None):
	# Try to execute the command and if this returned an error, then return response with output and set 404 code status
	try:
		key = subprocess.check_output('sudo /var/ossec/bin/manage_agents -e ' + agent_id, shell=True)
	except subprocess.CalledProcessError as e:
		return make_response(jsonify({
				'error': 'Not Found',
				'response': e.output
			}), 404)

	return make_response(jsonify({
		'response': key
	}), 200)

# Remove agent
@app.route('/agent/remove/<string:agent_id>', methods=['DELETE'])
@auth.login_required
def remove_agent(agent_id = None):
	if agent_id is None:
		return make_response(jsonify({
			'response': 'Bad Request',
			'status_code': 400
		}), 400)

	# This var is used when try to remove the agent and grep return an error code
	# Grep return the error code just when agent_id still exists in OSSEC
	# If agent not exists in OSSEC, errorCode remain with same value
	errorCode = 0

	try:
		removed = subprocess.check_output('sudo /var/ossec/bin/manage_agents -r ' + agent_id + ' | grep "Invalid"', shell=True)
	except subprocess.CalledProcessError as e:
		errorCode = jsonify(e.returncode)
	
	# If grep cmd doesn't return an error and the 'Invalid' string exists in removed output
	if errorCode == 0 and 'Invalid' in removed:
		return make_response(jsonify({
			'error': 'Not Found',
			'response': 'Invalid ID ' + agent_id + ' given. ID is not present.'
		}), 404)

	# Restart OSSEC for changes to take effect
	subprocess.check_output('sudo /var/ossec/bin/ossec-control restart', shell=True)

	# REMOVE FROM TABLE -> flag = 1
	smartMonitoring = mysql.connection.cursor()
	smartMonitoring.execute('UPDATE smart_monitoring_agents SET agent_flag_removed=1 WHERE agent_id="' + agent_id + '"')
	smartMonitoring.execute('COMMIT')

	return make_response(jsonify({
		'response': 'Agent ' + agent_id + ' removed.' 
	}), 200)

# Restart agent
@app.route('/agent/restart/<string:agent_id>', methods=['GET'])
@auth.login_required
def restart_agent(agent_id = None):
	if agent_id is None:
		return make_response(jsonify({
			'response': 'Bad Request',
			'status_code': 400
		}), 400)
	# Restared agent
	subprocess.check_output('sudo /var/ossec/bin/agent_control -R ' + agent_id, shell=True)

	return make_response(jsonify({
		'response': 'The agent is restarted successfully!'
	}), 200)

# Get config for an agent
@app.route('/agent/get/config/<string:agent_id>/<string:agent_name>', methods=['GET'])
@auth.login_required
def agent_get_config(agent_id = None, agent_name = None):
	if agent_id is None or agent_name is None:
		return make_response(jsonify({
			'response': 'Bad Request',
			'status_code': 400
		}), 400)

	result = ''

	if os.path.exists('/var/www/html/files/config-files-agents/agent_' + agent_id + '_' + agent_name + '.conf') is True:
		result = subprocess.check_output('cat /var/www/html/files/config-files-agents/agent_' + agent_id + '_' + agent_name + '.conf', shell=True)
	print(result)
	return make_response(jsonify({
		'response': result
	}), 200)

# Create config file for an agent
@app.route('/agent/create/config', methods=['POST'])
@auth.login_required
def agent_create_config():
	if not request.json or not 'agent_id' in request.json or not 'agent_name' in request.json or not 'agent_conf' in request.json:
		return jsonify({
			'status_code': 400,
			'response': 'Bad Request'
		}), 400

	# Add new settings in ossec.conf file
	with open('/var/www/html/files/config-files-agents/agent_' + request.json['agent_id'] + '_' + request.json['agent_name'] + '.conf', 'w+') as myFile:
		myFile.write(request.json['agent_conf'])

	catAllConfs = subprocess.Popen('cat /var/www/html/files/config-files-agents/*', shell=True, stdout=subprocess.PIPE)
	subprocess.check_output(('sudo', 'tee', '/var/ossec/etc/shared/agent.conf'), stdin=catAllConfs.stdout)
	# Wait for child process to terminate
	catAllConfs.wait()

	# Restart OSSEC for changes to take effect
	subprocess.check_output('sudo /var/ossec/bin/ossec-control restart', shell=True)
	
	return make_response(jsonify({
		'response': 'The file is created!'
	}), 200)

@app.route('/')
def index():
    return "Python Module"

if __name__ == '__main__':
	# app.run(debug=True)
	app.run(host='192.168.114.132')
