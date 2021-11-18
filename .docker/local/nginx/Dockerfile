# Image
FROM nginx:stable

# Add config
COPY default.conf /etc/nginx/conf.d/default.conf

# Install package
RUN apt-get update && apt-get install -y \
    composer

WORKDIR /api
