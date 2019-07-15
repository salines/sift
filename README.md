# Salines/Sift plugin for CakePHP

Sift is a CakePHP ^3.7 plugin that helps you to uncover empty and unmanaged tables and columns in your database.

## Installation

You can install this plugin into your CakePHP application using [composer](https://getcomposer.org).

The recommended way to install composer packages is:

```
composer require --dev salines/sift:dev-master
```
Add or move `$this->addPlugin('Salines/Sift');` in `Application.php` to:

```
protected function bootstrapCli()
{
    // other plugins ..
    $this->addPlugin('Salines/Sift');
}
```
    


## Usage

```
bin/cake sift
```


### Options

- ```-t```  select specific table. Example ```bin/cake sift -t users```. Default: all
- ```-c```  choose DB connections. Example ```bin/cake sift -u remote```. Default: default

```
bin/cake sift -t users -c remote
```
Example output show unused (empty || NULL) columns in users table:
```
+------------------------------------------------+
| TABLE users                                    |
+------------------------------------------------+
| - status                                       |
| - acitvated                                    |
+------------------------------------------------+
```

Example output show unused (empty) columns favorites table:

```
+------------------------------------------------+
| TABLE favorites                                |
+------------------------------------------------+
| >> Empty table                                 |
+------------------------------------------------+
```


## License

The MIT License (MIT)

Copyright (c) 2019 Salines

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

