FROM alpine:3.20.0

ARG UUID=1000
ARG UGID=1000

ENV UUID $UUID
ENV UGID $UGID

COPY --from=node:20-alpine /usr/lib /usr/lib
COPY --from=node:20-alpine /usr/local/lib /usr/local/lib
COPY --from=node:20-alpine /usr/local/include /usr/local/include
COPY --from=node:20-alpine /usr/local/bin /usr/local/bin

RUN node -v
RUN npm install -g yarn --force
RUN yarn -v

RUN apk add --no-cache git openssh bash

RUN addgroup -S $UGID && adduser -u $UUID -G $UGID -S app -s /bin/bash -D app

WORKDIR /app

USER app
