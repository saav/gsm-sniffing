#!/bin/bash
args=("$@")
tshark -r captures/${args[0]} -T fields -e gsm_a.tmsi -e gsmtap.signal_dbm -e gsm_a.bssmap.cell_ci -e e212.mcc -e e212.mnc -e gsm_a.lac -E separator=, > captures/${args[1]}