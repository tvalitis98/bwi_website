import requests

robot_name = "bender"
timeout = 60

url = "http://localhost/robot_alive.php?robot_name=%s&timeout=%d" % (robot_name, timeout)
r = requests.get(url)

print r.status_code
print r.content
