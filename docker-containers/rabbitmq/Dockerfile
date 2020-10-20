FROM rabbitmq:3.8.9-management-alpine

MAINTAINER Dmitriy Isaev <isaevdimka@gmail.com>
LABEL Description="RabbitMQ + management container"

ARG TIMEZONE
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone