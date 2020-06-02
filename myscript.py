import string
import random
from hashlib import sha256

def id_generator(size=6, chars=string.ascii_uppercase + string.digits):
    return ''.join(random.choice(chars) for _ in range(size))
id=id_generator()

app_id = "HAZX1A6SDRWD6Z1XHO8WO3B7" #(Please refer to required parameters section)
campaign_name = id
api_secret = "ND0B2QQ2BTV9" # This is the sample key. Each app has a different secret key
signature_key = app_id+'|'+ campaign_name+'|'+ api_secret
signature = sha256(signature_key.encode('utf-8')).hexdigest()


print(id+","+signature)
