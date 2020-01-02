## NGINX

### Base
```
server {
    listen 80;
    server_name ...;
    root ...;
    index index.php index.html;

    location /admin {
        try_files $uri $uri/ /admin/index.html;
    }

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location ~ \.php$ {
        fastcgi_pass unix:/run/php/php7.3-fpm.sock;
        include fastcgi_params;
    }
    add_header 'Access-Control-Allow-Origin' '*';

    location ~ /\.ht {
        deny all;
    }
}
```
