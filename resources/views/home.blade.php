<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Mehar Baba Restaurant - ERP</title>

<style>
    *{
        margin: 0;
        padding: 0;
        box-sizing: border-box;
        font-family: Arial, sans-serif;
    }

    body, html{
        height: 100%;
        width: 100%;
    }

    /* ===== FULL SCREEN BACKGROUND IMAGE ===== */
    body{
        background-image: url("/home_image.PNG");
        background-size: cover;       /* poora cover kare, crop na ho */
        background-position: center;
        background-repeat: no-repeat;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    /* ===== FORM BOX ===== */
    .form-box{
        width: 100%;
        max-width: 450px;
        background: rgba(255,255,204,0.85); /* thoda transparent */
        padding: 25px;
        border-radius: 10px;
        text-align: center;
        box-shadow: 0 5px 15px rgba(0,0,0,0.2);
    }

    .input{
        width: 90%;
        height: 35px;
        margin: 8px 0;
        padding-left: 10px;
        font-size: 15px;
        border-radius: 4px;
        border: 1px solid #666;
    }

    .btn{
        width: 120px;
        padding: 8px 0;
        margin: 10px 5px;
        font-size: 16px;
        border-radius: 6px;
        border: 1px solid #444;
        background: white;
        cursor: pointer;
    }

    .btn:active{
        background: #dcdcdc;
    }
</style>
</head>

<body>

<div class="form-box">
    <form action="{{ route('login_checker') }}" method="POST">
        @csrf
        <input type="text" name="username" class="input" placeholder="Username" required><br>
        <input type="password" name="password" class="input" placeholder="Password" required><br>
        <button type="submit" class="btn">Ok</button>
        <button type="reset" class="btn">Cancel</button>
    </form>
</div>

</body>
</html>
