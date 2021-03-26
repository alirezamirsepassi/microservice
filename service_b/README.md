# Service B

This service is a Kafka consumer which adds "Bye" to the messages on "Topic_B" and saves the result directly in the
database.

## Requirements

- PHP rdkafka extension
- PHP pdo, pdo_pgsql and pgsql extensions