FROM mongo:4.2.8

MAINTAINER Dmitriy Isaev <isaevdimka@gmail.com>

RUN apt-get update && apt-get install -y --no-install-recommends \
  wget \
  nano \
  mc \
  htop

RUN ln -snf /usr/share/zoneinfo/$TIMEZONE /etc/localtime && echo $TIMEZONE > /etc/timezone
