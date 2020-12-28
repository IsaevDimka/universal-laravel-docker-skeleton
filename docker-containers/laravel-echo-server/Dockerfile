FROM node:15-alpine

RUN apk add --update \
    htop \
    nano \
    mc

# Create app directory
RUN mkdir -p /usr/src/app
WORKDIR /usr/src/app

# global install laravel-echo-server
RUN npm i -g laravel-echo-server

# Bundle app source
COPY laravel-echo-server.json /usr/src/app/laravel-echo-server.json

CMD [ "laravel-echo-server", "start",  "--dir=/usr/src/app/", "--force" ]