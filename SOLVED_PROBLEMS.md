Solved problems
===============

1) Clearing cache
-----------------
Symfony2 work with cache. You could have any problem with permissions because the web server will work with an user and bash with other.

0) Try the command 'app/console cache:clear' if you have any problem you can solve with the next steps.

1) Clear your cache and logs directories:

```bash
rm -fr app/cache/*
rm -fr app/logs/*
```

2) Search the web server user on /etc/apache2/httpd.conf with the 'User' variable.

3) Change permissions of the directories with those commands:

```bash
sudo chmod +a "www-data allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs
sudo chmod +a "`whoami` allow delete,write,append,file_inherit,directory_inherit" app/cache app/logs
```
You should change 'www-data' with the user name from point 2.
