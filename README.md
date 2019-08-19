# PHP Playlist Google Drive
Simple Implementation to fetch Google Drive Folder to **XSPF** format playlist.

## Next Features (maybe)

 - [ ] Support Nginx
 - [ ] GUI Interface
 - [ ] Embed Playlist

## Installation
You need web server stack (PHP and Apache, nginx not support because using **Apache Environment Variables** ), to run this script.

For Windows you can use [XAMPP](apachefriends.org) or [Laragon](laragon.org)

PHP Extension Required :
- cURL

## Usage
You need change **[CLIENT ID]** and **[CLIENT_SECRET]** at `playlist.php`

```PHP
$client_id = '[CLIENT_ID]';
$client_secret = '[CLIENT_SECRET]';
```
and navigate to `index.php` or `http://localhost/playlist.php?id=[FOLDER_ID]`

## License

[MIT](https://opensource.org/licenses/MIT)
