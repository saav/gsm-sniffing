#!/bin/bash
args=("$@")
tshark -r capture/${args[0]} -T fields -e gsm_a.tmsi -e gsmtap.signal_dbm -e gsm_a.bssmap.cell_ci -e e212.mcc -e e212.mnc -e gsm_a.lac -E separator=, > capture/${args[1]}