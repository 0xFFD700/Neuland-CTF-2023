FROM alpine:edge
RUN apk update && apk upgrade
RUN apk add php
RUN apk add php-session 

WORKDIR /usr/src/myapp/

RUN addgroup -S phpuser && \
    adduser -S -G phpuser phpuser

COPY --chown=phpuser:phpuser odyssey .

USER phpuser

EXPOSE 80
CMD ["php", "-S", "0.0.0.0:80", "-c", "php.ini"]
