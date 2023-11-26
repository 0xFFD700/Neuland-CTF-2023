FROM alpine:edge
RUN apk update && apk upgrade
RUN apk add gcc socat musl-dev libc-dev

WORKDIR /usr/src/myapp/

RUN addgroup -S user && \
    adduser -S -G user user

COPY Secure4Sure.c .
RUN gcc Secure4Sure.c -o Secure4Sure

RUN chmod 755 Secure4Sure

USER user

EXPOSE 1111
CMD socat TCP-LISTEN:1111,reuseaddr,fork,nodelay EXEC:"./Secure4Sure",stderr


