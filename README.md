
# utopia-restful
Utopia Network RESTful API written in PHP

## Installation

```bash
composer update
cd public_html
cp example.htaccess .htaccess
cd ../test/data
cp example.htaccess .htaccess
```

set `utopia_token` and `utopia_http_port` in `.env`

## Usage

example

```bash
curl http://rest/api/uns/sync_info
```

result:

```json
{
	"last_record_names_registered": "upbit",
	"local_blocks": 194,
	"peers_connected": 21,
	"state": "Idle",
	"total_blocks": 194
}
```
