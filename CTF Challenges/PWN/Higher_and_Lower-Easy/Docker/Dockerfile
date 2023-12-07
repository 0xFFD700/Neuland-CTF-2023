FROM alpine:edge
RUN apk update && apk upgrade
RUN apk add gcc socat musl-dev libc-dev

WORKDIR /usr/src/myapp/

RUN addgroup -S user && \
    adduser -S -G user user

COPY higher_lower.c .
RUN gcc higher_lower.c -o higher_lower

RUN chmod 755 higher_lower

USER user

EXPOSE 1111

ENTRYPOINT ["/usr/bin/socat", "tcp-l:1111,reuseaddr,fork", "EXEC:'./higher_lower',pty,stderr"]
