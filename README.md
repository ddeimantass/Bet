## Make bet task

### Build and Run

- Build and run containers
```
docker-compose up --build -d
```

- Enter web container and run migrations
```
docker exec -it web bash
composer install
bin/console d:d:c
bin/console d:m:m -n
```

### Run Tests

- Run tests inside web container
```
bin/phpunit 
```

### API

#### Bet Endpoint

- Method: POST
- URI: /api/bet
- Success response code: 201 
- Request example:
```
{
	"player_id": 1,
	"stake_amount": "100.71",
	"selections": [
		{
			"id": 1,
			"odds": "2.001"
		},
		{
			"id": 2,
			"odds": "1.01"
		}
	]
}
```
