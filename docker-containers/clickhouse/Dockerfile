FROM yandex/clickhouse-server

MAINTAINER Dmitriy Isaev <isaevdimka@gmail.com>
LABEL Description="Yandex Clickhouse container"

ARG TIMEZONE
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone

RUN apt-get update -y
RUN apt-get install -y git mc curl python3.6 python3-pip
RUN pip3 install altinity-datasets
