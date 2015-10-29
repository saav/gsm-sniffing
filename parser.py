#!/usr/local/bin/python3.4
import csv

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

print (tmsi_list)
print (len(tmsi_list))
print (signal_list)
print (len(signal_list))
print (mcc)
print (mnc)
print (lac)
print (ci)