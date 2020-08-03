FROM nginx:1.19.0-alpine

MAINTAINER Dmitriy Isaev <isaevdimka@gmail.com>
LABEL Description="nginx (a little bit customized)"

ARG TIMEZONE
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone

RUN apk update && \
    apk add nano mc htop wget

