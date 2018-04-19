Provides files to configure local environment for development of train and tour sites.
It contains source files for php images and base docker-compose.yml.

**Prepare to use**

1. Install [docker](https://docs.docker.com/engine/installation/linux/ubuntulinux/) and
[docker compose](https://docs.docker.com/compose/install/) by following these instructions.

2. Clone this repository. Note that destination directory is not the site root.
  
    `git clone https://github.com/brilsergei/docker-for-work.git`

**Configure tour sites**

1. Compile php image.
    
    `docker build -t local/tour-php docker-for-work/docker/drupal-php/5.6`
    
2. Prepare site root directory and run docker containers.

    `cp docker-for-work/docker-compose/tour/docker-compose.yml path/to/site-root`
    
    The file is ready to use, but some options can be changed to your convenience.
    
    Create directory where you can place a database backup so, that it will be available inside the mariadb container.
    
    `cd path/to/site-root && mkdir docker-runtime && mkdir docker-runtime/mariadb-init`
    
    Start containers:
    
    `docker-compose up -d`
    
    Use it every time when you want to run them to make your sites available.
    
    Add new record to your /etc/hosts file to make the site domain matching to localhost.
    
    `127.0.0.1      ft.home`
    
    `127.0.0.1      tar.home`
    
    Here `ft.home` and `tar.home` corresponds to `PHP_HOST_NAME_*` and `NGINX_SERVER_NAME` options in the
    docker-compose.yml file. Open [http://ft.home:7000](http://ft.home:7000) in your browser, it must show 
    `File not found` error.
    
3. Import backups, download site source code from repository and create settings.php file.

    PhpMyAdmin is available at [http://localhost:7001](http://localhost:7001). Place database backups at 
    `path/to/site-root/docker-runtime/mariadb-init/` then run
    
    `docker-compose exec mariadb sh`
    
    to open shell inside the container. Here you can use command line mysql client to import database backups. They are
    placed at `/docker-entrypoint-initdb.d`. Enter `exit` for exit from the container shell.
    
    Now clone repository into the site root directory.

    `git clone git@bitbucket.org:sergei_bril/tours.git .`
    
    Use default.sites.php, example.settings.php, example.drushrc.php files as base to configure the sites. Database
    username is root, password can be configured in docker-compose.yml (default is root), host is mariadb.
    
4. Configure Drush.

    Drush is installed to the php image and will be available from the php container only.

    `docker-compose exec --user 1000 php drush help`
    
    You can create alias for this command in ~/.bashrc file. Open ~/.bashrc with an editor and add the next line to the
    end of the file:
    
    `alias dc-drush='docker-compose exec --user 1000 php drush`
    
    So now you can get help or clear cache using next commands:
    
    `dc-drush help`
    
    `dc-drush @ft cc all`
    
    Site aliases `@ft` and `@tar` are configured inside the php container.
    
5. Don't forget about `dc-drush devify`.
    
**Configure train and triptile sites**

1. Working directory.

    Create new directory for project and clone project repository to the directory.

    `mkdir project-dir`

    `git clone git@bitbucket.org:travelallrussia/drupal-8.git project-dir`
       
2. Prepare the site root directory and run docker containers.

    `cp docker-for-work/docker-compose/train/docker-compose.yml project-dir`
    
    `cp docker-for-work/docker-compose/train/.env project-dir`
    
    `cp docker-for-work/docker-compose/train/Makefile project-dir`
    
    The files are ready to use. But some options can be changed to your convenience.
    
    Create directory where you can place database backup so, that it will be available inside the mariadb container.
    
    `cd project-dir && mkdir docker-runtime && mkdir docker-runtime/mariadb-init`
    
    Start containers:
    
    `docker-compose up -d`
    
    Use it every time when you want to run them to make your sites available.
    
    Add new record to your /etc/hosts file to make the site domains matching to localhost.
    
    `127.0.0.1      rn.home`
    `127.0.0.1      rt.home`
    `127.0.0.1      rtt.home`
    `127.0.0.1      pma.rn.home`
    `127.0.0.1      portainer.rn.home`
    
    Open [http://rn.home:8000](http://rn.home:8000), [http://rt.home:8000](http://rt.home:8000) or
    [http://rtt.home:8000](http://rtt.home:8000) in your browser, it must show Drupal installation page. Do not install
    Drupal, just make sure that all is working ok.
    
    Do not change host names because they are hardcoded in configuration files.
    
3. Create databases for your sites.

    PhpMyAdmin is available at [http://pma.rn.home:8000](http://pma.rn.home:8000) with root login. Create databases
    `rn_1`, `rt_1`, `rtt_1` in PMA interface. It is recommended to create few databases for each site so, you can switch
    databases when work on different git branches in parallel.
    
    Download and import site databases using command line from mariadb container. Ask IT team for database dumps,
    extract them at `docker-runtime/mariadb-init`. Login to mariadb and import the dumps.
     
    `docker-compose exec mariadb sh`
    
    `mysql -u root -p rn_1 < /docker-entrypoint-initdb.d/rn-dev.sql`
    
    `mysql -u root -p rt_1 < /docker-entrypoint-initdb.d/rt-dev.sql`
    
    `mysql -u root -p rtt_1 < /docker-entrypoint-initdb.d/rtt-dev.sql`
    
4. Place site configuration files.

    Download site configs (ask IT team for it) and extract files into directory `dev-setttings` then copy configuration
    files and create necessary directories.
    
    `cp dev-settings/rn/* project-dir/sites/default && mkdir project-dir/sites/default/files`
    
    `cp dev-settings/rn/* project-dir/sites/default && mkdir project-dir/sites/default/files`
    
    `cp dev-settings/rn/* project-dir/sites/default && mkdir project-dir/sites/default/files`
    
    `cp dev-settings/sites/* project-dir/sites`
  
5. Configure Drush, Drupal Console and Composer.

    Drush, Drupal Console and Composer are installed inside the php image and will be available only from the php
    container.
   
   `docker-compose exec --user 1000 php drush cr`
   
   `docker-compose exec --user 1000 php drupal list`
   
   `docker-compose exec --user 1000 php composer help`
   
   You can create aliases for these commands in ~/.bashrc file. Open ~/.bashrc in a editor, add next line to the end of
   the file:
   
   `alias dc-drush='docker-compose exec --user 1000 php drush`
   
   `alias dc-drupal='docker-compose exec --user 1000 php drupal`
   
   `alias dc-composer='docker-compose exec --user 1000 php composer`
   
   So now you can get Drush help, Composer help or list available commands for Console using:
   
   `dc-drush help`
   
   `dc-drupal list`
   
   `dc-composer help`
   
   Place file with aliases for drush into site directory to simplify work with sites via drush.
   
   `mkdir project-dir/drush/site-aliases && cp dev-settings/drush/* project-dir/drush/site-aliases`
   
   Use site aliases to perform a drush command on specific site. Aliases are `@rn`, `@rt` and `@rtt`. For example:
   
   `dc-drush @rn cr`
   
   `dc-drush @rtt cr`
   