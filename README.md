Provides files to configure local environment for development of train and tour sites.
It contains source files for php images and base docker-compose.yml.

**Prepare to use**

1. Install [docker](https://docs.docker.com/engine/installation/linux/ubuntulinux/) and [docker compose](https://docs.docker.com/compose/install/) by following these instructions.

2. Clone this repository. Note that destination directory is not the site root.
  
    `git clone git@github.com:brilsergei/docker-for-work.git`

**Configure tour sites**

1. Compile php image.
    
    `docker build -t local/tour-php docker-for-work/drupal-php/5.6`
    
2. Prepare site root directory and run docker containers.

    `cp docker-for-work/docker-compose/tour/docker-compose.yml path/to/site-root`
    
    The file is ready to use. But some options can be changed to your convenience.
    
    Create directory where you can place database backup so, that it will be available inside the mariadb container.
    
    `cd path/to/site-root && mkdir docker-runtime && mkdir docker-runtime/mariadb-init`
    
    Start containers:
    
    `docker-compose up -d`
    
    Use it every time when you want to run them to make your sites available.
    
    Add new record to your /etc/hosts file to make the site domain matching to localhost.
    
    `127.0.0.1      ft.home`
    
    `127.0.0.1      tar.home`
    
    Here `ft.home` and `tar.home` corresponds to `PHP_HOST_NAME_*` and `NGINX_SERVER_NAME` options in the docker-compose.yml file.
    Open [http://ft.home:7000](http://ft.home:7000) in your browser, it must show `File not found` error.
    
3. Import backups, download site source code from repository and create settings.php file.

    PhpMyAdmin is available at [http://localhost:7001](http://localhost:7001). Place database backups at `path/to/site-root/docker-runtime/mariadb-init/` then run
    
    `docker-compose exec mariadb sh`
    
    to open shell inside the container. Here you can use command line mysql client to import database backups.
    They are placed at `/docker-entrypoint-initdb.d`. Enter `exit` for exit from the container shell.
    
    Now clone repository into the site root directory.

    `git clone git@bitbucket.org:sergei_bril/tours.git .`
    
    Use default.sites.php, example.settings.php, example.drushrc.php files as base to configure the sites. Database username is root, password can be configured in docker-compose.yml (default is root), host is mariadb.
    
4. Configure Drush.

    Drush is installed to the php image and will be available from the php container only.

    `docker-compose exec --user 1000 php drush help`
    
    You can create alias for this command in ~/.bashrc file. Open ~/.bashrc in a editor, add next line to the end of the file:
    
    `alias dc-drush='docker-compose exec --user 1000 php drush`
    
    So now you can get help or clear cache using next commands:
    
    `dc-drush help`
    
    `dc-drush @ft cc all`
    
    Site aliases `@ft` and `@tar` are configured inside the php container.
    
5. Don't forget about `dc-drush devify`.
    
**Configure train sites**

1. Compile php image.
       
    `docker build -t local/train-php docker-for-work/drupal-php/7.0`
       
2. Prepare site root directory and run docker containers.

    `cp docker-for-work/docker-compose/train/docker-compose.yml path/to/site-root`
    
    The file is ready to use. But some options can be changed to your convenience.
    
    Create directory where you can place database backup so, that it will be available inside the mariadb container.
    
    `cd path/to/site-root && mkdir docker-runtime && mkdir docker-runtime/mariadb-init`
    
    Start containers:
    
    `docker-compose up -d`
    
    Use it every time when you want to run them to make your sites available.
    
    Add new record to your /etc/hosts file to make the site domain matching to localhost.
    
    `127.0.0.1      rn.home`
    
    Here `rn.home` corresponds to `PHP_HOST_NAME_RN` and `NGINX_SERVER_NAME` options in the docker-compose.yml file.
    Open [http://rn.home:8000](http://rn.home:8000) in your browser, it must show `File not found` error.
  
3. Configure Drush and Console.

    Drush and Console are installed to the php image and will be available only from the php container.
   
   `docker-compose exec --user 1000 php drush cr`
   
   `docker-compose exec --user 1000 php drupal list`
   
   You can create aliases for these commands in ~/.bashrc file. Open ~/.bashrc in a editor, add next line to the end of the file:
   
   `alias dc-drush='docker-compose exec --user 1000 php drush`
   
   `alias dc-drupal='docker-compose exec --user 1000 php drupal`
   
   So now you can get Drush help or list available commands for Console using:
   
   `dc-drush help`
   
   `dc-drupal list`
   
4. Now clone the repository to the site root

    `git clone git@bitbucket.org:sergei_bril/train_booking.git .`
    
    and open [http://rn.home:8000](http://rn.home:8000) to install Drupal 8. Database username is root, password can be configured in docker-compose.yml (default it root). When configure database, set `mariadb` as database host in Advanced section.
    
    PhpMyAdmin is available at [http://localhost:8001](http://localhost:8001)`.
   