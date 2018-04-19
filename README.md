# symfony-api-skeleton
 JSON REST API simple skeleton based on symfony-flex, JWT, api-platform, fosuserbundle etc.

## Installation

### 1. Clone repository
Create project :

```bash
composer create-project svp1989/symfony-api-skeleton:@dev
```
Or clone repository from GitHub:

```bash
git clone https://github.com/svp1989/symfony-api-skeleton.git
```

### 2. Dependencies installation

Install all needed dependencies:

```bash
composer install
```

### 3. Create JWT auth keys

Create JWT auth keys and configure `.env`:

```bash
ssh-keygen -t rsa -b 4096 -f private.pem
openssl rsa -in private.pem -pubout -outform PEM -out public.pem

```


### 4. Configuration

Create `.env` file, which contains all the necessary
environment variables that application needs:

```bash
cp .env.dist .env
```

### 5. Create database

Create database and upload fixtures:

```bash
bin/console doctrine:database:create 
bin/console doctrine:schema:create
bin/console doctrine:fixtures:load
```
### 6. Start server

Start server and open in the browser http://127.0.0.1:8000/api/doc:

```bash
bin/console server:start
```
## Authorisation

Go to route /api/login:<br>
**ROLE_USER**:<br>
    --username:**user**<br>
    --password:**user**<br>
    
**ROLE_ADMIN**<br>
    --username:**admin**<br>
    --password:**admin**<br>