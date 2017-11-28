import requests

name = "bender"
alive = "false"
floor = 4

url = "http://localhost/process.php?name=%s&alive=%s&floor=%d" % (name, alive, floor)
r = requests.get(url)

print r.status_code
print r.content
