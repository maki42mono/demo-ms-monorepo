command for manual start:
```
docker-compose -f config/_inf/dev-auth/docker-compose.yml up -d

echo "APP_ENV=dev
LF_API_URL=http://api.legion.local" >> .env.local
```