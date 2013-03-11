Preview Github README.md
========================

The preview mimics repo homepage in Github.

## Requirements ##
* PHP 5.3+
* curl enabled
* Internet connection to send POST request to Github API

## How to use ##
You have project that need to be published onto Github and you're not
sure about how the README.md will render in your repository's homepage.

### Using CLI ###

```
$ ./preview_github_readme README.md
```

or you can pipe it to browser:

```
$ ./preview_github_readme README.md | browser
````

In daily use, it's worth to alias this long script's name:

```
alias gmd='/path/to/cloned-repo/preview_github_readme'
```

### Using webserver ####

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

## How it Works

`preview_github_readme.php` looks for readme file and
if the file exists, send it to Github Markdown's API, otherwise lookup README.md,
Readme.md, or readme.md file in root directory.

The markup and stylesheet for previewing are copied from Github.
