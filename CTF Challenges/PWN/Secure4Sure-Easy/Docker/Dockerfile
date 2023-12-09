FROM ubuntu:latest
RUN apt-get update -y && \
    apt-get install -y --no-install-recommends \
        build-essential socat
RUN useradd -ms /bin/nologin user
WORKDIR /usr/src/myapp/

COPY Secure4Sure.c .
RUN gcc Secure4Sure.c -o Secure4Sure

RUN chmod 755 Secure4Sure

USER user

EXPOSE 1111

ENTRYPOINT ["/usr/bin/socat", "tcp-l:1111,reuseaddr,fork", "EXEC:'./Secure4Sure',pty,stderr"]
