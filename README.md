# Zipator
PHP Command line tool for creating zip archives.

Requirements
----------
PHP 5.6 or above

Usage
----------
1. Clone or download this repository
2. Download dependency ```composer intall```
3. Run ```php init.php -i e:/b``` see options below

Options
----------
* ```-i, --input <n>``` Path to input directory.
* ```-o, --output <n>``` Path to output directory.
* ```-s, --size <i>``` Max archive size in bytes.
* ```-e, --extension <s>``` Archive only files with given file extension.

For example: ```php init.php -e jpg -s 100000000 -i e:/b -o e:/b/c```
This command will archive only jpg files which are located in e:\b directory. Max size of one archive is proximately 100 MB. All zip archive will be created in e:\b\c directory.

License
----------
See the [LICENSE](LICENSE.md) file for license rights and limitations (MIT).
