FROM postgres:13.1-alpine

LABEL Description="PostgresSQL database container"

ARG TIMEZONE
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone

RUN apk update && \
    apk add nano mc htop wget