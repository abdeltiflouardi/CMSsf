Installation from an Archive
----------------------------

Download archive from here https://github.com/ouardisoft/CMSsf/archives/master

Unpack it somewhere under your web server root directory and run the following command.

    - cd CMSsf 
    - php bin/vendors install

Installation from Git
---------------------

We highly recommend you that you download the packaged version of this
distribution. If you still want to use Git, your are on your own.

Run the following commands:

    - git clone git@github.com:ouardisoft/CMSsf.git
    - cd CMSsf
    - php bin/vendors install

Configuration
-------------

Check that everything is working fine by going to the ``web/config.php`` page
in a browser and follow the instructions.

Configure the distribution by editing rename ``app/config/defaults_parameters.ini``  to ``app/config/parameters.ini``
You shoud add your database infos (server, user, password)

Run the following commands to generate database tables

    - php app/console doctirne:database:create
    - php app/console doctrine:schema:create
    - php app/console doctrine:fixtures:load

Enjoy!
