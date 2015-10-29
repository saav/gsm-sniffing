#!/usr/bin/python

import csv
from datetime import datetime
import pymysql

filename = input("Enter a filename (.csv extension assumed): ") + '.csv'
f = open('captures/' + filename)
csv_f = csv.reader(f)
tmsi_list = []
signal_list = []
ci = ''
mcc = ''
mnc = ''
lac = ''

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

for i in range(0,length):
	tmsi = tmsi_list[i]
	signal = signal_list[i]
	last_seen = datetime.now().strftime("%Y-%m-%d %H:%M:%S")

	sql = "INSERT INTO cell_phone(tmsi, last_seen, signal_strength, lac, ci) \
	VALUES ('%s', '%s', '%d', '%d', '%d')" % \
	(tmsi, last_seen, int(signal), int(lac, 0), int(ci, 0))
	
	try:
		cursor.execute(sql)
		db.commit()
	except:
		db.rollback()

db.close()

