#!/usr/bin/sh
mariadb --defaults-file=db-smart-localhost.config --skip-ssl smart_school_www < $1
