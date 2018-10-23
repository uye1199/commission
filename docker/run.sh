#!/bin/bash

usage()
{
    echo "usage:"
    echo "standart mode:    $0"
    echo "build images:     $0 -b"
    echo "daemon mode:      $0 -d"
    echo "show logs:        $0 -l"
    echo "stop containers:  $0 -s"
    echo -e "\n\n"
}

if [ "$#" == "0" ]; then
    usage
	docker-compose up
	exit 0
fi


while (( "$#" )); do
    case $1 in
        -b | --build )
            echo "docker-compose up --build"
            shift
            docker-compose up --build
            ;;

        -l | --logs )
            echo "docker-compose logs -f"
            shift
            docker-compose logs -f
            ;;

        -d | --daemon )
            echo "docker-compose up -d"
            shift
            docker-compose up -d
            ;;

        -s | --stop )
            echo "docker-compose stop"
            shift
            docker-compose stop
            ;;

        -h | --help )
            usage
            exit
            ;;

        --default)
            docker-compose up
            shift # past argument
            ;;

        * )
            echo "docker-compose up"
            docker-compose up
            shift # past argument
            ;;

    esac
    shift
done
