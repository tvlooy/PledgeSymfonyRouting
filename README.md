# Symfony pledge/unveil routing support

This bundle adds PHP attributes `#[Pledge]` and `#[Unveil]` so you can add these to your routes.
It's possible to add these attributes to your route class, or method and it is possible to add multiple of them.

## Installing

```
composer require ctors/pledge-symfony-routing
```

The bundle requires ext/pledge, which is available as an OpenBSD port and package.
See [pecl-pledge](https://github.com/tvlooy/php-pledge/#openbsd-installation) for installation instructions.

```
cd /usr/ports/www/pecl-pledge
env FLAVOR="php82" make install

pkg_add pecl82-pledge-2.1.0
```

## Using

```
#[Unveil('/', 'r')]
#[Unveil('/htdocs/var/log', 'rwc')]
#[Unveil('/htdocs/var/cache', 'rwc')]
class DnsLookupController extends AbstractController
{
    #[Route('/hello', name: 'hello')]
    #[Unveil] // Disallow future unveil calls
    #[Pledge('stdio rpath wpath cpath fattr flock')]
    public function index(): Response
    {
        return $this->render('hello/index.html.twig');
    }
}
```

If you want to write in an additional directory you can modify it like this:

```
#[Unveil('/', 'r')]
#[Unveil('/htdocs/var/log', 'rwc')]
#[Unveil('/htdocs/var/cache', 'rwc')]
class DnsLookupController extends AbstractController
{
    #[Route('/hello', name: 'hello')]
    #[Unveil('/htdocs/src/Controller', 'rwc')]
    #[Unveil] // Disallow future unveil calls
    #[Pledge('stdio rpath wpath cpath fattr flock')]
    public function index(): Response
    {
        file_put_contents(__DIR__.'/test', 'ohai');

        return $this->render('hello/index.html.twig');
    }
}
```

If you want to connect to a MariaDB database over TCP/IP, add the `inet` pledge:

```
#[Unveil('/', 'r')]
#[Unveil('/htdocs/var/log', 'rwc')]
#[Unveil('/htdocs/var/cache', 'rwc')]
class DnsLookupController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
    ) {
    }

    #[Route('/hello', name: 'hello')]
    #[Unveil] // Disallow future unveil calls
    #[Pledge('stdio rpath wpath cpath fattr flock inet')]
    public function index(): Response
    {
        return $this->render(
            'hello/index.html.twig',
            [
                'users' => $this->userRepository->findAll(),
            ]
        );
    }
}
```

See [pecl-pledge](https://github.com/tvlooy/php-pledge/#fpm-configuration) for configuring this on an FPM pool level.

## Notes

Make sure to set PHP-FPM `pm.max_requests = 1` so you don't reuse a pledged/unveiled process.

