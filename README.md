# php-zabbix-graph-viewer
php-zabbix-graph-viewer

Based on this one:
https://github.com/ngyuki/zabbix-graph-viewer

You need to change files **app.js** and **app.php** to match your zabbix url.

Didn't make checks for urls and json status yet then if you change url then clear the cache or check others errors if the app keeps to redirect to zabbix main homepage.

If you need to you can add the following config to apache:

```

Alias /graphs/fast /fast

<Directory "/fast">
    Options all
    Require all granted
</Directory>

```

in the example above the php-zabbix-graph-viewer web files are in the / fast directory

![Alt text](snapshot.png?raw=true "PHP Graph Viewer screen")
