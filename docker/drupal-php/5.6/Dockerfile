FROM wodby/drupal-php:5.6-1.0.0
MAINTAINER brilsergei <brilsergei@gmail.com>
ARG HOST_UID=1000
ARG HOST_GID=1000

# Create group and user with same GID and UID as on host machine and assign it www-data group
RUN addgroup -g $HOST_GID host-group && \
	adduser -u $HOST_UID -D -G host-group host-user && \
	adduser host-user www-data

# Create work dir
RUN chown -R $HOST_UID:$HOST_GID /var/www

# Init host-user user
USER host-user
RUN composer global require hirak/prestissimo:^0.3 --optimize-autoloader && \
    rm -rf ~/.composer/.cache && \
    drupal init --override

USER root
COPY docker-entrypoint.sh /usr/local/bin/
CMD docker-entrypoint.sh
