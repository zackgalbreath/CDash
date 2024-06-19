FROM ubuntu:mantic AS cdash

ARG BASE_IMAGE=debian
ARG DEBIAN_FRONTEND=noninteractive
ENV TZ=Etc/UTC

ARG DEVELOPMENT_BUILD=0

RUN apt-get update && \
    apt-get install -y \
        libapache2-mod-php \
        php-bcmath \
        php-bz2 \
        php-gd \
        php-ldap \
        php-mysql \
        php-pgsql \
        php-xsl && \
    apt-get clean

COPY --from=kitware/cdash:latest /cdash /cdash

COPY ./docker/cdash-site.conf /etc/apache2/sites-available/cdash-site.conf
RUN sed -i 's/Listen 80/Listen 8080/g' /etc/apache2/ports.conf

# TODO: REMOVE THIS
COPY ./.env.dev /cdash/.env
COPY ./docker/docker-entrypoint.sh /cdash/docker/docker-entrypoint.sh
COPY ./install.sh /cdash/install.sh

RUN a2dissite 000-default && \
    a2ensite cdash-site && \
    a2enmod rewrite

WORKDIR /cdash

ENV BASE_IMAGE=$BASE_IMAGE

ENTRYPOINT ["/bin/bash", "/cdash/docker/docker-entrypoint.sh"]
CMD ["start-website"]
