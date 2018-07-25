Provides files to configure local environment for development of train and tour sites.
It contains configuration files for docker-compose which are based on
[docker4drupal version 5.1.1](https://github.com/wodby/docker4drupal/tree/5.1.1).

**Prepare to use**

1. Install [docker](https://docs.docker.com/engine/installation/linux/ubuntulinux/) and
[docker compose](https://docs.docker.com/compose/install/) by following these instructions.

2. Clone this repository. Note that destination directory is not the site root.
  
    `git clone https://github.com/brilsergei/docker-for-work.git`

**Configure sites**

1. Docker4drupal allows to configure 1 global container with traefik which may handle multiple projects. For more
    details see [https://wodby.com/stacks/drupal/docs/local/multiple-projects/](https://wodby.com/stacks/drupal/docs/local/multiple-projects/).
   
    Both tours and trains projects directories should have the same parent directory. In order to use configuration
    files as is without any changes use 'tours' and 'trains' as directory names. Place file traefik configuration file
    at the projects parent directory.
   
    `cd projects-dir`
   
    `cp docker-for-work/docker-compose/traefik.yml ./`
   
    Note that if you use project directories names different from 'tours' and 'trains', you should replace these names
    in the traefik.yml configuration file.

2. Create new directories for the projects and clone project repositories to the created directories:

    Tours:
    
    `mkdir tours`
    
    `git clone git@bitbucket.org:travelallrussia/tours-8.git tours`

    Trains:
    
    `mkdir trains`

    `git clone git@bitbucket.org:travelallrussia/drupal-8.git trains`
       
3. Prepare configuration files for docker-compose:

    Tours:
        
    `cp docker-for-work/docker-compose/tour/* tours`
        
    `cp docker-for-work/docker-compose/tour/.* tours`
    
    Trains:
    
    `cp docker-for-work/docker-compose/train/* trains`
    
    `cp docker-for-work/docker-compose/train/.* trains`
    
    The files are ready to use. But some options can be changed to your convenience.
    
4. Create directory where you can place database backups so, that they will be available inside the mariadb container.
    
    Tours:
        
    `cd tours && mkdir docker-runtime && mkdir docker-runtime/mariadb-init`
    
    Trains:
    
    `cd trains && mkdir docker-runtime && mkdir docker-runtime/mariadb-init`
    
5. For tours and trains projects run command from the appropriate folder:
    
    `docker-compose up -d`
    
    After that run traefik container:
    
    `cd ../ && docker-compose -f traefik.yml up -d`
    
    Use it every time when you want to run them to make your sites available.
    
6.  Install all dependencies for trains site using Composer:
    
    `cd trains && docker-compose exec --user php composer install`
    
7.  Add new records to your /etc/hosts file to make the site domains matching to localhost:
    
    `127.0.0.1      rn.home`
    `127.0.0.1      rt.home`
    `127.0.0.1      rtt.home`
    `127.0.0.1      pma.rn.home`
    `127.0.0.1      portainer.rn.home`
    `127.0.0.1      ft.home`
    `127.0.0.1      tar.home`
    `127.0.0.1      pma.ft.home`
    `127.0.0.1      portainer.ft.home`
    
    Open [http://rn.home](http://rn.home), [http://rt.home](http://rt.home), [http://rtt.home](http://rtt.home),
    [http://ft.home](http://ft.home) or [http://tar.home](http://tar.home) in your browser, it must show Drupal
    installation page. Do not install Drupal, just make sure that all is working correctly.
    
    Do not change host names because they are hardcoded in configuration files.
    
8. Create databases for your sites. PhpMyAdmin is available at [http://pma.ft.home](http://pma.ft.home) for tours and
    [http://pma.rn.home](http://pma.rn.home) for trains with root login. Create databases `ft_1`, `tar_1` for tours and
    `rn_1`, `rt_1`, `rtt_1` for trains in PMA interface. It is recommended to create few databases for each site so, you
    can switch databases when work on different git branches at the same time.
    
    Download and import site databases using command line from mariadb container. Ask IT team for database dumps and
    place them at `docker-runtime/mariadb-init`. Login to mariadb and import the dumps:
    
    Tours
         
    `docker-compose exec mariadb sh`
        
    `mysql -u root -p ft_1 < /docker-entrypoint-initdb.d/ft-dev.sql`
        
    `mysql -u root -p tar_1 < /docker-entrypoint-initdb.d/tar-dev.sql`
    
    Trains
     
    `docker-compose exec mariadb sh`
    
    `mysql -u root -p rn_1 < /docker-entrypoint-initdb.d/rn-dev.sql`
    
    `mysql -u root -p rt_1 < /docker-entrypoint-initdb.d/rt-dev.sql`
    
    `mysql -u root -p rtt_1 < /docker-entrypoint-initdb.d/rtt-dev.sql`
    
9. Download site configs (ask IT team for it) and extract files into directory `dev-setttings` then copy configuration
    files and create necessary directories.
    
    Tours:
    
    `cp dev-settings/ft/* tours/sites/default && mkdir tours/sites/default/files`
        
    `cp dev-settings/tar/* tours/sites/travelallrussia.com && mkdir project-dir/sites/travelallrussia.com/files`
                
    `cp dev-settings/sites/* tours/sites`
    
    Trains:
    
    `cp dev-settings/rn/* trains/sites/default && mkdir project-dir/sites/default/files`
    
    `cp dev-settings/rt/* trains/sites/russiantrains.com && mkdir project-dir/sites/russiantrains.com/files`
    
    `cp dev-settings/rtt/* trains/sites/russiantraintickets.com && mkdir project-dir/sites/russiantrainickets.com/files`
    
    `cp dev-settings/sites/* trains/sites`
  
10. Drush, Drupal Console and Composer are installed inside the php images and will be available only from the php
    containers.
   
    `docker-compose exec --user 1000 php drush cr`
   
    `docker-compose exec --user 1000 php drupal list`
   
    `docker-compose exec --user 1000 php composer help`
   
    You can create aliases for these commands in ~/.bashrc file. Open ~/.bashrc in an editor, add next lines to the end
    of the file:
   
    `alias dc-drush='docker-compose exec --user 1000 php drush`
   
    `alias dc-drupal='docker-compose exec --user 1000 php drupal`
   
    `alias dc-composer='docker-compose exec --user 1000 php composer`
   
    So, now you can get Drush help, Composer help or list available commands for Console using:
   
    `dc-drush help`
   
    `dc-drupal list`
   
    `dc-composer help`
   
11. Place file with aliases for drush into site directory to simplify work with sites via drush.
   
    `mkdir project-dir/drush/site-aliases && cp dev-settings/drush/* project-dir/drush/site-aliases`
   
    Use site aliases to perform a drush command on a specific site. Aliases are `@ft`, `@tar`, `@rn`, `@rt` and `@rtt`.
    For example:
    
    `dc-drush @ft cc all` 
   
    `dc-drush @rn cr`
   
    `dc-drush @rtt cr`
   