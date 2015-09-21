Host it!
======

## About

Host it! is an app in order to create virtual hosts on windows platforms.
You only have to provide ServerName and DocumentRoot in order to create a virtual host.
Your information will be added automatically to hosts and virtual host config file.

You can list available virtual hosts, remove one by providing the server name and clear all hosts where the document root is no longer available.

This application is based on symfony2 and twitter bootstrap.

If you find any bugs or if you have any ideas to improve this app, please leave an issue or a message.

© [Nick Böcker](http://nick-hat-boecker.de/ "Author's page") 2015

## Features

* List virtual hosts
* Add virtual host
* Change document root afterwards
* Remove virtual host
* If desired all files in document root will be deleted when removing a virtual host
* Clear virtual hosts where document root no longer exists
* Use command line commands or web interface
* Sort virtual hosts list in web interface
* Settings can be configured via web interface or parameters.yml (see below)

## Installation

Download the current tag and execute the following command:
```php
$ php app/console composer:install
```

You will be asked for path for hosts and virtual host config file.

On unix systems the hosts file is located at /etc/hosts. But on windows its C:/Windows/System32/drivers/etc/hosts.

**!Warning:**
It's important that you are permitted to write to the hosts file.

The path of the virtual host config file depends on your software. I'm using EasyPHP for example, but I know lots of people using xampp as well. If you are using another software, please let me know, so I can add an exemplary path.

**EasyPHP**:
```
C:/EasyPHP/data/conf/apache_virtual_hosts.conf
```

**Xampp**:
```
C:/xampp/apache/conf/extra/httpd-vhosts.conf
```

**!Info:**
You can set these paths via web interface, too.

## Commands

### List
```php
$ php app/console nhb:host-it:list-virtual-hosts
```

### Create
```php
$ php app/console nhb:host-it:add-virtual-host
```

For xampp it would be
```
$ php app/console nhb:host-it:add-virtual-host www.test.core C:/xampp/htdocs/test.
```

You have to restart your apache after this command manually.

### Remove
```php
$ php app/console nhb:host-it:remove-virtual-host
```

### Edit document root
```php
$ php app/console nhb:host-it:edit-virtual-host
```

### Clear
```php
$ php app/console nhb:host-it:clear-virtual-hosts
```

## Planned features

* Provide more arguments for virtual host entry e.g restriction
* Provide Icons for sort order

License
----

MIT
