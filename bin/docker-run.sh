#!/usr/bin/env bash
#Author Jean Silva <me@jeancsil.com>

YELLOW='\033[1;33m'
GREEN='\033[0;32m'
WHITE='\033[0;97m'
NO_COLOR='\033[0m'

DETACHED=""



if [ "$1" == "-h" ] || [ "$1" == "--help" ]; then
    printf "${YELLOW}Usage:${NO_COLOR}"
    echo ""
    printf "${WHITE} $0 [options]${NO_COLOR}"
    echo ""

    printf "${YELLOW}Options:${NO_COLOR}"
    echo ""
    printf "${GREEN}  -h, --help${NO_COLOR}            ${WHITE}Display this help message${NO_COLOR}"
    echo ""
    printf "${GREEN}  -d, --detached${NO_COLOR}        ${WHITE}Detaches the docker container from the terminal${NO_COLOR}"
    echo ""

    exit 0
fi

if [ "$1" == "-d" ] || [ "$1" == "--detached" ]; then
    DETACHED="-d"
fi

echo docker run ${DETACHED} -t -i jeancsil/flight-spy
