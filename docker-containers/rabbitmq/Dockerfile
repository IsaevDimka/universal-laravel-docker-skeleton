FROM rabbitmq:3.8.9-management-alpine

LABEL Description="RabbitMQ + management container"

ARG TIMEZONE
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone