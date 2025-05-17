<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Register</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/css/bootstrap.min.css"
          rel="stylesheet"
          integrity="sha384-4Q6Gf2aSP4eDXB8Miphtr37CMZZQ5oXLH2yaXMJ2w8e2ZtHTl7GptT4jmndRuHDT"
          crossorigin="anonymous">
</head>
<body class="bg-light">

<div class="container d-flex justify-content-center align-items-center" style="min-height: 100vh;">
    <div class="card shadow p-5" style="max-width: 500px; width: 100%;">
        <h2 class="text-center mb-4">Register</h2>

        <form action="register_process.php" method="POST">
            <div class="mb-3">
                <input type="email" name="email" class="form-control" placeholder="Email" required>
            </div>

            <div class="mb-3">
                <input type="text" name="name" class="form-control" placeholder="Full Name / Market Name" required>
            </div>

            <div class="mb-3">
                <input type="password" name="password" class="form-control" placeholder="Password" required>
            </div>

            <div class="mb-3">
                <input type="text" name="city" class="form-control" placeholder="City" required>
            </div>

            <div class="mb-3">
                <input type="text" name="district" class="form-control" placeholder="District" required>
            </div>

            <div class="mb-4">
                <select name="type" class="form-select" required>
                    <option value="" disabled selected>Select user type</option>
                    <option value="market">Market</option>
                    <option value="consumer">Consumer</option>
                </select>
            </div>

            <button type="submit" class="btn btn-success w-100">Register</button>
        </form>
    </div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.6/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-j1CDi7MgGQ12Z7Qab0qlWQ/Qqz24Gc6BM0thvEMVjHnfYGF0rmFCozFSxQBxwHKO"
        crossorigin="anonymous"></script>
</body>
</html>
