Preview Github README.md
========================

## Requirements ##
* PHP 5.3+
* curl enabled
* Internet connection to send POST request to Github API

## How to use ##
You have project that need to be published onto Github and you're not
sure about how the README.md will render in your repository's homepage.
Assuming your local development is accessible via `http://localhost/`
and you're cloning this preview scripts into the docroot of your localhost.
Now you can preview your local README.md:

```
http://localhost/preview_github_readme.php
```

or you can use PHP built-in server:

```
php -S localhost:8080
```

and open above url with specified port.

`preview_github_readme.php` looks for passed arg `?file=/path/to/readme.md` and
if the file exists, send it to Github Markdown's API, otherwise lookup README.md,
Readme.md, or readme.md file in root directory.

The markup and stylesheet for previewing are copied from Github.
