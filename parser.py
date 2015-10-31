#!/usr/bin/python
import sys
import csv
from datetime import datetime
import pymysql

filename = str(sys.argv[0])
f = open('captures/' + filename)
csv_f = csv.reader(f)
prev_tmsi_list = []
tmsi_list = []
signal_list = []
ci = ''
mcc = ''
mnc = ''
lac = ''
time_stamp = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
n = 0
r = 0

for row in csv_f:
	if row[0]:
		tmsi_list.append(row[0])
		if row[1]:
			if(row[1] and row[2]):
				tmsi_list.append(row[1])
				signal_list.append(int(row[2]))
				signal_list.append(int(row[2]))
			elif row[1]:
				signal_list.append(int(row[1]))
	if row[2] and not ci:
		ci = row[2]
	if row[3] and not mcc:
		mcc = row[3]
	if row[4] and not mnc:
		mnc = row[4]
	if row[5] and not lac:
		lac = row[5]

length = len(tmsi_list)

# Open database connection
db = pymysql.connect("localhost","root","","gsm")

# prepare a cursor object using cursor() method
cursor = db.cursor()

sql = "INSERT INTO cell_tower(mnc, mcc, lac, ci) \
VALUES ('%d', '%d', '%d', '%d')" % \
(int(mnc), int(mcc), int(lac, 0), int(ci, 0))

try:
	cursor.execute(sql)
	db.commit()
except:
	db.rollback()

#get last time stamp
sql = "SELECT c.last_seen FROM cell_phone c WHERE c.lac = '%d' AND c.ci = '%d' \
ORDER BY last_seen DESC LIMIT 1" %\
(int(lac, 0), int(ci, 0))

try:
	cursor.execute(sql)
	time_stamp = (cursor.fetchone())[0]
except:
	db.rollback()

#get list of tmsi at last time stamp
sql = "SELECT c.tmsi FROM cell_phone c \
WHERE c.lac = '%d' AND c.ci = '%d' AND c.last_seen = '%s'" %\
(int(lac, 0), int(ci, 0), time_stamp)

try:
	cursor.execute(sql)
	result = cursor.fetchall()
	for row in result:
		prev_tmsi_list.append(row[0])
except:
	db.rollback()

for item in prev_tmsi_list:
	if item in tmsi_list:
		r = r + 1

last_seen = datetime.now().strftime("%Y-%m-%d %H:%M:%S")
for i in range(0,length):
	tmsi = tmsi_list[i]
	signal = signal_list[i]

	sql = "INSERT INTO cell_phone(tmsi, last_seen, signal_strength, lac, ci) \
	VALUES ('%s', '%s', '%d', '%d', '%d')" % \
	(tmsi, last_seen, int(signal), int(lac, 0), int(ci, 0))

	try:
		cursor.execute(sql)
		n = n + 1
		db.commit()
	except:
		db.rollback()

sql = "INSERT INTO cell_connection(lac, ci, stamp, new, repeated) \
VALUES ('%d', '%d', '%s', '%d', '%d')" % \
(int(lac, 0), int(ci, 0), last_seen, n, r)

try:
	cursor.execute(sql)
	db.commit()
except:
	db.rollback()

#close db connection
db.close()

