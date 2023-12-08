FROM ubuntu:22.04

RUN apt-get update -y && apt-get upgrade -y
RUN apt install build-essential -y && apt-get install manpages-dev -y &&  apt-get install socat -y &&  apt-get install gcc-multilib -y

WORKDIR /usr/src/myapp/

RUN useradd -m -G root user

COPY ./challange.c /usr/src/myapp/
RUN gcc -m32 challange.c -o challange -no-pie -fno-stack-protector

RUN chmod 755 challange

USER user
CMD echo "nland{G0_TO_J0NNy_And_Say_KinkoFe}" > /home/user/flag.txt
EXPOSE 1111
CMD socat TCP-LISTEN:1111,reuseaddr,fork,nodelay EXEC:"./challange",stderr
