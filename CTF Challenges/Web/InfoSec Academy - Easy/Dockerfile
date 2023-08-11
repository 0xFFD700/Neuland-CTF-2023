FROM alpine:edge
RUN apk update && apk upgrade
RUN apk add php

WORKDIR /usr/src/infosec-academy/

COPY infosec-academy .

RUN addgroup -S infosec-academy && \
    adduser -S -G infosec-academy infosec-academy && \
    chown -R root:root /usr/src/infosec-academy && \
    chmod 755 /usr/src/infosec-academy

COPY flag.txt /home/infosec-academy/
USER infosec-academy

EXPOSE 80
CMD ["php", "-S", "0.0.0.0:80", "-c", "php.ini"]

