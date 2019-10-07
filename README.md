# MayMeow/Testing plugin for CakePHP

Plugin for easier writing tests for CakePHP. Create factories to create testing data with Faker instead of
loading TableRegistry and calling new entity and save

## Installation

You can install this plugin into your CakePHP application using [composer](https://getcomposer.org).

The recommended way to install composer packages is:

```
composer require maymeow/cakephp-testing
```

And load it to your project

## How to use it

First what you need to do is write factory for your model. Skeleton looks like 

```php
<?php

namespace Identity\Database\Factories;

use Faker\Generator;
use MayMeow\Testing\Factories\ModelFactory;
use MayMeow\Testing\Factories\ModelFactoryInterface;

class UserFactory implements ModelFactoryInterface
{
    /**
     * @param array|null $data
     * @return \Cake\Datasource\EntityInterface|false
     */
    public function get(array $data = null)
    {
        $factory = new ModelFactory($data);
   
        return $factory->define('Identity.Users', function (Generator $faker) {
            return [
                // Your model data
            ];
        });
    }
}
```

Then you can add your data. For now factories only support belongs to relationship.

```php
// ...
eturn $factory->define('Identity.Users', function (Generator $faker) use ($api_key_plain) {
    return [
        'email' => $faker->email,
        'password' => (new \Cake\Auth\DefaultPasswordHasher())->hash('pa$$word'),
        // Belogs to delation can be added by calling another factory
        'role_id' => function () {
            return (new RoleFactory())->get()->id;
        },
        'api_key_plain' => $api_key_plain,
        'api_key' => (new \Cake\Auth\DefaultPasswordHasher())->hash($api_key_plain)
    ];
});
// ...
```

After you have created your factories you can call them in your test for example in `setuUp` function.

```php
$this->user = (new UserFactory())->get();
```

When you need add relation or just want update any field in factory you can do this as following

```php
$this->post = (new PostFactory())->get(['address_id' => $address->id]);
```

## Relationships

Plugin only support creating `BelongsTo` relations. So if you need to create relations `User -> HasMany -> Posts` you
have to create user and then many posts with user_id of this user

```php
// EXAMPLE Create 10 posts for one user
$this->user = (new UserFactory())->get();

for ($i=1; $i<=10; $i++) {
    (new PostFactory())->get(['user_id' => $this->user->id]);
}
``` 

## Contributing

1. Fork it!
2. Create your feature branch: `git checkout -b my-new-feature`
3. Commit your changes: `git commit -am 'Add some feature'`
4. Push to the branch: `git push origin my-new-feature`
5. Submit a pull request :D

## License

MIT

## Support

You can support me on my [patreon](https://www.patreon.com/maymeow)

