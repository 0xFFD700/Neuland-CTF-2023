FROM alpine:edge
RUN apk update && apk upgrade
RUN apk add php
RUN apk add php-fileinfo

WORKDIR /usr/src/myapp/

COPY review .

RUN addgroup -S phpuser && \
    adduser -S -G phpuser phpuser && \
    chown -R root:root /usr/src/myapp && \
    chmod 755 /usr/src/myapp && \
    chown root:phpuser /usr/src/myapp/uploads && \
    chmod 1770 /usr/src/myapp/uploads # Set the sticky bit

COPY flag.txt /home/phpuser/
USER phpuser

EXPOSE 80
CMD ["php", "-S", "0.0.0.0:80", "-c", "php.ini"]

