#!/usr/bin/env bash

echo "Configure PostgreSQL test database"

psql -U postgres -c 'create database zenddb_test;'
