#!/bin/bash
echo "Retrieving /"
curl -i -H "Accept: application/json" localhost:8000
echo -e "\n"

echo "POSTing a new score"
curl -i -H "Accept: application/json" -H "Content-Type: application/json" localhost:8000/score -d '{"score":{"name":"TestPlayer","difficulty":"medium","score":"999"}}'
echo -e "\n"
