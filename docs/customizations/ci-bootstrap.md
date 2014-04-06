1. Clone https://github.com/sjlu/CodeIgniter-Bootstrap

2. Remove unnecessary folders:
```
rm -rf application/libraries/{aws,fb,rs}
rm application/libraries/{Aws,Fb,Cloudfiles,Curl,Fb,Format,Mongo_db,REST_Controller}.php
rm application/config/{aws,cloudfiles,fb,rest,mongodb}.php
rm application/controllers/unit.php
rm application/models/unit_model.php
rm -rf application/views/templates
```
