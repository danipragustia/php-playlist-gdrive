<!DOCTYPE HTML>
<html>
<head>
  <title>PHP Playlist Google Drive</title>
  <style>
    body {
      margin-top:20px;
      margin-left:30px;
      margin-right:30px;
      margin-bottom:20px;
    }
  </style>
</head>
<body>
  <h1>PHP Playlist Google Drive</h1>
  <p>Script to fetch Google Drive Music file to Playlist</p>
  <hr>
  <form method="GET" action="playlist.php">
    <input type="text" name="id" placeholder="ID Folder">
    <button type="submit">Fetch!</button>
  </form>
  <p>or simply with https://example.com/playlist.php?id=[FOLDER ID]</p>
  <ul>
    <li>Folder must be public</li>
  </ul>
</body>
</html>
