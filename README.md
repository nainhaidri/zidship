# Zidship Task
This task aims to develop a unified interface for creating, cancelling and tracking shipments across different courier service providers.

## Idea
The main idea behind interfacing the management of shipments is to expect same request parameters from client, pass them onto the desired courier and give it back the same response for every courier integrated. This would help other services to pass same parameters and expect same response for all the couriers in the system.

### Interfaces
I have implemented __Fedex__ as the proof of concept of how the requests and responses are mapped in this interface. For other couriers, the code can be written by implementing __CourierInterface__ or __CancellableCourierInterface__ depending on the type of Courier to be integrated with the system.

### Enums
Since all the couriers have their own Enums for __Service Types, Package Types, Payment Types, Pickup Types__ and __Delivery Status codes__, I have created a dedicated directory for Enums. For every new courier, these Enums should be defined. For interface level Enums, their mappings are defined in config folder of application.

### Architecture
While designing the application, SOLID principles of Object Oritented Programming were kept in mind.

### Tradeoffs
Whenever you got to design an application which authenticates with third party providers such as Couriers in this case, you have to keep the security in mind considering that if someone has access to your Sensitive information, he can have open access to all the other third party APIs as well.
The same problem arised here. There were two approaches to go about this. Client-side secrets storage or server side. 

Lets talk about __client side__ first. The __advantages__ that come along with client side storage of credentials are
1. Every client can authenticate with only the courier on which the shipment is to be created. Instead of server managing authentication with all of the Courier companies.
2. Also decentralization, which means that if someone has got access to our token, it does not directly mean that he can access third party tokens as well. He will have to authenticate on all the platforms manually and as required.

__Disadvangtages:__
1. For every new courier, consumers of this service will have to add integration for them as well, which increases the overall complexity of project.
2. If third party authentication is managed at client side, then all third party secrets will be stored at client side, which means if client side storage is hacked, he can have credentials to all of our courier platforms

Now lets move on to __server side disadvantages__ first:
1. On server side, all the third party __authentication__ tokens are to be managed which may or may not be required by the clients/consumers.
2. __Centralization__ of tokens which increases secruity risk of third party tokens

__Advantages of Server Side Auth__
1. New couriers integrations can be done irrespective of consumers of service, which means addition/removal of couriers from the system.
2. Since servers are isolated from client applications, more security layers and measures can be applied to servers e.g. ORIGINS, public/private key authentication etc.

__Why I chose Server Side over Client Side__
1. Even with the risk of centralization of tokens, it is better to save tokens at server side to avoid multiple different authentication calls to third party APIs. The returned token from the mentioned Auth requests can be encrypted before storing to the database which means even if the database is compromised, they will have encrypted tokens instead of plain access tokens.
2. __High throughput. Low latency__ . If I already have a valid token for a courier, I can directly fetch it from my database instead of authenticating with the third party courier.
3. __High performace__ Servers are mostly isolated tier of any application whose sole purpose is to process the request and give proper response to the client whereas the client can have different applications running on their systems impacting the application's performance.
4. Being a backend developer, I don't mind covering the hard yards just to make client's life a little easier. Cheers ;) !

__Reason for choosing Postgres__
1. ACID compiant out of the box.
2. Supports inheritance and complex relationship between tables.
3. Supports a wide range of data types like JSONs, Hash Tables etc.

### Tracking (Not Done)
I was not able to generate Fedex tracking response from Fedex since I do not have any active shipping account. I tried to reach support a few times but they have not answered yet. The request is integrated with body and parameters but my credentials do not have access to their Tracking APIs.

### Mapping of relevant status (Not Done)
I have created mapping of delivery statuses in Enums/FedexStatusCodes, but since tracking is not working, I could not map their statuses with my ENUMs. The definition is there in Enums as well as configuration but mapping could not be done due to Tracking APIs access.

## Usage
1. Install Docker
2. docker-compose up --build -d
3. docker-compose exec zid-php /bin/bash
4. cp .env.example .env
5. composer install
6. php artisan migrate
7. Project is now running at http://localhost:8086

## Technologies
Laravel 10
PHP 8.2
Postgres 16
Apache


