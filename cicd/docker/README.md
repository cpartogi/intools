```
docker build -t intools:1.1.0 \
  -f cicd/docker/Dockerfile \
  --build-arg APP_NAME=intools \
  --build-arg APP_ENV=production \
  --build-arg APP_KEY=base64:1t/Z1pSMKFYcHXsgxDb+q/Maf5KUp/bJSlIlxT+DkkU= \
  --build-arg APP_DEBUG=false \
  --build-arg APP_URL=http://localhost \
  --build-arg LOG_CHANNEL=stack \
  .
```
