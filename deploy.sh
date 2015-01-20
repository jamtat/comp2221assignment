#!/bin/bash

ssh $1@vega.dur.ac.uk "cd ~/public_html/comp2221assignment; rm -rf *"
rsync -r * $1@vega.dur.ac.uk:~/public_html/comp2221assignment
ssh $1@vega.dur.ac.uk "~/per"
