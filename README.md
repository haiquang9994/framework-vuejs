## NGINX

### Base
```
server {
    listen 80;
    server_name ...;
    root ...;

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php7.3-fpm.sock;
        include fastcgi_params;
    }
}
```

### For Production
```
server {
    ...

    location /admin {
        try_files $uri $uri/ /admin/index.html;
    }
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
    ...
```

### For Development
```
server {
    ...

    location /admin {
        try_files $uri $uri/ /admin/index.html;
    }
    location / {
        if ($request_method = 'OPTIONS') {
            add_header 'Access-Control-Allow-Origin' '*';
            add_header 'Access-Control-Allow-Methods' 'GET, POST, PUT, DELETE, OPTIONS';
            add_header 'Access-Control-Allow-Headers' 'Content-Type,Authorization';
            return 200;
        }
        try_files $uri $uri/ /index.php?$query_string;
    }
    add_header 'Access-Control-Allow-Origin' '*';

    location ~ \.php$ {
    ...
```
