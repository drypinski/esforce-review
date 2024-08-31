```bash
# Clone this project
git clone git@github.com:drypinski/esforce-review.git

# Change directory
cd esforce-review
```

#### Create `.env` file from `.env.dist` copy and update values
```bash
cp .env.dist .env
```

```dotenv
# You can add your system ID if required
UUID=your-system-user-id
UGID=your-system-user-group-id
```

1. Then run `make init`
2. Waiting for complete
3. Visit http://esforce.localhost

---
Traefik: http://traefik.esforce.localhost/dashboard/
API: http://api.esforce.localhost
Application: http://esforce.localhost
