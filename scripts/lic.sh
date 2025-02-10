#!/bin/bash

# Check if a license key is provided
if [ -z "$1" ]; then
    echo "No license key provided."
    exit 1
fi

LICENSE_KEY=$1
URL="http://yourdomain.com/api/validate_license.php"

# Send a POST request with the license key
RESPONSE=$(curl -s -X POST -d "license_key=$LICENSE_KEY" $URL)

# Parse the JSON response
STATUS=$(echo $RESPONSE | jq -r '.status')

if [ "$STATUS" == "valid" ]; then
    echo "License is valid."
else
    echo "License is invalid or expired."
fi
