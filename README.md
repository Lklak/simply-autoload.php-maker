# Autoload maker

Simply run the code of the file autoloadmaker.php, an autoload.php will be create in the same directory with the file autoloadmaker.php
The content in the autoload.php will be something like: <br />
require 'some/directory/yourfile.fex'; <br />
require 'some/dir/yourfile.fex';<br />
Modify the FILE_EX_GOES_HERE to php or something else, DIR2SCAN to the directory you would like to scan and the autoloadmaker will scan for all file that it's ex is YOUR DEFINED EX and it's in the DIR2SCAN.

# ATTENTION
For example my home dir is PUBLIC_HTML and I put my autoloadmaker.php in PUBLIC_HTML/some/dir And I replace the DIR2SCAN with "../pack1/", FILE_EX_GOES_HERE with "php" and I run the script, the output will besomething like: <br />
require '../pack1/src/somefile.php';<br />
require '../pack1/src/somf2.php';<br />
require '../pack1/home.php';<br />
require '../pack1/main.php';<br />
require '../pack1/pack.php';<br />
