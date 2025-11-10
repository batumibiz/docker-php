FROM alpine:3.22

# Arguments for user UID/GID synchronization
ARG USER_ID=1000
ARG GROUP_ID=1000

# Install Apache, PHP 8.4 FPM, and necessary modules (minimal)
RUN apk update && \
    apk add --no-cache \
        apache2 \
        apache2-proxy \
        php84 \
        php84-fpm && \
    # Create synchronized user and group
    addgroup -S -g ${GROUP_ID} appgroup && \
    adduser -S -D -H -u ${USER_ID} -G appgroup appuser && \
    # Configure Apache to use the custom user/group
    sed -i -e 's/^User apache/User appuser/' -e 's/^Group apache/Group appgroup/' /etc/apache2/httpd.conf && \
    # Configure PHP-FPM to use the custom user/group and Unix socket
    sed -i -e 's/user = nobody/user = appuser/' \
           -e 's/group = nobody/group = appgroup/' \
           -e 's/listen = 127.0.0.1:9000/listen = \/run\/php84-fpm.sock/' \
           -e 's/;listen.owner = nobody/listen.owner = appuser/' \
           -e 's/;listen.group = nobody/listen.group = appgroup/' \
           -e 's/;listen.mode = 0660/listen.mode = 0660/' \
           /etc/php84/php-fpm.d/www.conf && \
    # Set up directories and permissions
    mkdir -p /var/www/html /run/apache2 /run/php84 && \
    chown -R appuser:appgroup /var/www/html /run/apache2 /run/php84 && \
    # Minimal Apache configuration for PHP handling
    echo 'LoadModule proxy_module modules/mod_proxy.so' >> /etc/apache2/httpd.conf && \
    echo 'LoadModule proxy_fcgi_module modules/mod_proxy_fcgi.so' >> /etc/apache2/httpd.conf && \
    echo '<FilesMatch \.php$>' >> /etc/apache2/httpd.conf && \
    echo '    SetHandler "proxy:unix:/run/php84-fpm.sock|fcgi://localhost/"' >> /etc/apache2/httpd.conf && \
    echo '</FilesMatch>' >> /etc/apache2/httpd.conf && \
    echo 'DirectoryIndex index.php index.html' >> /etc/apache2/httpd.conf && \
    echo 'DocumentRoot /var/www/html' >> /etc/apache2/httpd.conf && \
    echo '<Directory /var/www/html>' >> /etc/apache2/httpd.conf && \
    echo '    AllowOverride None' >> /etc/apache2/httpd.conf && \
    echo '    Require all granted' >> /etc/apache2/httpd.conf && \
    echo '</Directory>' >> /etc/apache2/httpd.conf

# Expose port
EXPOSE 80

# Start PHP-FPM in background and Apache in foreground
CMD ["/bin/sh", "-c", "/usr/sbin/php-fpm84 & /usr/sbin/httpd -D FOREGROUND"]
