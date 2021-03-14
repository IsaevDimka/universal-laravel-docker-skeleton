FROM mongo:4.4.3

LABEL Description="MongoDB container"

RUN apt-get update && apt-get install -y --no-install-recommends \
  wget \
  nano \
  mc \
  htop

ARG TIMEZONE
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone