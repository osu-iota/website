#!/usr/bin/env bash

if [ ! -e .git ]; then
    echo
    echo "This file must be run from the root of the repository"
    echo
    echo "usage: sh scripts/deploy.sh"
    echo
    exit 1
fi

git stash
git pull origin master
sh scripts/allow.sh
