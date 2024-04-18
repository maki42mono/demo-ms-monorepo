command for manual start:
```
docker-compose -f config/_inf/dev-user/docker-compose.yml up -d

echo "APP_ENV=dev
LF_API_URL=http://api.legion.local
AUTH_ENDPOINT=http://legion.local/back-api/auth" >> .env.local
```