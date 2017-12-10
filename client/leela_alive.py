import requests
import sched, time

robot_name = "leela"
timeout = 300
url = "http://localhost/robot_alive.php?robot_name=%s&timeout=%d" % (robot_name, timeout)
s = sched.scheduler(time.time, time.sleep)
def alive(sc): 
    r = requests.get(url)
    print r.status_code
    print r.content
    s.enter(180, 1, alive, (sc,))

s.enter(1, 1, alive, (s,))
s.run()

