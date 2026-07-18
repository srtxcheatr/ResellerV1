# This PHP never touches Firestore — it only serves pages and the
# browser calls your Node backend directly via fetch(). So unlike the
# old backend, this needs ZERO extensions. Builds in seconds.
FROM php:8.3-apache
COPY . /var/www/html/
EXPOSE 80
