#!/usr/bin/sh
mariadb --defaults-file=db-smart-majordomo.config --skip-ssl b171063_smart_school < $1
