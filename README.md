# Simple PHP/Symfony Test

This is a test with instructions from this [link](https://docs.google.com/document/d/1xi3SRTPoGwoL7HU4zXf1u8KsLG0lXbkw3ePFP6M2xbQ/edit#heading=h.umvywnnhccz)

## Getting started

**Cloning the repository**

```
git clone https://etor_nam@bitbucket.org/etor_nam/ecommerce.git
cd ecommerce/
```

**Installing dependencies**

```
composer install
```

**Create .env file**

```
create a .env file and copy the contents of the .env.sample file into it. Configure your database and mailer settings
```

**Create database by running this command**

```
php bin/console doctrine:database:create
```

**Migrate tables by running this command**

```
php bin/console doctrine:migrations:migrate or php bin/console doctrine:schema:update --force
```

**Create admin user by running this command**

```
php bin/console doctrine:fixtures:load
An admin with email:admin@example.com and password:admin_pass will be created
```

**Start application**

```
symfony server:start or php bin/console server:run
```

### Features

- Authentication (Login,register,profile,password reset)
- Superadmin CRUD users
- Superadmin CRUD category
- Superadmin CRUD products
- Products display and filtering based on categories
- Shopping cart for adding products to cart
- Checking out shopping cart
- Superadmin viewing shopping orders
