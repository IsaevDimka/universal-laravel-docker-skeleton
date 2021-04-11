FROM nginx:1.19.9-alpine

LABEL Description="nginx (a little bit customized)"

ARG TIMEZONE
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone

RUN apk update && \
    apk add nano mc htop wget