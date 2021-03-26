#!/usr/bin/env bash

/opt/bitnami/kafka/bin/kafka-topics.sh --create --zookeeper zookeeper:2181 --topic Topic_A --partitions 1 --replication-factor 1 --if-not-exists
/opt/bitnami/kafka/bin/kafka-topics.sh --create --zookeeper zookeeper:2181 --topic Topic_B --partitions 1 --replication-factor 1 --if-not-exists
