command for manual start:
```
docker-compose -f config/_inf/dev-order-chat/docker-compose.yml up -d

echo "APP_ENV=dev
LF_API_URL=http://api.legion.local
STREAM_CHAT_KEY=zcmkjq9tcnwy
STREAM_CHAT_SECRET=tgpk42hejrryew4awhrmtpxy3t3h54t2cfa83bmwps63xkj26qxrs5g2jaq8dr9n" >> .env.local
```