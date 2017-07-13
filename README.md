# Calendar-Maker
[![Build Status](https://travis-ci.org/NHSTechTeam/Calendar-Maker.svg?branch=master)](https://travis-ci.org/NHSTechTeam/Calendar-Maker)

Newtown High School Calendar Maker

Database Setup:
--------
### Early Dismissal
##### Type 1
All early dismissal days are simply the same as normal days, but marked with a `1` instead of a `0`
- Examples: `A`, `B`, `C`, `D`, `E`, `F`, `G`, `H`.

### Two Hour Delays
##### Type 2
All Two Hour Delay days should be labeled in the format `Letter Day`TD
- Examples: `ATD`, `BTD`, `CTD`, `DTD`, `ETD`, `FTD`, `GTD`, `HTD`.

### Extended Advisory
##### Type 0
All Extended Advisory days should be labeled in the format EA`Letter Day`
- Examples: `EAA`, `EAB`, `EAC`, `EAD`, `EAE`, `EAF`, `EAG`, `EAH`.

### PLC Days
##### Type 2
All PLC days should be labeled in the format `Letter Day`PL
- Examples: `APL`, `BPL`, `CPL`, `DPL`, `EPL`, `FPL`, `GPL`, `HPL`.

### Midterms
##### Type 3
All Midterms should be labeled in the format M`Day 1-4`
- Examples: `M1`, `M2`, `M3`, `M4`.

### Finals
##### Type 3
All Finals should be labeled in the format Y`Day 1-4`
- Examples: `Y1`, `Y2`, `Y3`, `Y4`.
