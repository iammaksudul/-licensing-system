import requests
import sys

# Get the license key from the command line argument
license_key = sys.argv[1] if len(sys.argv) > 1 else None

if license_key:
    url = "http://yourdomain.com/api/validate_license.php"
    response = requests.post(url, data={"license_key": license_key})
    
    result = response.json()
    
    if result["status"] == "valid":
        print("License is valid.")
    else:
        print("License is invalid or expired.")
else:
    print("No license key provided.")
