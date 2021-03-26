# Requester

This service creates an event loop of 50ms using [React PHP](https://reactphp.org/) which acts as a transceiver between
[Broker](../broker) and itself. ( also called LP or Long Polling )

When the service executes, at first, it sends initiative data to the [Broker](../broker) using HTTP in order to start the flow. Then,
for each 50ms an HTTP request is sent to get the information back from the [Broker](../broker).