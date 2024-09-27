import re

def validate_password(password):
    # Regular expression for password validation
    pattern = r"^(?=.*[A-Z])(?=.*[a-z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]{8,}$"
    
    if re.match(pattern, password):
        return True
    else:
        return False

# Example usage
password = input("Enter a password: ")
if validate_password(password):
    print("Password is valid.")
else:
    print("Password is invalid. Ensure it meets the criteria.")
