<?php
session_start();

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Creative Wallet - User page</title>

  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-aFq/bzH65dt+w6FI2ooMVUpc+21e0SRygnTpmBvdBgSdnuTN7QbdgL+OapgHtvPp" crossorigin="anonymous" />
  <link rel="stylesheet" href="../style.css" />
</head>

<body>
  <div class="bg-cream h-100 pt-2 position-relative">
    <header>
      <nav class="navbar navbar-expand-xl navbar-dark bg-dark mx-2 rounded-3" aria-label="toggle navigation">
        <div class="container">
          <a class="navbar-brand" href="#">
            <svg xmlns="http://www.w3.org/2000/svg" height="30" fill="currentColor" class="bi bi-wallet-fill me-1 mb-1" viewBox="0 0 16 16">
              <path d="M1.5 2A1.5 1.5 0 0 0 0 3.5v2h6a.5.5 0 0 1 .5.5c0 .253.08.644.306.958.207.288.557.542 1.194.542s.987-.254 1.194-.542C9.42 6.644 9.5 6.253 9.5 6a.5.5 0 0 1 .5-.5h6v-2A1.5 1.5 0 0 0 14.5 2z" />
              <path d="M16 6.5h-5.551a2.7 2.7 0 0 1-.443 1.042C9.613 8.088 8.963 8.5 8 8.5s-1.613-.412-2.006-.958A2.7 2.7 0 0 1 5.551 6.5H0v6A1.5 1.5 0 0 0 1.5 14h13a1.5 1.5 0 0 0 1.5-1.5z" />
            </svg>
            CreativeWallet</a>
          <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainmenu" aria-controls="mainmenu" aria-expanded="false" aria-label="toggle navigation">
            <span class="navbar-toggler-icon"></span>
          </button>
          <div class="collapse navbar-collapse d-xl-flex justify-content-md-end" id="mainmenu">
            <hr class="line mt-3" />
            <ul class="navbar-nav mb-2 mb-md-0">
              <li class="nav-item mb-2 mb-md-0">
                <a class="nav-link active" aria-current="page" href="#">User page</a>
              </li>
              <li class="nav-item mb-2 mb-md-0">
                <a class="nav-link disabled" href="#">Add income</a>
              </li>
              <li class="nav-item mb-2 mb-md-0">
                <a class="nav-link" href="./add-expense.html">Add expense</a>
              </li>
              <li class="nav-item mb-2 mb-md-0">
                <a class="nav-link" href="./show-balance.html">Show balance</a>
              </li>
              <li class="nav-item mb-2 mb-md-0">
                <a class="nav-link disabled" href="#">Settings</a>
              </li>
              <li class="nav-item mb-2 mb-md-0">
                <a class="nav-link" href="../index.html">Logout</a>
              </li>
            </ul>
          </div>
        </div>
      </nav>
    </header>
    <main class="pb-75">
      <div class="container my-5">
        <div class="bg-light-red shadow p-5 rounded-3">
          <div class="text-center">
            <h1 class="h3 mb-4">This section is under construction</h1>
          </div>
        </div>
      </div>
    </main>
    <footer class="position-absolute w-100 bottom-0">
      <div class="bg-grey-blue mx-2 rounded-top-3">
        <div class="container">
          <div class="row d-flex justify-content-between align-items-center">
            <div class="col-md-4 d-flex justify-content-center justify-content-md-start">
              <p class="my-2">© 2024 CreativeWallet</p>
            </div>
            <div class="col-md-4 d-flex justify-content-center justify-content-md-end align-items-center">
              <p class="my-2">Author: Mateusz Przybyła</p>
              <a class="text-body-secondary" href="https://github.com/mateusz-przybyla" target="_blank"><img src="../assets/svg/github.svg" alt="graph-up-arrow" height="20" class="ms-3 my-1" /></a>
            </div>
          </div>
        </div>
      </div>
    </footer>
  </div>
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
  <script src="../index.js" type="text/javascript"></script>
</body>

</html>