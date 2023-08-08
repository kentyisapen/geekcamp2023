<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Laravel</title>

    <!-- Fonts -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>


</head>

<body class="antialiased">
    <header>
        <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
            <div class="container-fluid">
                <a class="navbar-brand" href="#">関西弁Generator</a>
            </div>
        </nav>
    </header>
    <div class="container mt-4">
        <form action="{{ url('/') }}" method="post">
            @csrf
            <div class="row">
                <div class="col-md-6 col offset-md-3">
                    <label for="pre-text" class="form-label">元の言葉</label>
                    <textarea name="pre-text" id="pre-text" class="form-control" rows="5">{{ $preText }}</textarea>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-6 col offset-md-3">
                    <label for="dialect" class="form-label">方言を選択</label>
                    <select name="dialect" id="dialect" class="form-select">
                        <option value="0">関西弁</option>
                        <option value="1">京都弁</option>
                    </select>
                </div>
            </div>
            <div class="row mt-4">
                <div class="col-md-6 col offset-md-3 text-end">
                    <button type="submit" id="submit" class="btn btn-primary ">
                        翻訳
                    </button>
                </div>
            </div>
        </form>
        <div class="row mt-4">
            <div class="col-md-6 col offset-md-3">
                <label for="after-text" class="form-label">翻訳後の言語</label>
                <textarea id="after-text" class="form-control" rows="5">{{ $value }}</textarea>
            </div>
        </div>
    </div>

</body>

</html>
