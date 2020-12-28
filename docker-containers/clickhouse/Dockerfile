FROM yandex/clickhouse-server:20.8.9.6

LABEL Description="Yandex ClickHouse"

ARG TIMEZONE
RUN ln -snf /usr/share/zoneinfo/${TIMEZONE} /etc/localtime && echo ${TIMEZONE} > /etc/timezone

RUN apt-get update && \
    apt-get install -y --force-yes --no-install-recommends \
    nano \
    mc \
    htop \
    wget

RUN rm -r /var/lib/apt/lists/*