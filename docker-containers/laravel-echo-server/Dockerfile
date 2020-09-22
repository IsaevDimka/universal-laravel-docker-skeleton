FROM node:alpine

# Create app directory
RUN mkdir -p /usr/src/app
WORKDIR /usr/src/app

RUN npm i -g laravel-echo-server

RUN apk add --update \
    python \
    python-dev \
    py-pip \
    build-base \
    nano \
    mc

# Bundle app source
COPY laravel-echo-server.json /usr/src/app/laravel-echo-server.json

EXPOSE 3000
CMD [ "laravel-echo-server", "start",  "--dir=/usr/src/app/", "--force" ]