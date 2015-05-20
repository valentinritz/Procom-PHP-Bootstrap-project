#!/bin/bash

artist=$(mpc -f "%artist%" | head -n 1)
title=$(mpc -f "%title%" | head -n 1)

notify-send "Now Playing" "$artist - $title"
