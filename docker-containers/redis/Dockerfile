#FROM redis:5.0.7-alpine
FROM redis:6.0.5-alpine

MAINTAINER Dmitriy Isaev <isaevdimka@gmail.com>

RUN apk update && \
    apk add nano mc htop wget

RUN ln -snf /usr/share/zoneinfo/$TIMEZONE /etc/localtime && echo $TIMEZONE > /etc/timezone
