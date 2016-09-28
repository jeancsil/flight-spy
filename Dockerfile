FROM php

ADD . /flight-spy
COPY docker/php.ini /usr/local/etc/php/

CMD ["/bin/sh"]
